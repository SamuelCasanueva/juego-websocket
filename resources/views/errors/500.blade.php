<!DOCTYPE html>
<html lang="es" data-theme="green">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROBCO INDUSTRIES // ERROR 500: SOBRECALENTAMIENTO CRÍTICO DEL NÚCLEO</title>
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

        /* ── MONITOR CRT CON EFECTO DE ALERTA ROJA ─────────────────── */
        .crt-monitor {
            position: relative;
            width: 100%;
            max-width: 860px;
            background: var(--term-bg);
            border: 2px solid var(--term-red);
            border-radius: 8px;
            padding: 28px 32px;
            box-shadow: 0 0 30px rgba(239, 68, 68, 0.35), inset 0 0 20px rgba(0, 0, 0, 0.8), var(--term-glow);
            overflow: hidden;
            animation: border-flicker 3s infinite alternate;
        }

        @keyframes border-flicker {
            0%, 100% { border-color: var(--term-red); box-shadow: 0 0 25px rgba(239, 68, 68, 0.3); }
            50% { border-color: #b91c1c; box-shadow: 0 0 15px rgba(185, 28, 28, 0.2); }
        }

        .crt-monitor::before {
            content: " ";
            display: block;
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.28) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.04), rgba(0, 255, 0, 0.01), rgba(0, 0, 255, 0.04));
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
            border-bottom: 2px solid var(--term-red);
            padding-bottom: 12px;
            margin-bottom: 24px;
            font-size: 0.95rem;
        }

        .header-title {
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--term-red);
            text-shadow: 0 0 8px rgba(239, 68, 68, 0.7);
        }

        .header-badge {
            background: var(--term-red);
            color: #000;
            font-weight: bold;
            padding: 3px 10px;
            font-size: 0.8rem;
            letter-spacing: 2px;
            animation: pulse-red 1.2s infinite alternate;
        }

        @keyframes pulse-red {
            from { opacity: 0.75; }
            to { opacity: 1; filter: drop-shadow(0 0 8px #ef4444); }
        }

        /* ── SECTOR CENTRAL DE ERROR ────────────────────────────────── */
        .error-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .ascii-art {
            font-size: 0.7rem;
            line-height: 1.15;
            color: var(--term-red);
            text-shadow: 0 0 10px rgba(239, 68, 68, 0.8);
            white-space: pre;
            overflow-x: auto;
            text-align: center;
            margin: 10px 0;
        }

        .alert-box {
            border: 2px dashed var(--term-red);
            background: rgba(239, 68, 68, 0.08);
            padding: 18px 22px;
            border-radius: 4px;
        }

        .alert-title {
            color: var(--term-red);
            font-weight: bold;
            font-size: 1.25rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-shadow: 0 0 10px rgba(239, 68, 68, 0.7);
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
            min-width: 170px;
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
            border-top: 1px solid var(--term-red);
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

        .robco-btn-danger {
            border-color: var(--term-red);
            color: var(--term-red);
            text-shadow: 0 0 8px rgba(239, 68, 68, 0.6);
            background: rgba(239, 68, 68, 0.1);
            font-weight: bold;
        }

        .robco-btn-danger:hover {
            background: var(--term-red);
            color: #000;
            box-shadow: 0 0 14px #ef4444;
            text-shadow: none;
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
            color: var(--term-red);
            border-top: 1px solid rgba(239, 68, 68, 0.3);
            padding-top: 10px;
            opacity: 0.85;
        }

        /* ── CURSOR PARPADEANTE ────────────────────────────────────── */
        .cursor {
            display: inline-block;
            width: 9px;
            height: 1.1em;
            background: var(--term-red);
            vertical-align: text-bottom;
            animation: blink 0.8s infinite;
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
            .ascii-art { font-size: 0.52rem; }
        }
    </style>
</head>
<body>

<div class="crt-monitor">
    <!-- BARRA SUPERIOR ROBCO -->
    <div class="header-bar">
        <div class="header-title">
            <span>☢ ROBCO INDUSTRIES (TM) // TERMLINK CORE DUMP</span>
        </div>
        <div class="header-badge">
            PANIC CODE: 0x00000500
        </div>
    </div>

    <!-- CONTENEDOR DE ERROR -->
    <div class="error-container">
        <!-- ASCII ART RADIACIÓN / FALLO CRÍTICO -->
        <div class="ascii-art">
   ______ _____  _____   ____  _____     _____  ___   ___  
  |  ____|  __ \|  __ \ / __ \|  __ \   | ____|/ _ \ / _ \ 
  | |__  | |__) | |__) | |  | | |__) |  | |__ | | | | | | |
  |  __| |  _  /|  _  /| |  | |  _  /   |___ \| | | | | | |
  | |____| | \ \| | \ \| |__| | | \ \    ___) | |_| | |_| |
  |______|_|  \_\_|  \_\\____/|_|  \_\  |____/ \___/ \___/ 
             [ ☢ ALERTA DE COLAPSO EN LA UNIDAD CENTRAL ☢ ]
        </div>

        <div class="alert-box">
            <div class="alert-title">
                <span>⚠ SOBRECALENTAMIENTO DE TUBOS // EXCEPCIÓN INTERNA DEL NÚCLEO</span>
            </div>
            <p style="font-size: 0.92rem; color: #eee; opacity: 0.92; line-height: 1.5;">
                La computadora central de Vault-Tec ha detectado una anomalía catastrófica en el ciclo de ejecución de instrucciones. Los condensadores analógicos o la base de datos local han rechazado el flujo de procesamiento.
            </p>
        </div>

        <!-- DIAGNÓSTICO DEL SISTEMA -->
        <div class="diagnostic-log">
            <div class="log-line">
                <span class="tag">&gt; SECTOR DE COLAPSO:</span>
                <span class="val">{{ request()->path() ?: '/' }}</span>
            </div>
            <div class="log-line">
                <span class="tag">&gt; TERMINAL ORIGEN:</span>
                <span class="val">{{ request()->ip() }}</span>
            </div>
            <div class="log-line">
                <span class="tag">&gt; REGISTRO DE EVENTO:</span>
                <span class="val">{{ now()->format('Y-m-d H:i:s') }} UTC</span>
            </div>
            <div class="log-line">
                <span class="tag">&gt; DIAGNÓSTICO:</span>
                <span class="val" style="color: var(--term-red);">SOBRECARGA DE RELÉS // REINICIO MANUAL RECOMENDADO<span class="cursor"></span></span>
            </div>
        </div>

        <!-- BOTONES DE ACCIÓN -->
        <div class="actions-grid">
            <button onclick="window.location.reload()" class="robco-btn robco-btn-danger" id="btn-reload">
                <span>[ 🔄 REINICIAR PROTOCOLO (REINTENTAR) ]</span>
            </button>
            <a href="{{ route('terminal.lobby') }}" class="robco-btn robco-btn-primary" id="btn-lobby">
                <span>[ 🏠 EVACUAR AL LOBBY PRINCIPAL ]</span>
            </a>
            <button onclick="window.history.back()" class="robco-btn robco-btn-secondary" id="btn-back">
                <span>[ ↩ RETROCEDER SECTOR ANTERIOR ]</span>
            </button>
        </div>
    </div>

    <!-- PIE DE PÁGINA -->
    <div class="term-footer">
        <span>ESTADO CRÍTICO // INTERRUPCIONES DE HARDWARE ACTIVAS</span>
        <span>VAULT-TEC DEFENSE SYSTEMS</span>
    </div>
</div>

<script>
    // Recuperar tema persistente de Pip-Boy (green, amber, blue)
    try {
        const savedTheme = localStorage.getItem('robco_theme') || 'green';
        document.documentElement.setAttribute('data-theme', savedTheme);
    } catch(e) {}

    // Sintetizador de audio retro para la alarma crítica
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    function playBeep(freq = 600, type = 'sawtooth', duration = 0.08, gainValue = 0.05) {
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
        btn.addEventListener('mouseenter', () => playBeep(880, 'sine', 0.03, 0.02));
        btn.addEventListener('click', () => playBeep(220, 'square', 0.1, 0.06));
    });

    // Alarma de advertencia dual de colapso al cargar
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            playBeep(200, 'sawtooth', 0.15, 0.06);
            setTimeout(() => playBeep(150, 'sawtooth', 0.2, 0.06), 180);
        }, 150);
    });
</script>

</body>
</html>
