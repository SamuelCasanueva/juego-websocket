# 🌐 Arquitectura de Red y Gateway Unificado (HTTP + WebSockets)

Documentación técnica y explicativa sobre la solución implementada para compartir el juego **RobCo TermLink** por Internet mediante un **único túnel** (Cloudflare Tunnel, Ngrok, etc.), unificando el tráfico web (Laravel) y el tráfico en tiempo real (Reverb).

---

## 📌 1. El Problema: Dos servidores locales vs. Un solo túnel

Cuando desarrollas o ejecutas la aplicación localmente en tu máquina, tienes **dos servidores independientes** corriendo en paralelo:

1. **Servidor Web Laravel (HTTP / PHP):**
   - Corre con `php artisan serve` en el puerto **`8000`**.
   - Responde a las peticiones web: vistas Blade, HTML, estilos CSS, llamadas a la API, rutas como `/lobby`, `/game/{id}`, etc.
2. **Servidor WebSocket Reverb (WS / PHP):**
   - Corre con `php artisan reverb:start` en el puerto **`8080`**.
   - Maneja la conexión bidireccional persistente en tiempo real: el chat del lobby, la lista de terminales activas, los intentos de hackeo y la sincronización del juego.

```
       Máquina Local (Tu PC)
┌─────────────────────────────────┐
│  Laravel HTTP  ---> Puerto 8000 │
│  Reverb WS     ---> Puerto 8080 │
└─────────────────────────────────┘
```

---

### ¿Por qué fallaba al abrir un túnel tradicional?

Las herramientas de túnel público como **Cloudflare Tunnel (`cloudflared`)**, **Ngrok** o **Localtunnel** toman **un único puerto local** y lo exponen a Internet bajo un dominio seguro con HTTPS (puerto 443 estándar).

Por ejemplo, si ejecutabas:
```bash
cloudflared tunnel --url http://localhost:8000
```
Obtenías una URL pública como:
```
https://subdominio-aleatorio.trycloudflare.com
```

#### Los 2 problemas críticos que ocurrían:

1. **El puerto 8080 quedaba aislado:**  
   La web cargaba bien porque el túnel apuntaba al puerto 8000. Pero cuando el navegador ejecutaba el archivo JavaScript de Laravel Echo, este intentaba conectarse a:
   ```
   wss://subdominio-aleatorio.trycloudflare.com:8080  ❌ (FALLO DE CONEXIÓN)
   ```
   Cloudflare **no tiene abierto el puerto 8080** hacia tu máquina; solo escucha en el puerto seguro estándar `443` (HTTPS/WSS).
2. **Abrir dos túneles era inviable:**  
   Si abrías un túnel para el puerto 8000 y otro túnel para el 8080, Cloudflare te daba **dos URLs aleatorias distintas**. Tenías que editar el archivo `.env`, cambiar las variables de Reverb, recompilar los assets con Vite y reiniciar los servidores **cada vez que levantabas el túnel**.

---

## 💡 2. La Solución: El Gateway Proxy Unificado (`tunnel-gateway.mjs`)

Para resolver esto sin tocar tu `.env` ni depender de múltiples túneles, creamos un **Reverse Proxy (Pasarela Intermedia)** ligera escrita en Node.js que escucha en el puerto **`8008`**.

Ahora el túnel apunta **única y exclusivamente** al puerto 8008:

```bash
cloudflared tunnel --url http://localhost:8008
```

### Diagrama del flujo de tráfico:

```
                                      INTERNET
                                          │
                     https://xxxx.trycloudflare.com (Puerto 443)
                                          │
                                          ▼
                                TU COMPUTADORA LOCAL
                        ┌─────────────────────────────────┐
                        │      ROBCO TUNNEL GATEWAY       │
                        │       (Puerto Local 8008)       │
                        └───────────────┬─────────────────┘
                                        │
           ¿Es una conexión             │           ¿Es una petición
           WebSocket (Upgrade)          │           Web normal (HTTP)
           o endpoint /app/*?           │           o vista Blade?
                  │                     │                  │
                  ▼                                        ▼
      ┌───────────────────────┐                ┌───────────────────────┐
      │     LARAVEL REVERB    │                │      LARAVEL WEB      │
      │     (Puerto 8080)     │                │     (Puerto 8000)     │
      └───────────────────────┘                └───────────────────────┘
```

---

## 🔍 3. ¿Cómo funciona por dentro `tunnel-gateway.mjs`?

El archivo `tunnel-gateway.mjs` utiliza la librería `http-proxy` y realiza 3 tareas fundamentales:

### A. Detección y desvío de WebSockets (`Upgrade`)
En el protocolo HTTP, las conexiones WebSocket no nacen como WebSockets directamente; comienzan como una petición HTTP que incluye la cabecera:
```http
Connection: Upgrade
Upgrade: websocket
```
El gateway intercepta este evento nativo de Node.js (`upgrade`) y redirige el flujo de datos (socket) directamente al servidor **Reverb en el puerto 8080**:

