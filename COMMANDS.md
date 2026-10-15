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

## 🗂️ Orden recomendado de arranque

```
1. php artisan migrate        ← solo la primera vez
2. php artisan serve          ← servidor web
3. php artisan reverb:start   ← websockets
4. npm run dev                ← assets
```

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

```powershell
# Limpiar salas obsoletas (espera > 20 min, jugando > 40 min, finalizadas > 2 hs)
php artisan robco:purge-rooms

# Limpiar salas especificando antigüedad en minutos (ej: inactivas por más de 10 min)
php artisan robco:purge-rooms --minutes=10

# Purgar TODAS las salas activas e incompletas inmediatamente
php artisan robco:purge-rooms --all

# Purgar absolutamente todas las salas (incluidas finalizadas y récords)
php artisan robco:purge-rooms --force-all
```

> 💡 **Métodos adicionales incluidos:**
> - **Auto-mantenimiento:** Cada vez que se consulta el Lobby, las salas abandonadas se eliminan en segundo plano sin intervención humana.
> - **Botón en el Lobby:** En el panel `[3] REGISTRO DE TERMINALES ACTIVAS`, presionar `⚡ [PURGAR SUBRED]`.
> - **Scheduler:** Programado en `routes/console.php` para ejecutarse automáticamente cada 15 minutos.


---

## ⚙️ Variables clave del .env

| Variable             | Valor por defecto       | Descripción                     |
|----------------------|-------------------------|---------------------------------|
| APP_URL              | http://localhost:8000   | URL del servidor Laravel        |
| REVERB_HOST          | localhost               | Host del servidor WebSocket     |
| REVERB_PORT          | 8080                    | Puerto del servidor WebSocket   |
| DB_CONNECTION        | sqlite                  | Base de datos (archivo local)   |
| BROADCAST_CONNECTION | reverb                  | Driver de broadcasting          |
| QUEUE_CONNECTION     | sync                    | Colas (sync = sin worker extra) |
