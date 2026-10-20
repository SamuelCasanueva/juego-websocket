<!DOCTYPE html>
<html lang="es" data-theme="green">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROBCO INDUSTRIES // ERROR 404: SECTOR NO ENCONTRADO</title>
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        /* ── TEMAS DE COLOR PIP-BOY ─────────────────────────────────── */
        [data-theme="green"] {
            --term-green: #1bf835;
            --term-green-dim: #0e851c;
            --term-green-dark: #05330a;
            --term-bg: #010802;
            --term-glow: 0 0 8px rgba(27, 248, 53, 0.65);
            --term-amber: #f59e0b;
            --term-red: #ef4444;
        }
        [data-theme="amber"] {
            --term-green: #f5a623;
            --term-green-dim: #a8701a;
            --term-green-dark: #3d2600;
            --term-bg: #0d0700;
            --term-glow: 0 0 8px rgba(245, 166, 35, 0.65);
            --term-amber: #fde68a;
            --term-red: #ef4444;
        }
        [data-theme="blue"] {
            --term-green: #7dd3fc;
            --term-green-dim: #2563eb;
            --term-green-dark: #0a1628;
            --term-bg: #010510;
            --term-glow: 0 0 8px rgba(125, 211, 252, 0.65);
            --term-amber: #c084fc;
            --term-red: #f87171;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #000000;
            color: var(--term-green);
            font-family: 'Share Tech Mono', monospace;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            text-shadow: var(--term-glow);
            letter-spacing: 1px;
            overflow-x: hidden;
        }

        /* ── MONITOR CRT Y SCANLINES ───────────────────────────────── */
        .crt-monitor {
            position: relative;
            width: 100%;
            max-width: 860px;
            background: var(--term-bg);
            border: 2px solid var(--term-green-dim);
            border-radius: 8px;
            padding: 28px 32px;
            box-shadow: 0 0 24px rgba(0, 0, 0, 0.9), inset 0 0 16px rgba(0, 0, 0, 0.8), var(--term-glow);
            overflow: hidden;
        }

        .crt-monitor::before {
            content: " ";
            display: block;
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.03), rgba(0, 255, 0, 0.01), rgba(0, 0, 255, 0.03));
            z-index: 20;
            background-size: 100% 3px, 6px 100%;
            pointer-events: none;
        }

        .crt-monitor::after {
            content: " ";
            display: block;
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            background: radial-gradient(circle, rgba(0,0,0,0) 65%, rgba(0,0,0,0.6) 100%);
            pointer-events: none;
            z-index: 21;
        }

        /* ── ENCABEZADO ROBCO ───────────────────────────────────────── */
        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--term-green);
            padding-bottom: 12px;
            margin-bottom: 24px;
            font-size: 0.95rem;
        }

        .header-title {
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-badge {
            background: var(--term-red);
            color: #000;
            font-weight: bold;
            padding: 2px 8px;
            font-size: 0.8rem;
            letter-spacing: 2px;
            animation: pulse-red 1.5s infinite alternate;
        }

        @keyframes pulse-red {
            from { opacity: 0.7; }
            to { opacity: 1; filter: drop-shadow(0 0 6px #ef4444); }
        }

        /* ── SECTOR DE ERROR CENTRAL ────────────────────────────────── */
        .error-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .ascii-art {
            font-size: 0.7rem;
            line-height: 1.15;
            color: var(--term-red);
            text-shadow: 0 0 8px rgba(239, 68, 68, 0.7);
            white-space: pre;
            overflow-x: auto;
            text-align: center;
            margin: 10px 0;
        }

        .alert-box {
            border: 1px dashed var(--term-red);
            background: rgba(239, 68, 68, 0.06);
            padding: 16px 20px;
            border-radius: 4px;
        }

        .alert-title {
            color: var(--term-red);
            font-weight: bold;
            font-size: 1.25rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-shadow: 0 0 8px rgba(239, 68, 68, 0.6);
        }

        .diagnostic-log {
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 14px 0;
            color: var(--term-green);
        }

        .log-line {
            display: flex;
            gap: 12px;
            margin-bottom: 6px;
        }

        .log-line .tag {
            color: var(--term-green-dim);
            min-width: 140px;
        }

        .log-line .val {
            color: var(--term-amber);
            word-break: break-all;
        }

        /* ── BOTONES DE ACCIÓN ─────────────────────────────────────── */
        .actions-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid var(--term-green-dim);
        }

        .robco-btn {
            background: transparent;
            color: var(--term-green);
            border: 1px solid var(--term-green);
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.95rem;
            padding: 10px 18px;
            text-decoration: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s ease-in-out;
            text-shadow: var(--term-glow);
        }

        .robco-btn:hover {
            background: var(--term-green);
            color: #000;
            box-shadow: 0 0 12px var(--term-green);
            text-shadow: none;
        }

        .robco-btn-primary {
            border-width: 2px;
            font-weight: bold;
            background: rgba(27, 248, 53, 0.1);
        }

        .robco-btn-secondary {
            border-color: var(--term-green-dim);
            color: var(--term-green-dim);
        }
        .robco-btn-secondary:hover {
            border-color: var(--term-green);
            color: #000;
        }

        /* ── FOOTER DE LA TERMINAL ─────────────────────────────────── */
        .term-footer {
            margin-top: 28px;
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: var(--term-green-dim);
            border-top: 1px solid var(--term-green-dark);
            padding-top: 10px;
        }

        /* ── CURSOR PARPADEANTE ────────────────────────────────────── */
        .cursor {
            display: inline-block;
            width: 9px;
            height: 1.1em;
            background: var(--term-green);
            vertical-align: text-bottom;
            animation: blink 0.9s infinite;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }

        @media (max-width: 600px) {
            .crt-monitor { padding: 18px 16px; }
            .header-bar { flex-direction: column; align-items: flex-start; gap: 8px; }
            .log-line { flex-direction: column; gap: 2px; }
            .actions-grid { flex-direction: column; }
            .robco-btn { width: 100%; justify-content: center; }
            .ascii-art { font-size: 0.55rem; }
        }
    </style>
</head>
<body>

<div class="crt-monitor">
    <!-- BARRA SUPERIOR ROBCO -->
    <div class="header-bar">
        <div class="header-title">
            <span>ROBCO INDUSTRIES (TM) TERMLINK PROTOCOL</span>
        </div>
        <div class="header-badge">
            ERROR CODE: 0x00000404
        </div>
    </div>

    <!-- CONTENEDOR DE ERROR -->
    <div class="error-container">
        <!-- ASCII ART GLITCH/ERROR -->
        <div class="ascii-art">
   ______ _____  _____   ____  _____     _  _    ___  _  _   
  |  ____|  __ \|  __ \ / __ \|  __ \   | || |  / _ \| || |  
  | |__  | |__) | |__) | |  | | |__) |  | || |_| | | | || |_ 
  |  __| |  _  /|  _  /| |  | |  _  /   |__   _| | | |__   _|
  | |____| | \ \| | \ \| |__| | | \ \      | | | |_| |  | |  
  |______|_|  \_\_|  \_\\____/|_|  \_\     |_|  \___/   |_|  
        </div>

        <div class="alert-box">
            <div class="alert-title">
                <span>⚠ ACCESO DENEGADO // SECTOR DE SUBRED NO ENCONTRADO</span>
            </div>
            <p style="font-size: 0.92rem; color: #ddd; opacity: 0.9;">
                La dirección o registro de memoria solicitado no existe en la topología local de la red Vault-Tec. Es posible que el enlace haya sido purgado, la terminal esté fuera de línea o el operador haya introducido coordenadas inválidas.
            </p>
        </div>

        <!-- DIAGNÓSTICO DEL SISTEMA -->
        <div class="diagnostic-log">
            <div class="log-line">
                <span class="tag">&gt; SECTOR PETICIÓN:</span>
                <span class="val">{{ request()->path() ?: '/' }}</span>
            </div>
            <div class="log-line">
                <span class="tag">&gt; NODO DE ORIGEN:</span>
                <span class="val">{{ request()->ip() }}</span>
            </div>
            <div class="log-line">
                <span class="tag">&gt; PROTOCOLO:</span>
                <span class="val">{{ request()->secure() ? 'ENCRIPTADO (HTTPS/WSS)' : 'ESTÁNDAR (HTTP/WS)' }}</span>
            </div>
            <div class="log-line">
                <span class="tag">&gt; ESTADO DEL NÚCLEO:</span>
                <span class="val" style="color: var(--term-green);">TERMINAL OPERATIVA // ESPERANDO INSTRUCCIÓN DE RETORNO<span class="cursor"></span></span>
            </div>
        </div>

        <!-- BOTONES DE NAVEGACIÓN -->
        <div class="actions-grid">
            <a href="{{ route('terminal.lobby') }}" class="robco-btn robco-btn-primary" id="btn-lobby">
                <span>[ 🏠 RECONECTAR AL LOBBY PRINCIPAL ]</span>
            </a>
            <button onclick="window.history.back()" class="robco-btn robco-btn-secondary" id="btn-back">
                <span>[ ↩ RETROCEDER SECTOR ANTERIOR ]</span>
            </button>
            <button onclick="window.location.reload()" class="robco-btn robco-btn-secondary" id="btn-retry">
                <span>[ 🔄 REINTENTAR ENLACE ]</span>
            </button>
        </div>
    </div>

    <!-- PIE DE PÁGINA -->
    <div class="term-footer">
        <span>VAULT-TEC OS v4.0.4 // MEMORY DUMP: OK</span>
        <span>ROBCO TERMLINK MULTIPLEXOR</span>
    </div>
</div>

<script>
    // Recuperar tema persistente de Pip-Boy (green, amber, blue)
    try {
        const savedTheme = localStorage.getItem('robco_theme') || 'green';
        document.documentElement.setAttribute('data-theme', savedTheme);
    } catch(e) {}

    // Sintetizador de audio retro para la terminal RobCo
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    function playBeep(freq = 800, type = 'square', duration = 0.05, gainValue = 0.04) {
        if (!audioCtx) return;
        try {
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = type;
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            gain.gain.setValueAtTime(gainValue, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + duration);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + duration);
        } catch(e) {}
    }

    // Efecto de sonido al interactuar con los botones
    document.querySelectorAll('.robco-btn').forEach(btn => {
        btn.addEventListener('mouseenter', () => playBeep(950, 'sine', 0.03, 0.02));
        btn.addEventListener('click', () => playBeep(450, 'square', 0.08, 0.05));
    });

    // Beep de alerta inicial (buzzer sutil al cargar)
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => playBeep(260, 'sawtooth', 0.12, 0.04), 150);
    });
</script>

</body>
</html>