```javascript
server.on('upgrade', (req, socket, head) => {
    // Es un WebSocket (wss://) -> Enviar directo a Reverb (8080)
    proxy.ws(req, socket, head, { target: 'http://127.0.0.1:8080' });
});
```

### B. Enrutamiento inteligente de peticiones HTTP
Cuando llega una petición HTTP regular:
1. Si la ruta comienza con `/app/...` (endpoints que usa el cliente de Pusher/Reverb para autenticarse o verificar canales), la envía a **Reverb (`http://127.0.0.1:8080`)**.
2. Todas las demás rutas (`/`, `/lobby`, `/game/...`, archivos CSS/JS, login, etc.) las envía a **Laravel (`http://127.0.0.1:8000`)**.

```javascript
const server = http.createServer((req, res) => {
    if (req.url && req.url.startsWith('/app/')) {
        proxy.web(req, res, { target: 'http://127.0.0.1:8080' });
    } else {
        proxy.web(req, res, { target: 'http://127.0.0.1:8000' });
    }
});
```

### C. Normalización de cabeceras HTTPS y SSL
Cloudflare recibe a los usuarios en `https://` (puerto seguro 443), pero se comunica con tu túnel local mediante HTTP plano.

Si Laravel no sabe que el usuario original está navegando en HTTPS, Laravel genera enlaces internos con `http://` y puerto `:80`, provocando errores de contenido mixto (*Mixed Content*) o redirecciones infinitas.

El gateway detecta si la petición original vino por Cloudflare/HTTPS y normaliza las cabeceras:
```javascript
const isHttps = (req.headers['x-forwarded-proto'] === 'https') ||
                (req.headers['cf-visitor'] && req.headers['cf-visitor'].includes('https'));

if (isHttps) {
    req.headers['x-forwarded-proto'] = 'https';
    req.headers['x-forwarded-port'] = '443';
}
```

---

## ⚡ 4. La adaptación del Frontend (`resources/js/echo.js`)

Para que el cliente (el navegador) sepa a qué puerto conectarse sin tocar configuraciones manuales, adaptamos `resources/js/echo.js`:

```javascript
const isHttps = window.location.protocol === 'https:';
const currentHost = window.location.hostname;
const currentPort = window.location.port;

// Si estás usando un túnel HTTPS (Cloudflare / Ngrok / etc.)
const isTunnel = isHttps || (currentPort !== '8000' && currentHost !== 'localhost' && currentHost !== '127.0.0.1');

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: isTunnel ? currentHost : (import.meta.env.VITE_REVERB_HOST ?? currentHost),
    // En túnel HTTPS se usa el puerto 443 estándar (wss://). En local se usa el 8080.
    wsPort: isTunnel ? 443 : (import.meta.env.VITE_REVERB_PORT ?? 80),
    wssPort: isTunnel ? 443 : (import.meta.env.VITE_REVERB_PORT ?? 443),
    forceTLS: isHttps || isTunnel,
    enabledTransports: ['ws', 'wss'],
});
```

### ¿Qué hace esta lógica?
- **Si juegas en tu PC (`localhost:8000`):** Conecta directamente a `ws://localhost:8080`.
- **Si entras mediante la URL del túnel (`https://xxxx.trycloudflare.com`):**  
  Detecta automáticamente que estás en HTTPS, usa el mismo dominio público y conecta por `wss://` en el puerto `443`. La petición viaja por Cloudflare, llega al Gateway (8008) y este se la entrega limpia a Reverb (8080).

---

## 🚀 5. Resumen de ejecución para compartir el juego

Para jugar con amigos por Internet, necesitas tener abiertas **4 terminales**:

| Terminal | Comando | Función |
| :--- | :--- | :--- |
| **Terminal 1** | `php artisan serve` | Servidor Web de Laravel (Puerto 8000) |
| **Terminal 2** | `php artisan reverb:start` | Servidor WebSocket Reverb (Puerto 8080) |
| **Terminal 3** | `node tunnel-gateway.mjs` | Proxy unificador (Puerto 8008) |
| **Terminal 4** | `cloudflared tunnel --url http://localhost:8008` | Túnel público de Cloudflare |

👉 Tomas la URL `https://xxxx.trycloudflare.com` que te imprime la **Terminal 4** y se la pasas a tus amigos.  
**Todo funciona automáticamente:** navegación web, partidas multijugador, chat global del lobby y espectadores en tiempo real.

---

## 🛠️ 6. Preguntas frecuentes (FAQ)

### ¿Por qué aparece "502 Bad Gateway" en el navegador?
El Gateway (8008) intenta conectarse a los puertos 8000 o 8080. Si ves un error 502, significa que olvidaste iniciar `php artisan serve` o `php artisan reverb:start`.

### ¿Funciona también con Ngrok?
Sí. En vez de Cloudflare, puedes ejecutar:
```bash
ngrok http 8008
```
Y funcionará con exactamente la misma lógica de multiplexación.

### ¿Se necesita recompilar los assets con `npm run build`?
No es obligatorio si ya tienes `npm run dev` corriendo o los assets construidos en `public/build/`.
