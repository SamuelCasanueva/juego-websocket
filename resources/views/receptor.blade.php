<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptor | Reverb WebSocket</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 por CDN (como en la guía de la cátedra) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #4f46e5;
            --bg: #0b0f19;
            --card: #151e32;
            --border: #243049;
            --text: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .wrap {
            width: 100%;
            max-width: 760px;
            text-align: center;
        }

        .header {
            margin-bottom: 24px;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid var(--border);
            padding: 6px 16px;
            border-radius: 9999px;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #eab308;
            box-shadow: 0 0 10px #eab308;
            transition: all 0.3s ease;
        }

        .dot.connected {
            background-color: #10b981;
            box-shadow: 0 0 12px #10b981;
        }

        .dot.disconnected {
            background-color: #ef4444;
            box-shadow: 0 0 12px #ef4444;
        }

        h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .stage-container {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px 24px;
            min-height: 240px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 24px;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        #target-div {
            padding: 24px 32px;
            border: 2px dashed #334155;
            border-radius: 14px;
            font-size: 1.25rem;
            color: #64748b;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            max-width: 90%;
            word-break: break-word;
        }

        .log-box {
            margin-top: 20px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 18px;
            font-family: ui-monospace, monospace;
            font-size: 0.8rem;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .channel-name {
            color: #818cf8;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="wrap">
    <div class="header">
        <div class="badge-status">
            <span class="dot" id="status-dot"></span>
            <span id="status-text">Conectando a Reverb...</span>
        </div>
        <h1>Pantalla Receptora</h1>
        <p class="subtitle">Escuchando eventos en tiempo real vía WebSocket (Laravel Reverb + Echo)</p>
    </div>

    <div class="stage-container">
        <div id="target-div">Esperando mensaje desde /emisor...</div>
    </div>

    <div class="log-box">
        <span>Canal suscrito: <span class="channel-name">style-channel</span></span>
        <span id="events-count">Eventos recibidos: 0</span>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const targetDiv = document.getElementById('target-div');
    const statusDot = document.getElementById('status-dot');
    const statusText = document.getElementById('status-text');
    const countSpan = document.getElementById('events-count');
    let eventCounter = 0;

    // Verificar estado de conexión de Reverb / Pusher
    if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
        const pusher = window.Echo.connector.pusher;

        const updateState = (state) => {
            if (state === 'connected') {
                statusDot.className = 'dot connected';
                statusText.innerText = 'WebSocket Conectado (Reverb 8080)';
            } else if (state === 'connecting') {
                statusDot.className = 'dot';
                statusText.innerText = 'Conectando a Reverb...';
            } else {
                statusDot.className = 'dot disconnected';
                statusText.innerText = 'Desconectado (' + state + ')';
            }
        };

        pusher.connection.bind('state_change', (states) => {
            updateState(states.current);
        });

        updateState(pusher.connection.state);
    }

    // 1. Suscripción al canal público y escucha del evento
    // El punto (.RemoteStyleUpdate) le indica a Echo que no anteponga App\Events
    window.Echo.channel('style-channel')
        .listen('.RemoteStyleUpdate', (e) => {
            eventCounter++;
            countSpan.innerText = `Eventos recibidos: ${eventCounter}`;

            // Actualizar el texto y el CSS inline recibido
            targetDiv.innerText = e.newText ?? '';
            targetDiv.style.cssText = e.newStyle ?? '';

            // Disparar SweetAlert2 toast no bloqueante
            Swal.fire({
                toast: false,
                position: 'center',
                icon: 'success',
                title: e?.newText ? `Nuevo mensaje recibido` : 'Mensaje recibido',
                text: e?.newText ?? '',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
});
</script>
</body>
</html>
