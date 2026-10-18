import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Detección automática del host para soportar Localhost, LAN (192.168.x.x) y Tunnels (Cloudflare / ngrok / DevTunnels)
const currentHost = window.location.hostname;
const isHttps = window.location.protocol === 'https:';

const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('ws')) {
    localStorage.setItem('robco_ws_host', urlParams.get('ws'));
}
const overrideHost = urlParams.get('ws') || localStorage.getItem('robco_ws_host');

let rawHost = overrideHost;
if (rawHost) {
    rawHost = rawHost.replace(/^https?:\/\//i, '').replace(/\/.*$/, '').trim();
}

let wsHost = rawHost || currentHost || 'localhost';

// Si se usa VS Code / GitHub Codespaces Port Forwarding (ej: xxxx-8000.app.github.dev o .devtunnels.ms)
if (currentHost.includes('-8000.')) {
    wsHost = currentHost.replace('-8000.', '-8080.');
}

const defaultPort = import.meta.env.VITE_REVERB_PORT ?? 8080;
const isLocal = (wsHost === 'localhost' || wsHost === '127.0.0.1');

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: wsHost,
    wsPort: isHttps ? (window.location.port ? window.location.port : 443) : (isLocal ? defaultPort : (window.location.port || defaultPort)),
    wssPort: isHttps ? (window.location.port ? window.location.port : 443) : defaultPort,
    forceTLS: isHttps || (import.meta.env.VITE_REVERB_SCHEME === 'https'),
    enabledTransports: ['ws', 'wss'],
});

// Helper interactivo opcional (por si se usa un túnel secundario manual)
window.configureRobcoWsHost = function() {
    const current = localStorage.getItem('robco_ws_host') || '';
    const input = prompt(
        "⚡ ENLACE WEBSOCKET ROBCO (REVERB)\n\n" +
        "Con el Gateway RobCo (puerto 8008), la conexión es 100% automática.\n\n" +
        "Si estás usando un túnel secundario manual para el puerto 8080, pega la URL aquí:\n" +
        "(O déjalo vacío para detección automática):",
        current
    );
    if (input !== null) {
        const cleaned = input.replace(/^https?:\/\//i, '').replace(/\/.*$/, '').trim();
        if (cleaned) {
            localStorage.setItem('robco_ws_host', cleaned);
        } else {
            localStorage.removeItem('robco_ws_host');
        }
        window.location.reload();
    }
};

function updateWsBadges(state) {
    document.querySelectorAll('.robco-ws-badge').forEach(badge => {
        if (state === 'connected') {
            badge.style.color = 'var(--term-green, #1bf835)';
            badge.style.borderColor = 'var(--term-green, #1bf835)';
            badge.innerText = `[🟢 ENLACE: CONECTADO]`;
            badge.title = `WebSocket enlazado activamente (${wsHost})`;
        } else if (state === 'connecting') {
            badge.style.color = 'var(--term-amber, #f59e0b)';
            badge.style.borderColor = 'var(--term-amber, #f59e0b)';
            badge.innerText = `[🟡 ENLACE: CONECTANDO...]`;
            badge.title = `Estableciendo enlace con ${wsHost}...`;
        } else {
            badge.style.color = 'var(--term-red, #ff4444)';
            badge.style.borderColor = 'var(--term-red, #ff4444)';
            badge.innerText = `[🔴 ENLACE: RECONECTANDO]`;
            badge.title = `Esperando señal de Reverb en ${wsHost}...`;
        }
    });
}

if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
    const pusher = window.Echo.connector.pusher;
    pusher.connection.bind('state_change', (states) => {
        window.dispatchEvent(new CustomEvent('robco-ws-state', { detail: states.current }));
        updateWsBadges(states.current);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
        updateWsBadges(window.Echo.connector.pusher.connection.state);
    }
});
