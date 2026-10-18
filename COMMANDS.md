# 🖥️ ROBCO TERMLINK — Comandos para levantar el proyecto

Este proyecto requiere **3 procesos corriendo en paralelo** (abrir 3 terminales separadas).

---

## 📋 Prerequisitos (primera vez)

```powershell
# Instalar dependencias PHP
composer install

# Instalar dependencias Node
npm install

# Generar clave de la aplicación (si no existe en .env)
php artisan key:generate

# Crear las tablas de la base de datos (SQLite, se crea automático)
php artisan migrate
```

---

## 🚀 Levantar el proyecto

Abrir **3 terminales** en la carpeta raíz del proyecto y ejecutar uno por terminal:

### Terminal 1 — Servidor PHP (Laravel)

```powershell
php artisan serve
```

> 🌐 Disponible en: http://localhost:8000

---

### Terminal 2 — Servidor WebSocket (Reverb)

```powershell
php artisan reverb:start
```

> 🔌 Escucha en: localhost:8080
> Gestiona los canales en tiempo real (sala de juego, lobby, eventos).

---

### Terminal 3 — Compilador de Assets (Vite)

```powershell
npm run dev
```

> ⚡ Compila JS/CSS con hot-reload.
> Incluye Laravel Echo, audio sintetizado y estilos de la terminal CRT.

---

## 🌐 Compartir con tus compañeros (Túnel 100% Automático)

Para compartir la partida por Internet con **un solo enlace** (unificando Web en 8000 + WebSockets en 8080 automáticamente):

### Paso 1: Levantar los 2 servicios habituales

- **Terminal 1:** `php artisan serve` (puerto 8000)
- **Terminal 2:** `php artisan reverb:start` (puerto 8080)

### Paso 2: Iniciar la pasarela unificada RobCo

- **Terminal 3:**

```powershell
node tunnel-gateway.mjs
```

_(Escucha en el puerto 8008 y rutea automáticamente las páginas a Laravel y los WebSockets a Reverb)._

### Paso 3: Abrir el túnel único

- **Terminal 4:**

```powershell
cloudflared tunnel --url http://localhost:8008
```

👉 **Listo:** Copias la URL de Cloudflare (`https://xxxx.trycloudflare.com`) y se la pasas a tus compañeros.  
**No hace falta configurar ningún puerto ni enlace secundario:** el chat, las salas y los WebSockets funcionan al 100% en tiempo real.

---

## 🛠️ Comandos útiles adicionales

```powershell
# Limpiar caché de configuración
php artisan config:clear

# Limpiar caché de rutas
php artisan route:clear

# Ver todas las rutas del proyecto
php artisan route:list

# Correr los tests
php artisan test

# Resetear y re-ejecutar las migraciones
php artisan migrate:fresh
```

---

## 🧹 Mantenimiento de la Subred (Limpieza de Terminales)

El sistema cuenta con **auto-limpieza transparente** (al ingresar al Lobby), botón interactivo en pantalla y comando de consola CLI para evitar acumulación de salas incompletas o abandonadas:

````powershell
# Limpiar salas obsoletas (espera > 20 min, jugando > 40 min, finalizadas > 2 hs)
php artisan robco:purge-rooms

# Limpiar salas especificando antigüedad en minutos (ej: inactivas por más de 10 min)
php artisan robco:purge-rooms --minutes=10

# Purgar TODAS las salas activas e incompletas inmediatamente
php artisan robco:purge-rooms --all

# Purgar absolutamente todas las salas (incluidas finalizadas y récords)
php artisan robco:purge-rooms --force-all
---

## 💬 Mantenimiento del Chat (Limpieza de Mensajes)

Para limpiar o vaciar el historial de mensajes de la base de datos:

```powershell
# Purgar todos los mensajes del chat global del Lobby
php artisan robco:purge-chat

# Purgar absolutamente todos los mensajes (Lobby + todas las salas de juego)
php artisan robco:purge-chat --all

# Purgar únicamente los mensajes de una sala en particular
php artisan robco:purge-chat --room=V-ABCD
````

> 💡 Al ejecutar el comando, si hay usuarios conectados al Lobby, sus pantallas se actualizarán automáticamente en tiempo real vía WebSocket mostrando `> CANAL DE SUBRED PURGADO // FRECUENCIA LIMPIA.` sin requerir recargar.

---

## ⚙️ Variables clave del .env

| Variable             | Valor por defecto     | Descripción                     |
| -------------------- | --------------------- | ------------------------------- |
| APP_URL              | http://localhost:8000 | URL del servidor Laravel        |
| REVERB_HOST          | localhost             | Host del servidor WebSocket     |
| REVERB_PORT          | 8080                  | Puerto del servidor WebSocket   |
| DB_CONNECTION        | sqlite                | Base de datos (archivo local)   |
| BROADCAST_CONNECTION | reverb                | Driver de broadcasting          |
| QUEUE_CONNECTION     | sync                  | Colas (sync = sin worker extra) |
