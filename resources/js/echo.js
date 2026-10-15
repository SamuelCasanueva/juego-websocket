import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Detección automática del host para soportar Localhost, LAN (192.168.x.x) y Port Forwarding (VS Code / Codespaces / ngrok)
const currentHost = window.location.hostname;
const isHttps = window.location.protocol === 'https:';

const configuredHost = import.meta.env.VITE_REVERB_HOST;
let wsHost = (configuredHost && configuredHost !== 'localhost' && configuredHost !== '127.0.0.1')
    ? configuredHost
    : (currentHost || 'localhost');

// Si se usa VS Code / GitHub Codespaces Port Forwarding (ej: xxxx-8000.app.github.dev o .devtunnels.ms)
if (currentHost.includes('-8000.')) {
    wsHost = currentHost.replace('-8000.', '-8080.');
}

const defaultPort = import.meta.env.VITE_REVERB_PORT ?? 8080;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: wsHost,
    wsPort: isHttps ? (window.location.port ? window.location.port : 443) : defaultPort,
    wssPort: isHttps ? (window.location.port ? window.location.port : 443) : defaultPort,
    forceTLS: isHttps || (import.meta.env.VITE_REVERB_SCHEME === 'https'),
    enabledTransports: ['ws', 'wss'],
});
