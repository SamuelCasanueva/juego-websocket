import http from 'http';
import httpProxy from 'http-proxy';

const proxy = httpProxy.createProxyServer({
    ws: true,
    xfwd: false, // Evita que http-proxy sobreescriba puertos y rompa redirecciones SSL
});

proxy.on('error', (err, req, res) => {
    if (err.code !== 'ECONNRESET') {
        console.error('[RobCo Gateway Error]:', err.message);
    }
    if (res && res.writeHead && !res.headersSent) {
        res.writeHead(502, { 'Content-Type': 'text/plain; charset=utf-8' });
        res.end('Error de enlace: Asegúrate de que "php artisan serve" (8000) y "php artisan reverb:start" (8080) estén corriendo.');
    }
});

const server = http.createServer((req, res) => {
    // Normalizar cabeceras HTTPS de Cloudflare para que Laravel no arme URLs con puerto :80
    const isHttps = (req.headers['x-forwarded-proto'] === 'https') ||
                    (req.headers['cf-visitor'] && req.headers['cf-visitor'].includes('https'));

    if (isHttps) {
        req.headers['x-forwarded-proto'] = 'https';
        req.headers['x-forwarded-port'] = '443';
    }

    // Si la petición HTTP va hacia /app/... (handshake o endpoints de reverb)
    if (req.url && req.url.startsWith('/app/')) {
        proxy.web(req, res, { target: 'http://127.0.0.1:8080' });
    } else {
        // Todo el resto va al servidor web Laravel (puerto 8000)
        proxy.web(req, res, { target: 'http://127.0.0.1:8000' });
    }
});

// Interceptar conexiones WebSocket (upgrade) hacia Reverb (8080)
server.on('upgrade', (req, socket, head) => {
    proxy.ws(req, socket, head, { target: 'http://127.0.0.1:8080' });
});

const PORT = process.env.GATEWAY_PORT || 8008;
server.listen(PORT, '0.0.0.0', () => {
    console.log(`\n======================================================`);
    console.log(` 🚀 ROBCO GATEWAY UNIFICADO ACTIVO EN PUERTO ${PORT}`);
    console.log(`======================================================`);
    console.log(` > Web (Laravel):       http://127.0.0.1:8000`);
    console.log(` > WebSockets (Reverb): http://127.0.0.1:8080`);
    console.log(`------------------------------------------------------`);
    console.log(` 🌐 TÚNEL ÚNICO (CLOUDFLARE):`);
    console.log(` cloudflared tunnel --url http://localhost:${PORT}`);
    console.log(`======================================================\n`);
});
