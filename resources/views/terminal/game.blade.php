<!DOCTYPE html>
<html lang="es" data-theme="green">
<head>
    <meta charset="UTF-8">
    <script>
        (function() {
            var params = new URLSearchParams(window.location.search);
            var theme = params.get('theme') || localStorage.getItem('robco_theme') || 'green';
            var crt = localStorage.getItem('robco_crt') || 'retro';
            var contrast = localStorage.getItem('robco_contrast') || 'classic';
            var fontSize = localStorage.getItem('robco_font_size') || 'normal';

            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.setAttribute('data-crt', crt);
            document.documentElement.setAttribute('data-contrast', contrast);
            document.documentElement.setAttribute('data-fontsize', fontSize);
        })();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROBCO TERMLINK // BÓVEDA {{ $room->code }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            --term-glow: 0 0 6px rgba(27, 248, 53, 0.65);
            --term-amber: #f59e0b;
            --term-red: #ef4444;
        }
        [data-theme="amber"] {
            --term-green: #f5a623;
            --term-green-dim: #a8701a;
            --term-green-dark: #3d2600;
            --term-bg: #0d0700;
            --term-glow: 0 0 6px rgba(245, 166, 35, 0.65);
            --term-amber: #fde68a;
            --term-red: #ef4444;
        }
        [data-theme="blue"] {
            --term-green: #7dd3fc;
            --term-green-dim: #2563eb;
            --term-green-dark: #0a1628;
            --term-bg: #010510;
            --term-glow: 0 0 6px rgba(125, 211, 252, 0.65);
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
            padding: 16px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            text-shadow: var(--term-glow);
            letter-spacing: 1px;
            user-select: none;
        }

        @media (min-height: 800px) and (min-width: 900px) {
            body {
                align-items: center;
            }
        }

        /* Contenedor CRT RobCo */
        .term-screen {
            width: 100%;
            max-width: 1080px;
            background: var(--term-bg);
            border: 2px solid var(--term-green-dim);
            padding: 24px;
            position: relative;
            box-shadow: 0 0 35px rgba(14, 133, 28, 0.35), inset 0 0 50px rgba(0, 0, 0, 0.95);
        }

        /* Scanlines auténticos */
        .term-screen::before {
            content: " ";
            display: block;
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.45) 50%);
            background-size: 100% 4px;
            z-index: 10;
            pointer-events: none;
        }

        /* Viñeta sutil de tubo catódico */
        .term-screen::after {
            content: " ";
            display: block;
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            background: radial-gradient(circle at center, transparent 65%, rgba(0, 0, 0, 0.75) 100%);
            pointer-events: none;
            z-index: 11;
        }

        .term-header {
            border-bottom: 1px solid var(--term-green);
            padding-bottom: 12px;
            margin-bottom: 14px;
        }

        .term-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .term-title {
            font-size: 1.3rem;
            font-weight: bold;
        }

        .term-header-actions {
            display: flex;
            gap: 12px;
        }

        .term-action-btn {
            background: transparent;
            border: 1px solid var(--term-green);
            color: var(--term-green);
            padding: 4px 10px;
            font-family: inherit;
            font-size: 0.8rem;
            cursor: pointer;
            text-decoration: none;
        }

        .term-action-btn:hover {
            background: var(--term-green);
            color: #000000;
            text-shadow: none;
        }

        .term-status-line {
            font-size: 0.85rem;
            opacity: 0.85;
            margin-top: 4px;
        }

        /* Encabezado clásico de Fallout de intentos */
        .fallout-attempts-header {
            margin-bottom: 14px;
            line-height: 1.5;
            font-size: 1rem;
        }

        .attempts-blocks {
            display: inline-flex;
            gap: 6px;
            vertical-align: middle;
            margin-left: 6px;
        }

        .att-block {
            display: inline-block;
            width: 12px;
            height: 16px;
            background-color: var(--term-green);
            box-shadow: 0 0 6px var(--term-green);
        }

        .att-block.spent {
            background-color: transparent;
            border: 1px dashed var(--term-green-dim);
            box-shadow: none;
        }

        /* Disposición principal: Memoria a la izquierda, Diagnóstico a la derecha */
        .term-body {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 20px;
        }

        @media (max-width: 900px) {
            .term-body {
                grid-template-columns: 1fr;
            }
        }

        /* Volcado de Memoria Hexadecimal (2 columnas) */
        .memory-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            background: rgba(0, 0, 0, 0.7);
            border: 1px solid var(--term-green-dim);
            padding: 12px;
            font-size: 1.05rem;
            line-height: 1.35;
        }

        @media (max-width: 768px) {
            .memory-columns {
                grid-template-columns: 1fr;
                font-size: 0.95rem;
                gap: 10px;
                padding: 10px 8px;
            }
        }

        .memory-row {
            display: flex;
            align-items: center;
        }

        .mem-addr {
            margin-right: 10px;
            opacity: 0.8;
            user-select: none;
        }

        .mem-chars {
            display: flex;
            letter-spacing: 2px;
        }

        .cell {
            cursor: pointer;
            padding: 0 1px;
        }

        /* Resaltado de cursor CRT fiel a Fallout */
        .cell.highlighted {
            background-color: var(--term-green);
            color: #000000;
            text-shadow: none;
        }

        .cell.dud-removed {
            opacity: 0.3;
            cursor: not-allowed;
            text-decoration: line-through;
        }

        /* Línea de cursor de entrada en la parte inferior */
        .term-cursor-bar {
            border-top: 1px solid var(--term-green-dim);
            padding-top: 8px;
            margin-top: 10px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cursor-solid {
            display: inline-block;
            width: 10px;
            height: 16px;
            background: var(--term-green);
            animation: blink 0.9s step-end infinite;
        }

        @keyframes blink {
            50% { opacity: 0; }
        }

        /* Panel Lateral: Diagnóstico de Intrusión y Log */
        .term-sidebar {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .term-subbox {
            border: 1px solid var(--term-green-dim);
            background: rgba(5, 51, 10, 0.15);
            padding: 12px;
        }

        .term-subbox-title {
            font-size: 0.9rem;
            font-weight: bold;
            border-bottom: 1px dashed var(--term-green-dim);
            padding-bottom: 4px;
            margin-bottom: 10px;
        }

        .rival-hud-info {
            font-size: 0.85rem;
            line-height: 1.6;
        }

        .rival-att-blocks {
            display: inline-flex;
            gap: 6px;
            vertical-align: middle;
            margin-left: 6px;
        }

        .riv-block {
            display: inline-block;
            width: 12px;
            height: 14px;
            background-color: var(--term-amber);
            box-shadow: 0 0 6px var(--term-amber);
        }

        .riv-block.spent {
            background-color: transparent;
            border: 1px dashed var(--term-amber);
            box-shadow: none;
        }

        /* Feed del registro de comandos RobCo */
        .term-log-feed {
            height: 240px;
            overflow-y: auto;
            font-size: 0.85rem;
            line-height: 1.5;
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding-right: 4px;
        }

        .term-log-feed::-webkit-scrollbar {
            width: 4px;
        }

        .term-log-feed::-webkit-scrollbar-thumb {
            background: var(--term-green-dim);
        }

        .log-line {
            word-break: break-all;
        }

        .log-line-local {
            color: var(--term-green);
        }

        .log-line-remote {
            color: var(--term-amber);
        }

        .log-line-sys {
            opacity: 0.85;
        }

        /* Pantallas de Fin de Misión (RobCo Takeover) */
        .term-screen-takeover {
            display: none;
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 50;
            background: #000000;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 30px;
            border: 2px solid var(--term-green);
        }

        .term-screen-takeover.active {
            display: flex;
        }

        .term-screen-takeover.loss {
            border-color: var(--term-red);
            color: var(--term-red);
            text-shadow: 0 0 8px rgba(239, 68, 68, 0.7);
        }

        .takeover-title {
            font-size: 2.2rem;
            font-weight: bold;
            letter-spacing: 3px;
            margin-bottom: 16px;
        }

        .takeover-body {
            font-size: 1.15rem;
            line-height: 1.6;
            margin-bottom: 24px;
            max-width: 650px;
        }

        /* ── BOOT OVERLAY ─────────────────────────────────────────── */
        #boot-overlay {
            position: fixed;
            inset: 0;
            background: #000;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
            padding: 32px 40px;
            font-family: 'Share Tech Mono', monospace;
            color: var(--term-green);
            text-shadow: var(--term-glow);
            overflow: hidden;
            transition: opacity 0.6s ease;
        }
        #boot-overlay.fade-out {
            opacity: 0;
            pointer-events: none;
        }
        #boot-text {
            font-size: 0.9rem;
            line-height: 1.7;
            white-space: pre-wrap;
            word-break: break-all;
        }
        #boot-cursor {
            display: inline-block;
            width: 9px;
            height: 15px;
            background: var(--term-green);
            vertical-align: middle;
            animation: blink 0.9s step-end infinite;
        }

        /* ── THEME SWITCHER ──────────────────────────────────────────── */
        .theme-switcher {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .theme-btn {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.3);
            cursor: pointer;
            transition: transform 0.15s, border-color 0.15s;
        }
        .theme-btn:hover { transform: scale(1.25); }
        .theme-btn.active { border-color: #ffffff; transform: scale(1.2); }
        .theme-btn[data-t="green"] { background: #1bf835; box-shadow: 0 0 5px #1bf835; }
        .theme-btn[data-t="amber"] { background: #f5a623; box-shadow: 0 0 5px #f5a623; }
        .theme-btn[data-t="blue"]  { background: #7dd3fc; box-shadow: 0 0 5px #7dd3fc; }

        /* ── MODO BADGE ────────────────────────────────────────── */
        .mode-badge {
            display: inline-block;
            border: 1px solid currentColor;
            padding: 1px 7px;
            font-size: 0.7rem;
            letter-spacing: 2px;
            margin-left: 10px;
            vertical-align: middle;
        }
        .mode-badge.coop  { color: #34d399; border-color: #34d399; }
        .mode-badge.timed { color: var(--term-amber); border-color: var(--term-amber); }
        .mode-badge.comp  { color: var(--term-green-dim); border-color: var(--term-green-dim); }

        /* ── TEMPORIZADOR ────────────────────────────────────── */
        #timerWrap {
            margin-bottom: 10px;
            display: none;
        }
        .timer-label {
            font-size: 0.8rem;
            opacity: 0.85;
            margin-bottom: 3px;
            display: flex;
            justify-content: space-between;
        }
        #timerBar {
            height: 6px;
            background: var(--term-green-dark);
            border: 1px solid var(--term-green-dim);
        }
        #timerFill {
            height: 100%;
            background: var(--term-green);
            box-shadow: 0 0 6px var(--term-green);
            width: 100%;
            transition: width 1s linear, background 0.3s;
        }

        /* ── POOL COOPERATIVO ──────────────────────────────── */
        #coopPool {
            margin-bottom: 12px;
            border: 1px solid #34d399;
            padding: 8px 12px;
            font-size: 0.85rem;
            color: #34d399;
            display: none;
        }

        /* ── CHAT DE SALA DE JUEGO ────────────────────────── */
        .term-room-chat-box {
            display: flex;
            flex-direction: column;
            margin-top: 10px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--term-green-dark);
            padding: 8px;
        }
        .term-room-chat-feed {
            height: 120px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding-right: 4px;
            scrollbar-width: thin;
            scrollbar-color: var(--term-green-dim) rgba(0, 0, 0, 0.3);
        }
        .term-room-chat-feed::-webkit-scrollbar {
            width: 5px;
        }
        .term-room-chat-feed::-webkit-scrollbar-thumb {
            background: var(--term-green-dim);
        }
        .room-chat-msg {
            font-size: 0.78rem;
            line-height: 1.3;
            word-break: break-word;
            padding: 2px 4px;
            border-left: 2px solid transparent;
        }
        .room-chat-msg-own {
            border-left-color: var(--term-green);
            background: rgba(27, 248, 53, 0.04);
        }
        .room-chat-msg-rival {
            border-left-color: var(--term-amber);
            background: rgba(245, 166, 35, 0.03);
        }
        .room-chat-time {
            font-size: 0.68rem;
            opacity: 0.6;
            margin-right: 4px;
        }
        .room-chat-author {
            color: var(--term-amber);
            font-weight: bold;
            margin-right: 4px;
        }
        .room-chat-msg-own .room-chat-author {
            color: var(--term-green);
        }
        .room-chat-text {
            color: var(--term-green);
        }
        .term-room-chat-form {
            display: flex;
            gap: 6px;
            margin-top: 6px;
            padding-top: 6px;
            border-top: 1px dashed var(--term-green-dark);
        }
        .term-room-chat-input {
            flex: 1;
            font-size: 0.78rem;
            padding: 4px 6px;
        }

        /* ── RESPONSIVE ADAPTATIONS (MOBILE & TABLET) ── */
        @media (max-width: 768px) {
            body {
                padding: 8px;
            }
            .term-screen {
                padding: 14px 10px;
                border-width: 1.5px;
            }
            .term-header-top {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            .term-title {
                font-size: 1.05rem;
            }
            .term-header-actions {
                flex-wrap: wrap;
                gap: 6px;
                width: 100%;
            }
            .term-action-btn {
                font-size: 0.74rem;
                padding: 4px 8px;
            }
            .term-status-line {
                font-size: 0.78rem;
                line-height: 1.4;
                word-break: break-word;
            }
            .mode-badge {
                margin-left: 4px;
            }
            .fallout-attempts-header {
                font-size: 0.9rem;
            }
            .att-block {
                width: 10px;
                height: 14px;
            }
            .term-body {
                gap: 14px;
            }
            .term-sidebar {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .term-log-feed {
                max-height: 140px;
            }
            .term-room-chat-feed {
                height: 120px;
            }
            .term-screen-takeover {
                padding: 20px 14px;
            }
            .takeover-title {
                font-size: 1.3rem;
                letter-spacing: 1px;
                word-break: break-all;
                line-height: 1.3;
            }
            .takeover-body {
                font-size: 0.95rem;
                line-height: 1.45;
            }
            #boot-overlay {
                padding: 20px 16px;
            }
            #boot-text {
                font-size: 0.78rem;
                line-height: 1.5;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 4px;
            }
            .term-screen {
                padding: 10px 6px;
            }
            .memory-columns {
                font-size: 0.82rem;
                padding: 8px 4px;
                overflow-x: auto;
            }
            .mem-addr {
                margin-right: 5px;
                font-size: 0.75rem;
            }
            .mem-chars {
                letter-spacing: 1px;
            }
            .cell {
                padding: 0;
            }
            .term-cursor-bar {
                font-size: 0.92rem;
            }
            .term-header-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 4px;
            }
            .term-header-actions .theme-switcher {
                grid-column: span 2;
                margin-bottom: 4px;
            }
            .term-header-actions .term-action-btn {
                text-align: center;
                padding: 5px 2px;
                font-size: 0.7rem;
            }
            .timer-label {
                font-size: 0.75rem;
            }
            #coopPool {
                font-size: 0.75rem;
                padding: 6px 8px;
            }
        }

        @media (max-width: 380px) {
            .memory-columns {
                font-size: 0.76rem;
            }
            .mem-chars {
                letter-spacing: 0.5px;
            }
        }

        /* ── CRT INTERFERENCE GLITCH ANIMATION ───────────────────────── */
        @keyframes crtGlitch {
            0%   { transform: translate(0, 0); filter: hue-rotate(0deg) contrast(100%); }
            10%  { transform: translate(-5px, 2px) skewX(2deg); filter: hue-rotate(90deg) contrast(180%); }
            20%  { transform: translate(4px, -3px) skewX(-2deg); filter: hue-rotate(180deg) invert(20%); }
            30%  { transform: translate(-4px, -2px); filter: hue-rotate(270deg) contrast(200%); }
            45%  { transform: translate(3px, 3px) skewX(1deg); filter: hue-rotate(45deg); }
            60%  { transform: translate(-3px, 4px); filter: hue-rotate(135deg) contrast(160%); }
            75%  { transform: translate(5px, -2px) skewX(-1.5deg); filter: hue-rotate(220deg); }
            90%  { transform: translate(-2px, 1px); filter: hue-rotate(310deg); }
            100% { transform: translate(0, 0); filter: hue-rotate(0deg) contrast(100%); }
        }

        .term-screen.crt-interference-glitch {
            animation: crtGlitch 0.18s infinite !important;
            border-color: var(--term-red) !important;
            box-shadow: 0 0 50px rgba(239, 68, 68, 0.85), inset 0 0 60px rgba(239, 68, 68, 0.45) !important;
        }

        .term-screen.crt-interference-glitch::before {
            background: linear-gradient(rgba(255, 0, 0, 0.35) 50%, rgba(0, 0, 0, 0.8) 50%) !important;
            background-size: 100% 6px !important;
        }

        .term-screen.crt-interference-glitch .memory-columns {
            letter-spacing: 3px !important;
            filter: blur(0.7px) !important;
        }

        #interferenceAlertBanner {
            display: none;
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(239, 68, 68, 0.95);
            color: #000;
            font-weight: bold;
            padding: 7px 18px;
            border: 2px solid #fff;
            z-index: 100;
            box-shadow: 0 0 25px rgba(239, 68, 68, 0.9);
            font-size: 0.9rem;
            text-align: center;
            letter-spacing: 1px;
            animation: blink 0.5s step-end infinite;
        }
    </style>
</head>
<body>

<!-- Boot Overlay RobCo BIOS -->
<div id="boot-overlay">
    <div id="boot-text"></div><span id="boot-cursor"></span>
</div>

@php
    $roomSettings = $room->settings ?? [];
    $gameMode     = $roomSettings['mode']          ?? 'competitive';
    $timeLimit    = $roomSettings['time_limit']    ?? null;
    $showLiken    = $roomSettings['show_likeness'] ?? true;
    $maxAtt       = $roomSettings['attempts']      ?? 4;
    $wordPack     = $roomSettings['word_pack']     ?? 'yermo';
    $isAiMode     = !empty($roomSettings['ai_mode']);
    $aiDiff       = $roomSettings['ai_difficulty'] ?? 'normal';

    // Resolver perk activo para este jugador (soporta controller, settings, y fallback a 'science')
    $perk = $activePerk ?? (
        ($playerNumber === 2 && !empty($roomSettings['player2_perk']))
            ? $roomSettings['player2_perk']
            : ($roomSettings['player1_perk'] ?? $roomSettings['perk'] ?? 'science')
    );

    $modeLabel  = match($gameMode) { 'cooperative' => 'COOPERATIVO', 'timed' => 'CONTRARRELOJ', default => 'COMPETITIVO' };
    $packLabel  = match($wordPack) { 'robco' => '🤖 ROBCO', 'vault' => '⚗️ VAULT-TEC', 'vegas' => '🎰 NUEVA VEGAS', default => '🏚 YERMO' };
    $perkLabel  = match($perk) {
        'science'    => '🧠 CIENCIA',
        'luck'       => '🍀 SUERTE',
        'perception' => '👁️ PERCEPCIÓN',
        'injection'  => '⚡ INYECCIÓN DE CÓDIGO',
        default      => '🧠 CIENCIA'
    };

    $secretUpper = !empty($room->secret_word) ? strtoupper($room->secret_word) : '';
    $firstChar   = $secretUpper !== '' ? substr($secretUpper, 0, 1) : '';
    $wordLen     = $secretUpper !== '' ? strlen($secretUpper) : ($room->word_length ?? 6);
    $scienceHint = "CLAVE CONTIENE {$wordLen} CARACTERES Y COMIENZA CON '{$firstChar}'";

    $perkDetail = match($perk) {
        'science'    => "PISTA: COMIENZA CON '{$firstChar}' ({$wordLen} LETRAS)",
        'luck'       => "PROTECCIÓN CRÍTICA DE INTENTOS (30% ANTE FALLOS)",
        'perception' => "DESCRIPCIÓN DE BONIFICACIONES DE CORCHETES VISIBLE",
        'injection'  => "INYECCIÓN MANUAL DE VIRUS DISPONIBLE (PURGA 2 DUDAS)",
        default      => ""
    };
@endphp

<div class="term-screen">
    <div id="interferenceAlertBanner">⚡ ¡ALERTA: INTERFERENCIA ELECTROMAGNÉTICA DETECTADA! SEÑAL INESTABLE ⚡</div>

    <!-- Header RobCo -->
    <div class="term-header">
        <div class="term-header-top">
            <span class="term-title">ROBCO INDUSTRIES (TM) TERMLINK PROTOCOL</span>
            <div class="term-header-actions">
                <button type="button" class="term-action-btn" onclick="window.toggleRobcoSettings(true)" title="Ajustes de visualización (Filtro CRT, Contraste de palabras, Tamaño de fuente, Tema Pip-Boy)">
                    ⚙ [AJUSTES]
                </button>
                <a href="{{ route('terminal.spectate', ['code' => $room->code]) }}" target="_blank" class="term-action-btn"
                   onclick="this.href=this.href.split('?')[0] + '?theme=' + (localStorage.getItem('robco_theme') || 'green')">
                    > [MODO ESPECTADOR]
                </a>
                <button class="term-action-btn" onclick="copyInviteLink()">
                    > [COPIAR FRECUENCIA DE ENLACE]
                </button>
                <button type="button" class="term-action-btn" onclick="handleForfeitOrExit()" style="border-color: var(--term-red); color: var(--term-red);" title="Abandonar la sesión de hackeo o salir al menú principal">
                    > [ABANDONAR // SALIR]
                </button>
            </div>
        </div>
        <div class="term-status-line">
            NODO DE BÓVEDA: [{{ $room->code }}] // OPERADOR: <span id="operatorActiveTitle" style="color:var(--term-amber); margin-right:4px;"></span>{{ strtoupper($playerName) }} [JUGADOR {{ $playerNumber }}] // <span class="robco-ws-badge" onclick="window.configureRobcoWsHost()" style="cursor:pointer;" title="Clic para configurar URL de WebSocket / Reverb">[ENLACE: DETECTANDO...]</span>
            <span class="mode-badge {{ $gameMode === 'cooperative' ? 'coop' : ($gameMode === 'timed' ? 'timed' : 'comp') }}">{{ $modeLabel }}</span>
            @if($isAiMode)
                <span class="mode-badge" style="border-color:var(--term-red); color:var(--term-red);">🤖 VS ZAX ({{ strtoupper($aiDiff) }})</span>
            @endif
            @if($perkLabel)
                <span class="mode-badge" style="border-color:var(--term-amber); color:var(--term-amber);">PERK: {{ $perkLabel }}</span>
            @endif
            <span style="font-size:0.7rem; opacity:0.65; margin-left:6px;">{{ $packLabel }}</span>
        </div>
    </div>

    <!-- Banner de Perk Activo -->
    <div style="margin: -4px 0 12px; padding: 6px 12px; background: rgba(0, 0, 0, 0.45); border: 1px solid var(--term-green-dim); display: flex; align-items: center; justify-content: space-between; font-size: 0.8rem; flex-wrap: wrap; gap: 8px;">
        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <span style="opacity: 0.8;">[MÓDULO S.P.E.C.I.A.L. ACTIVO]:</span>
            <strong style="color: var(--term-amber); letter-spacing: 1px;">{{ $perkLabel }}</strong>
            <span style="opacity: 0.85;">— {{ $perkDetail }}</span>
        </div>
        @if($perk === 'injection')
            <button type="button" class="term-action-btn" id="btnCodeInjectionBanner" onclick="executeCodeInjection()" style="font-size: 0.75rem; border-color: var(--term-amber); color: var(--term-amber); padding: 3px 8px;">
                ⚡ [PURGAR 2 DUDAS (1x)]
            </button>
        @endif
    </div>

    @php
        $myAttempts = ($gameMode === 'cooperative')
            ? $room->player1_attempts
            : ($playerNumber === 1 ? $room->player1_attempts : $room->player2_attempts);
    @endphp

    <!-- Indicador de Intentos Clásico de Fallout -->
    <div class="fallout-attempts-header">
        INTRODUZCA LA CONTRASEÑA<br>
        <span id="attemptsText">{{ $myAttempts }}</span> INTENTO(S) RESTANTE(S):
        <div class="attempts-blocks" id="player-attempts-blocks">
            @for($i = 0; $i < $maxAtt; $i++)
                <span class="att-block {{ $i >= $myAttempts ? 'spent' : '' }}" id="my-att-{{ $i }}"></span>
            @endfor
        </div>
    </div>

    {{-- Barra de temporizador (modo contrarreloj) --}}
    <div id="timerWrap">
        <div class="timer-label">
            <span>⏱ TIEMPO RESTANTE: <strong id="timerDisplay">{{ $timeLimit ? sprintf('%02d:%02d', intdiv($timeLimit,60), $timeLimit%60) : '--:--' }}</strong></span>
            <span id="timerPanic" style="color:var(--term-amber); display:none;">⚠ BLOQUEO INMINENTE</span>
        </div>
        <div id="timerBar"><div id="timerFill"></div></div>
    </div>

    {{-- Pool compartido (modo cooperativo) --}}
    <div id="coopPool">
        > [MODO COOPERATIVO] POOL COMPARTIDO: <span id="coopCount">{{ $room->player1_attempts }}</span>/{{ $maxAtt }}
        &nbsp;│&nbsp; AMBOS OPERADORES COMPARTEN ESTE POOL.
    </div>

    <!-- Cuerpo Principal de Terminal -->
    <div class="term-body">
        <!-- Volcado Hexadecimal -->
        <div>
            <div class="memory-columns" id="memoryGrid">
                <!-- Columna Izquierda -->
                <div id="leftColumnContainer">
                    @foreach($room->grid_data['left_column'] as $line)
                        <div class="memory-row">
                            <span class="mem-addr">{{ $line['address'] }}</span>
                            <div class="mem-chars">
                                @foreach($line['cells'] as $cell)
                                    @include('terminal.partials.cell', ['cell' => $cell])
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Columna Derecha -->
                <div id="rightColumnContainer">
                    @foreach($room->grid_data['right_column'] as $line)
                        <div class="memory-row">
                            <span class="mem-addr">{{ $line['address'] }}</span>
                            <div class="mem-chars">
                                @foreach($line['cells'] as $cell)
                                    @include('terminal.partials.cell', ['cell' => $cell])
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Cursor de comando RobCo -->
            <div class="term-cursor-bar">
                <span>></span>
                <span id="previewSelection">SELECCIONA PALABRA</span>
                <span class="cursor-solid"></span>
            </div>
        </div>

        <!-- Panel Lateral: Diagnóstico de Intrusión y Registro de Comandos -->
        <div class="term-sidebar">
            <!-- Diagnóstico de Intrusión Remota -->
            <div class="term-subbox">
                <div class="term-subbox-title">> SUBRUTINA DE SEGURIDAD // DIAGNÓSTICO</div>
                <div class="rival-hud-info">
                    <div>
                        NODO REMOTO: 
                        <strong id="rival-name">
                            {{ strtoupper($playerNumber === 1 ? ($room->player2_name ?? 'EN ESPERA DE INTRUSO...') : $room->player1_name) }}
                        </strong>
                    </div>
                    <div>
                        ESTADO DE ENLACE: 
                        <span id="rival-state-tag">
                            [{{ $room->status === 'playing' ? 'CONECTADO' : 'ESPERANDO SEÑAL' }}]
                        </span>
                    </div>
                    <div style="margin-top: 4px;">
                        INTENTOS RIVAL:
                        <div class="rival-att-blocks" id="rival-attempts-blocks">
                            @php $rivAttempts = ($playerNumber === 1 ? $room->player2_attempts : $room->player1_attempts); @endphp
                            @for($i = 0; $i < $maxAtt; $i++)
                                <span class="riv-block {{ $i >= $rivAttempts ? 'spent' : '' }}" id="riv-att-{{ $i }}"></span>
                            @endfor
                        </div>
                    </div>
                    <div id="rival-last-action" style="margin-top: 6px; font-size: 0.8rem; opacity: 0.85;">
                        > ESPERANDO COMANDO REMOTO
                    </div>
                </div>
            </div>

            @if($perk === 'injection')
            <!-- Módulo Perk: Inyección de Código -->
            <div class="term-subbox" style="margin-bottom: 10px;">
                <div class="term-subbox-title">> S.P.E.C.I.A.L. // CONTRAMEDIDA ACTIVA</div>
                <button type="button" class="term-action-btn" id="btnCodeInjection" onclick="executeCodeInjection()" style="width: 100%; border-color: var(--term-amber); color: var(--term-amber); padding: 7px; font-weight: bold; font-size: 0.82rem; text-align: center;">
                    ⚡ [EJECUTAR INYECCIÓN DE CÓDIGO (1x)]
                </button>
            </div>
            @endif

            <!-- Registro del Sistema RobCo -->
            <div class="term-subbox" style="flex: 1; display: flex; flex-direction: column;">
                <div class="term-subbox-title">> SALIDA DE COMANDOS TERMLINK</div>
                <div class="term-log-feed" id="systemLog">
                    <div class="log-line log-line-sys">> TERMLINK V4.0.12 INICIALIZADO.</div>
                    <div class="log-line log-line-sys">> LONGITUD DE CLAVE: {{ $room->word_length }} CARACTERES.</div>
                    <div class="log-line log-line-sys">> INTRODUZCA CONTRASEÑA O EJECUTE CORCHETES.</div>
                </div>
            </div>

            <!-- Canal de Comunicación Inter-Bóveda (Chat en Sala) -->
            <div class="term-room-chat-box">
                <div class="term-subbox-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <span>> COMUNICACIÓN DIRECTA</span>
                    <span id="roomChatBadge" style="font-size: 0.7rem; opacity: 0.75;">[INTER-BÓVEDA]</span>
                </div>
                <div class="term-room-chat-feed" id="roomChatFeed">
                    @forelse($roomChat as $m)
                        <div class="room-chat-msg {{ $m->player_token === $token ? 'room-chat-msg-own' : 'room-chat-msg-rival' }}" data-chat-id="{{ $m->id }}">
                            <span class="room-chat-time">[{{ $m->created_at->format('H:i') }}]</span>
                            <span class="room-chat-author">&lt;{{ strtoupper($m->player_name) }}&gt;:</span>
                            <span class="room-chat-text">{{ e($m->message) }}</span>
                        </div>
                    @empty
                        <div id="noRoomChatMsg" style="font-size: 0.75rem; opacity: 0.7; padding: 6px; text-align: center;">
                            > CANAL EN SILENCIO. TRANSMITE TU PRIMER MENSAJE.
                        </div>
                    @endforelse
                </div>
                <form id="roomChatForm" onsubmit="handleSendRoomChat(event)" class="term-room-chat-form">
                    @csrf
                    <input type="text" id="roomChatInput" class="term-input term-room-chat-input"
                           placeholder="TX MENSAJE AL RIVAL/ALIADO..." maxlength="240" autocomplete="off" spellcheck="false">
                    <button type="submit" class="term-btn term-btn-compact" id="btnSendRoomChat" style="font-size: 0.75rem; padding: 3px 8px;">
                        > TX
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Pantalla de Éxito RobCo (Acceso Concedido) -->
    <div class="term-screen-takeover" id="winOverlay">
        <div class="takeover-title" id="winTitle">====================================<br>¡ACCESO CONCEDIDO!<br>====================================</div>
        <div class="takeover-body" id="winDesc">
            SISTEMA DE SEGURIDAD DE BÓVEDA DESBLOQUEADO.<br>
            CONTRASEÑA VERIFICADA: [<span id="winSecretWord"></span>]<br><br>
            AUTORIZACIÓN DE SUPERVISOR CONCEDIDA.
        </div>
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <button class="term-action-btn btn-rematch-action" onclick="requestRematch()" style="font-size: 1.05rem; padding: 10px 20px;">
                > [REINICIAR PROTOCOLO // REVANCHA]
            </button>
            <a href="{{ route('terminal.lobby') }}" class="term-action-btn" style="font-size: 1.05rem; padding: 10px 20px;">
                > [VOLVER A TERMINAL MAESTRA]
            </a>
        </div>
    </div>

    <!-- Pantalla de Error RobCo (Terminal Bloqueada) -->
    <div class="term-screen-takeover loss" id="lossOverlay">
        <div class="takeover-title" id="lossTitle">====================================<br>TERMINAL BLOQUEADA<br>====================================</div>
        <div class="takeover-body" id="lossDesc">
            CONTACTE CON EL ADMINISTRADOR DE LA BÓVEDA.<br>
            EL NODO REMOTO HA COMPROMETIDO EL SISTEMA.
        </div>
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <button class="term-action-btn btn-rematch-action" onclick="requestRematch()" style="font-size: 1.05rem; padding: 10px 20px; border-color: var(--term-red); color: var(--term-red);">
                > [REINICIAR PROTOCOLO // REVANCHA]
            </button>
            <a href="{{ route('terminal.lobby') }}" class="term-action-btn" style="font-size: 1.05rem; padding: 10px 20px; border-color: var(--term-red); color: var(--term-red);">
                > [VOLVER A TERMINAL MAESTRA]
            </a>
        </div>
    </div>
</div>

<script>
const ROOM_CODE = "{{ $room->code }}";
const PLAYER_NUM = {{ $playerNumber }};
const IS_PLAYER_1 = (PLAYER_NUM === 1);
const CSRF_TOKEN = "{{ csrf_token() }}";
const MY_TOKEN = "{{ $token }}";
const FIRST_SECRET_CHAR = "{{ $firstChar }}";
const SCIENCE_HINT = @json($scienceHint);
const ACTIVE_PERK = "{{ $perk }}";
const PERK_LABEL = "{{ $perkLabel }}";
const MODE_LABEL = "{{ $modeLabel }}";
const PACK_LABEL = "{{ $packLabel }}";
const WORD_LENGTH = {{ $wordLen }};
const IS_AI_MODE = {{ $isAiMode ? 'true' : 'false' }};
const AI_DIFFICULTY = "{{ $aiDiff }}";
const ROOM_SETTINGS = @json($roomSettings);
const GAME_MODE = ROOM_SETTINGS.mode || 'competitive';
const TIME_LIMIT = ROOM_SETTINGS.time_limit || null;
const SHOW_LIKENESS = ROOM_SETTINGS.show_likeness !== false;
const MAX_ATTEMPTS = {{ $maxAtt }};
const IS_COOP = (GAME_MODE === 'cooperative');
const IS_TIMED = (GAME_MODE === 'timed');
let myAttempts = {{ $myAttempts }};
let isGameActive = {{ $room->status === 'finished' ? 'false' : 'true' }};

// ── REGISTRO DE SERVICIO Y LOGROS ROBCO (PERSISTENCIA LOCAL) ───────────
function getServiceRecord() {
    const raw = localStorage.getItem('robco_service_record');
    if (raw) {
        try { return JSON.parse(raw); } catch(e){}
    }
    return {
        games_played: 0,
        games_won: 0,
        ai_games_won: 0,
        duds_removed: 0,
        brackets_used: 0,
        interferences_sent: 0,
        one_shot_wins: 0,
        unlocked_achievements: [],
        active_title: '[OPERADOR NOVATO]'
    };
}

function saveServiceRecord(rec) {
    localStorage.setItem('robco_service_record', JSON.stringify(rec));
}

function recordServiceStat(stat, inc = 1) {
    const rec = getServiceRecord();
    rec[stat] = (rec[stat] || 0) + inc;

    if (rec.games_won >= 1 && !rec.unlocked_achievements.includes('first_hack')) {
        rec.unlocked_achievements.push('first_hack');
        appendLog('> [HOLOCINTA DESBLOQUEADA]: "PRIMERA INTRUSIÓN" · TÍTULO [HACKER DEL YERMO]', 'log-line-sys');
    }
    if (rec.ai_games_won >= 1 && !rec.unlocked_achievements.includes('ai_slayer')) {
        rec.unlocked_achievements.push('ai_slayer');
        appendLog('> [HOLOCINTA DESBLOQUEADA]: "DESTRUCTOR DE ZAX" · TÍTULO [DESTRUCTOR DE ZAX]', 'log-line-sys');
    }
    if (rec.duds_removed >= 10 && !rec.unlocked_achievements.includes('bracket_master')) {
        rec.unlocked_achievements.push('bracket_master');
        appendLog('> [HOLOCINTA DESBLOQUEADA]: "PURGADOR DE MEMORIA" · TÍTULO [PURGADOR DE MEMORIA]', 'log-line-sys');
    }
    if (rec.interferences_sent >= 3 && !rec.unlocked_achievements.includes('interferer')) {
        rec.unlocked_achievements.push('interferer');
        appendLog('> [HOLOCINTA DESBLOQUEADA]: "GUERRA ELECTROMAGNÉTICA" · TÍTULO [GUERRILLERO EMP]', 'log-line-sys');
    }
    if (rec.games_won >= 5 && !rec.unlocked_achievements.includes('supervisor')) {
        rec.unlocked_achievements.push('supervisor');
        appendLog('> [HOLOCINTA DESBLOQUEADA]: "VETERANO DE ROBCO" · TÍTULO [SUPERVISOR SUPREMO]', 'log-line-sys');
    }
    saveServiceRecord(rec);
}

// ── ATAQUE DE INTERFERENCIA CRT GLITCH ────────────────────────────────
let glitchTimer = null;
function triggerCrtGlitchEffect(senderName = 'RIVAL') {
    const screen = document.querySelector('.term-screen');
    const banner = document.getElementById('interferenceAlertBanner');
    if (!screen) return;

    screen.classList.add('crt-interference-glitch');
    if (banner) {
        banner.style.display = 'block';
        banner.innerText = `⚡ ¡ALERTA: INTERFERENCIA ELECTROMAGNÉTICA DE ${senderName.toUpperCase()}! SEÑAL INESTABLE ⚡`;
    }
    appendLog(`> ⚡ [ALERTA DE SEGURIDAD] ¡DESCARGA ELECTROMAGNÉTICA RECIBIDA DE ${senderName.toUpperCase()}!`, 'log-line-remote');
    if (window.robcoAudio) window.robcoAudio.playAlarm();

    clearTimeout(glitchTimer);
    glitchTimer = setTimeout(() => {
        screen.classList.remove('crt-interference-glitch');
        if (banner) banner.style.display = 'none';
        appendLog('> [SISTEMA] FILTROS CATÓDICOS RESTABLECIDOS. SEÑAL NORMALIZADA.', 'log-line-sys');
    }, 3800);
}

// ── EJECUCIÓN PERK: INYECCIÓN DE CÓDIGO ────────────────────────────────
let injectionUsed = false;
async function executeCodeInjection() {
    if (injectionUsed || !isGameActive) return;
    const btn = document.getElementById('btnCodeInjection');
    const btnBanner = document.getElementById('btnCodeInjectionBanner');
    if (btn) {
        btn.disabled = true;
        btn.style.opacity = '0.5';
        btn.innerText = '[⚡ INYECCIÓN DE CÓDIGO: AGOTADA]';
    }
    if (btnBanner) {
        btnBanner.disabled = true;
        btnBanner.style.opacity = '0.5';
        btnBanner.innerText = '⚡ [INYECCIÓN AGOTADA]';
    }
    injectionUsed = true;
    appendLog('> [⚡ INYECCIÓN DE CÓDIGO]: INYECTANDO SECUENCIA DESCOMPILADORA...', 'log-line-sys');
    if (window.robcoAudio) window.robcoAudio.playBracketBonus();

    const activeWords = [];
    document.querySelectorAll('.cell[data-type="word"]:not(.dud-removed)').forEach(c => {
        if (!activeWords.includes(c.dataset.word)) {
            activeWords.push(c.dataset.word);
        }
    });

    try {
        const res = await fetch(`/terminal/sala/${ROOM_CODE}/corchete`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
            body: JSON.stringify({ bonus_type: 'remove_dud', active_words: activeWords })
        });
        const data = await res.json();
        if (data.removed_dud) {
            document.querySelectorAll(`.cell[data-word="${data.removed_dud}"]`).forEach(c => {
                c.classList.add('dud-removed');
                c.innerText = '.';
            });
            appendLog(`> INYECCIÓN: PALABRA FALSA '${data.removed_dud}' PURGADA (1/2).`, 'log-line-sys');
            recordServiceStat('duds_removed', 1);
        }

        const remainingWords = activeWords.filter(w => w !== data.removed_dud);
        if (remainingWords.length > 1) {
            const res2 = await fetch(`/terminal/sala/${ROOM_CODE}/corchete`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: JSON.stringify({ bonus_type: 'remove_dud', active_words: remainingWords })
            });
            const data2 = await res2.json();
            if (data2.removed_dud) {
                document.querySelectorAll(`.cell[data-word="${data2.removed_dud}"]`).forEach(c => {
                    c.classList.add('dud-removed');
                    c.innerText = '.';
                });
                appendLog(`> INYECCIÓN: PALABRA FALSA '${data2.removed_dud}' PURGADA (2/2).`, 'log-line-sys');
                recordServiceStat('duds_removed', 1);
            }
        }
    } catch (e) {
        console.error('Error en inyección de código:', e);
    }
}

// ── BUCLE DE SIMULACIÓN IA (ZAX MAINFRAME) ─────────────────────────────
let zaxInterval = null;
let zaxAttemptsLeft = MAX_ATTEMPTS;
function initZaxAiSimulation() {
    if (!IS_AI_MODE || !IS_PLAYER_1) return;

    let intervalMs = 12000;
    let winChance = 0.20;
    let glitchChance = 0.15;

    if (AI_DIFFICULTY === 'easy') {
        intervalMs = 16000;
        winChance = 0.12;
        glitchChance = 0.05;
    } else if (AI_DIFFICULTY === 'hard') {
        intervalMs = 7500;
        winChance = 0.32;
        glitchChance = 0.35;
    }

    appendLog(`> [ZAX MAINFRAME v1.0] PROTOCOLO DE DEFENSA ACTIVO · DIFICULTAD: ${AI_DIFFICULTY.toUpperCase()}.`, 'log-line-remote');

    zaxInterval = setInterval(() => {
        if (!isGameActive || zaxAttemptsLeft <= 0) {
            clearInterval(zaxInterval);
            return;
        }

        // Probabilidad de ataque electromagnético de ZAX
        if (Math.random() < glitchChance) {
            appendLog(`> [ZAX MAINFRAME] EJECUTANDO DESCARGA EMP DE CONTRAMEDIDA...`, 'log-line-remote');
            triggerCrtGlitchEffect('ZAX MAINFRAME');
            return;
        }

        // Palabras activas en el terminal
        const words = [];
        document.querySelectorAll('.cell[data-type="word"]:not(.dud-removed)').forEach(c => {
            if (!words.includes(c.dataset.word)) words.push(c.dataset.word);
        });

        if (words.length === 0) return;
        const pickedWord = words[Math.floor(Math.random() * words.length)];

        zaxAttemptsLeft--;
        updateRivalAttempts(zaxAttemptsLeft);

        const isWin = (Math.random() < winChance);
        if (isWin) {
            isGameActive = false;
            clearInterval(zaxInterval);
            appendLog(`> [ZAX MAINFRAME] CRIPTOANÁLISIS COMPLETADO: '${pickedWord}' [VÁLIDO]`, 'log-line-remote');
            if (window.robcoAudio) window.robcoAudio.playAlarm();
            document.getElementById('lossTitle').innerHTML = '====================================<br>INTRUSIÓN DE IA DETECTADA<br>====================================';
            document.getElementById('lossDesc').innerHTML  = 'LA SUPERCOMPUTADORA ROBCO ZAX HA DESCIFRADO LA CONTRASEÑA ANTES QUE TÚ.<br><br>CONTROL DE BÓVEDA CONFISCADO POR LA IA.';
            document.getElementById('lossOverlay').classList.add('active');
            return;
        }

        const simLikeness = Math.floor(Math.random() * (pickedWord.length - 1));
        appendLog(`> [ZAX MAINFRAME] PRUEBA DE VECTOR: '${pickedWord}' · SEMEJANZA: ${simLikeness}/${pickedWord.length}`, 'log-line-remote');
        document.getElementById('rival-last-action').innerText = `> ÚLTIMO INTENTO ZAX: '${pickedWord}' (${simLikeness}/${pickedWord.length})`;
        if (window.robcoAudio) window.robcoAudio.playKeyClick();

        if (zaxAttemptsLeft <= 0) {
            clearInterval(zaxInterval);
            appendLog(`> [ZAX MAINFRAME] ERROR FATAL EN BANCO DE MEMORIA. SUBRUTINA DE IA BLOQUEADA.`, 'log-line-sys');
            document.getElementById('rival-last-action').innerText = `> ZAX BLOQUEADO // 0 INTENTOS`;
        }
    }, intervalMs);
}

function appendLog(text, type = 'log-line-local') {
    const log = document.getElementById('systemLog');
    const line = document.createElement('div');
    line.className = `log-line ${type}`;
    line.innerText = text;
    log.appendChild(line);
    log.scrollTop = log.scrollHeight;
}

function updateMyAttempts(left) {
    myAttempts = left;
    const txt = document.getElementById('attemptsText');
    if (txt) txt.innerText = left;

    for (let i = 0; i < MAX_ATTEMPTS; i++) {
        const el = document.getElementById(`my-att-${i}`);
        if (el) {
            if (i < left) {
                el.classList.remove('spent');
            } else {
                el.classList.add('spent');
            }
        }
    }
}

function updateRivalAttempts(left) {
    for (let i = 0; i < MAX_ATTEMPTS; i++) {
        const el = document.getElementById(`riv-att-${i}`);
        if (el) {
            if (i < left) {
                el.classList.remove('spent');
            } else {
                el.classList.add('spent');
            }
        }
    }
}

function copyInviteLink() {
    navigator.clipboard.writeText(window.location.href);
    appendLog('> FRECUENCIA DE ENLACE COPIADA AL PORTAPAPELES.', 'log-line-sys');
    if (window.robcoAudio) window.robcoAudio.playKeyClick();
}

// -------------------------------------------------------------
// Interacción con Celdas del Volcado de Memoria
// -------------------------------------------------------------
function attachCellEvents() {
    const preview = document.getElementById('previewSelection');

    document.querySelectorAll('.cell').forEach(cell => {
        cell.addEventListener('mouseenter', () => {
            const type = cell.dataset.type;
            if (type === 'word') {
                const word = cell.dataset.word;
                if (!cell.classList.contains('dud-removed')) {
                    document.querySelectorAll(`.cell[data-word="${word}"]`).forEach(c => c.classList.add('highlighted'));
                    if (preview) {
                        if (ACTIVE_PERK === 'science' && typeof FIRST_SECRET_CHAR === 'string' && FIRST_SECRET_CHAR && word.startsWith(FIRST_SECRET_CHAR)) {
                            preview.innerText = `${word} [🧠 1ª LETRA COINCIDE]`;
                        } else {
                            preview.innerText = word;
                        }
                    }
                    if (window.robcoAudio) window.robcoAudio.playCharHover();
                }
            } else if (type === 'bracket') {
                const bId = cell.dataset.bracketId;
                const bonus = cell.dataset.bonus;
                document.querySelectorAll(`.cell[data-bracket-id="${bId}"]`).forEach(c => c.classList.add('highlighted'));
                if (preview) {
                    if (ACTIVE_PERK === 'perception') {
                        let bonusDesc = '[CORCHETE DE ASISTENCIA]';
                        if (bonus === 'restore_attempts') bonusDesc = '[RESTABLECER INTENTOS]';
                        else if (bonus === 'remove_dud') bonusDesc = '[PURGAR 1 DUDA FALSA]';
                        else if (bonus === 'offensive_glitch') bonusDesc = '[DESCARGA EMP RIVAL]';
                        preview.innerText = `👁️ CORCHETE: ${bonusDesc}`;
                    } else {
                        preview.innerText = 'CORCHETE DE AYUDA';
                    }
                }
                if (window.robcoAudio) window.robcoAudio.playCharHover();
            } else {
                cell.classList.add('highlighted');
                if (preview) preview.innerText = cell.innerText;
            }
        });

        cell.addEventListener('mouseleave', () => {
            const type = cell.dataset.type;
            if (type === 'word') {
                const word = cell.dataset.word;
                document.querySelectorAll(`.cell[data-word="${word}"]`).forEach(c => c.classList.remove('highlighted'));
            } else if (type === 'bracket') {
                const bId = cell.dataset.bracketId;
                document.querySelectorAll(`.cell[data-bracket-id="${bId}"]`).forEach(c => c.classList.remove('highlighted'));
            } else {
                cell.classList.remove('highlighted');
            }
            if (preview) preview.innerText = 'SELECCIONA PALABRA';
        });

        cell.addEventListener('click', () => {
            if (!isGameActive) return;
            if (myAttempts <= 0) {
                appendLog('> TERMINAL BLOQUEADA. ESPERANDO...', 'log-line-sys');
                return;
            }

            const type = cell.dataset.type;
            if (type === 'word') {
                const word = cell.dataset.word;
                if (!cell.classList.contains('dud-removed')) {
                    submitWordGuess(word);
                }
            } else if (type === 'bracket') {
                const bonus = cell.dataset.bonus;
                const bId = cell.dataset.bracketId;
                claimBracket(bId, bonus);
            } else {
                if (window.robcoAudio) window.robcoAudio.playKeyClick();
                appendLog(`> SÍMBOLO '${cell.innerText}' NO RECONOCIDO.`, 'log-line-sys');
            }
        });
    });
}

function renderNewGrid(gridData) {
    function buildColHtml(colData) {
        return colData.map(line => {
            const cellsHtml = line.cells.map(c => {
                if (c.type === 'word') {
                    return `<span class="cell" data-type="word" data-word="${c.word}" data-index="${c.word_index}">${c.char}</span>`;
                } else if (c.type === 'bracket') {
                    return `<span class="cell" data-type="bracket" data-bracket-id="${c.bracket_id}" data-bonus="${c.bonus}">${c.char}</span>`;
                } else {
                    return `<span class="cell" data-type="junk">${c.char}</span>`;
                }
            }).join('');

            return `<div class="memory-row"><span class="mem-addr">${line.address}</span><div class="mem-chars">${cellsHtml}</div></div>`;
        }).join('');
    }

    const leftCol = document.getElementById('leftColumnContainer');
    const rightCol = document.getElementById('rightColumnContainer');
    if (leftCol && gridData.left_column) leftCol.innerHTML = buildColHtml(gridData.left_column);
    if (rightCol && gridData.right_column) rightCol.innerHTML = buildColHtml(gridData.right_column);

    attachCellEvents();

    if (ACTIVE_PERK === 'perception') {
        document.querySelectorAll('.cell[data-type="bracket"]').forEach(c => {
            c.style.color = 'var(--term-amber)';
            c.style.fontWeight = 'bold';
            c.style.textShadow = '0 0 10px var(--term-amber)';
        });
    }
}

function applyRematchSession(gridData, attempts) {
    isGameActive = true;

    // 1. Ocultar overlays de fin de partida
    const winOverlay = document.getElementById('winOverlay');
    const lossOverlay = document.getElementById('lossOverlay');
    if (winOverlay) winOverlay.classList.remove('active');
    if (lossOverlay) lossOverlay.classList.remove('active');

    // 2. Restaurar botones de revancha
    document.querySelectorAll('.btn-rematch-action').forEach(b => {
        b.disabled = false;
        b.innerText = '> [REINICIAR PROTOCOLO // REVANCHA]';
    });

    // 3. Restaurar intentos
    const resetAttempts = attempts || MAX_ATTEMPTS;
    updateMyAttempts(resetAttempts);
    updateRivalAttempts(resetAttempts);

    // 4. Renderizar nueva matriz de palabras
    if (gridData) {
        renderNewGrid(gridData);
    }

    // 5. Restablecer barra de previsualización
    const preview = document.getElementById('previewSelection');
    if (preview) preview.innerText = 'SELECCIONA PALABRA';

    // 6. Restablecer perk de inyección
    injectionUsed = false;
    const btnInj = document.getElementById('btnCodeInjection');
    const btnInjBanner = document.getElementById('btnCodeInjectionBanner');
    if (btnInj) {
        btnInj.disabled = false;
        btnInj.style.opacity = '1';
        btnInj.innerText = '⚡ [EJECUTAR INYECCIÓN DE CÓDIGO (1x)]';
    }
    if (btnInjBanner) {
        btnInjBanner.disabled = false;
        btnInjBanner.style.opacity = '1';
        btnInjBanner.innerText = '⚡ [PURGAR 2 DUDAS (1x)]';
    }

    // 7. Reiniciar simulación ZAX si aplica
    if (IS_AI_MODE && IS_PLAYER_1) {
        zaxAttemptsLeft = resetAttempts;
        initZaxAiSimulation();
    }

    appendLog('> REVANCHA INICIALIZADA // NUEVA MATRIZ DE MEMORIA CARGADA.', 'log-line-sys');
    if (window.robcoAudio) window.robcoAudio.playBracketBonus();
}

async function requestRematch() {
    if (window.robcoAudio) window.robcoAudio.playKeyClick();

    // Feedback visual inmediato en el botón
    document.querySelectorAll('.btn-rematch-action').forEach(b => {
        b.disabled = true;
        b.innerText = '⚡ [INICIALIZANDO NUEVA SECUENCIA...]';
    });

    appendLog('> TRANSMITIENDO SOLICITUD DE REVANCHA A LA SUBRED...', 'log-line-sys');

    try {
        const res = await fetch(`/terminal/sala/${ROOM_CODE}/revancha`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            }
        });
        const data = await res.json();
        if (data.success) {
            appendLog('> REVANCHA CONCEDIDA // REINICIANDO NODO...', 'log-line-sys');
            window.location.reload();
        } else {
            appendLog(`> ${data.message || 'Error al iniciar revancha'}`, 'log-line-sys');
            document.querySelectorAll('.btn-rematch-action').forEach(b => {
                b.disabled = false;
                b.innerText = '> [REINICIAR PROTOCOLO // REVANCHA]';
            });
        }
    } catch (err) {
        console.error(err);
        appendLog('> ERROR DE ENLACE // NO SE PUDO INICIAR LA REVANCHA', 'log-line-sys');
        document.querySelectorAll('.btn-rematch-action').forEach(b => {
            b.disabled = false;
            b.innerText = '> [REINICIAR PROTOCOLO // REVANCHA]';
        });
    }
}

async function handleForfeitOrExit() {
    if (window.robcoAudio) window.robcoAudio.playKeyClick();

    if (!isGameActive) {
        window.location.href = "{{ route('terminal.lobby') }}";
        return;
    }

    if (IS_AI_MODE) {
        if (!confirm('¿ABANDONAR SIMULACIÓN CONTRA LA IA ZAX? El control de la bóveda será confiscado por RobCo.')) {
            return;
        }
        isGameActive = false;
        if (typeof zaxInterval !== 'undefined' && zaxInterval) clearInterval(zaxInterval);
        appendLog('> SIMULACIÓN ABANDONADA // CONTROL CONFISCADO POR ZAX.', 'log-line-sys');
        document.getElementById('lossTitle').innerHTML = '====================================<br>SIMULACIÓN ABANDONADA<br>====================================';
        document.getElementById('lossDesc').innerHTML  = 'HAS CERRADO LA SESIÓN DE PRUEBA.<br><br>LA SUPERCOMPUTADORA ROBCO ZAX HA TOMADO CONTROL DE LA TERMINAL.';
        document.getElementById('lossOverlay').classList.add('active');
        if (window.robcoAudio) window.robcoAudio.playAlarm();
        return;
    }

    if (!confirm('¿CONFIRMAR ABANDONO DE LA TERMINAL? Cederás el control total de la bóveda al nodo remoto.')) {
        return;
    }

    appendLog('> TRANSMITIENDO PROTOCOLO DE ABANDONO A LA SUBRED...', 'log-line-sys');

    try {
        const res = await fetch(`/terminal/sala/${ROOM_CODE}/abandonar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        if (data.success) {
            isGameActive = false;
            document.getElementById('lossTitle').innerHTML = '====================================<br>TERMINAL ABANDONADA<br>====================================';
            document.getElementById('lossDesc').innerHTML  = 'HAS CERRADO LA TRANSMISIÓN Y CEDIDO LA BÓVEDA AL NODO REMOTO.';
            document.getElementById('lossOverlay').classList.add('active');
            if (window.robcoAudio) window.robcoAudio.playAlarm();
        } else {
            alert(data.message || 'Error al abandonar.');
        }
    } catch (err) {
        console.error(err);
        window.location.href = "{{ route('terminal.lobby') }}";
    }
}

function initRobcoTerminal() {
    attachCellEvents();

    // Iniciar registro de estadísticas
    recordServiceStat('games_played', 1);

    // Título activo del Operador
    const rec = getServiceRecord();
    const titleEl = document.getElementById('operatorActiveTitle');
    if (titleEl && rec.active_title) {
        titleEl.innerText = rec.active_title;
    }

    // Efectos iniciales y feedback en consola según Perk activo
    if (ACTIVE_PERK === 'science') {
        if (SCIENCE_HINT) {
            appendLog(`> [🧠 PERK CIENCIA ACTIVADO]: ANÁLISIS PREVIO: ${SCIENCE_HINT}.`, 'log-line-sys');
        }
    } else if (ACTIVE_PERK === 'perception') {
        const bracketCount = Math.round(document.querySelectorAll('.cell[data-type="bracket"]').length / 2);
        appendLog(`> [👁️ PERK PERCEPCIÓN ACTIVADO]: DETECTADOS ${bracketCount} MÓDULOS DE CORCHETES DE ASISTENCIA IDENTIFICADOS.`, 'log-line-sys');
        document.querySelectorAll('.cell[data-type="bracket"]').forEach(c => {
            c.style.color = 'var(--term-amber)';
            c.style.fontWeight = 'bold';
            c.style.textShadow = '0 0 10px var(--term-amber)';
        });
    } else if (ACTIVE_PERK === 'luck') {
        appendLog('> [🍀 PERK SUERTE ACTIVADO]: 30% DE PROBABILIDAD DE PRESERVAR INTENTO TRAS FALLO.', 'log-line-sys');
    } else if (ACTIVE_PERK === 'injection') {
        appendLog('> [⚡ PERK INYECCIÓN DE CÓDIGO]: PURGA MANUAL DE 2 DUDAS LISTA EN EL PANEL LATERAL.', 'log-line-sys');
    }

    // Si es modo IA vs ZAX
    if (IS_AI_MODE && IS_PLAYER_1) {
        initZaxAiSimulation();
    }

    // -------------------------------------------------------------
    // Conexión WebSocket Reverb & Echo
    // -------------------------------------------------------------
    if (window.Echo) {
        window.Echo.channel(`robco-room.${ROOM_CODE}`)
            .listen('.TerminalPlayerJoined', (e) => {
                appendLog(`> [ALERTA DE SEGURIDAD] NODO REMOTO ${e.player2Name.toUpperCase()} VINCULADO.`, 'log-line-remote');
                document.getElementById('rival-name').innerText = e.player2Name.toUpperCase();
                document.getElementById('rival-state-tag').innerText = '[CONECTADO]';
                if (window.robcoAudio) window.robcoAudio.playAlarm();
            })
            .listen('.TerminalAttemptMade', (e) => {
                if (e.playerNumber !== PLAYER_NUM) {
                    updateRivalAttempts(e.attemptsLeft);
                    const rivalMsg = `> NODO REMOTO PROBÓ PALABRA · SEMEJANZA: ${e.likeness}/${e.wordLength}`;
                    appendLog(rivalMsg, 'log-line-remote');
                    document.getElementById('rival-last-action').innerText = `> ÚLTIMO INTENTO: SEMEJANZA ${e.likeness}/${e.wordLength}`;

                    if (e.isLocked) {
                        appendLog(`> [SISTEMA] NODO REMOTO HA AGOTADO SUS INTENTOS // BLOQUEADO.`, 'log-line-sys');
                    }
                    if (window.robcoAudio) window.robcoAudio.playKeyClick();
                }
            })
            .listen('.TerminalBracketUsed', (e) => {
                if (e.playerNumber !== PLAYER_NUM) {
                    if (e.bonusType === 'restore_attempts') {
                        updateRivalAttempts(e.attemptsRestored || MAX_ATTEMPTS);
                        appendLog(`> NODO REMOTO RESTABLECIÓ SUS INTENTOS.`, 'log-line-remote');
                    } else if (e.bonusType === 'offensive_glitch') {
                        appendLog(`> NODO REMOTO HA EMITIDO UNA INTERFERENCIA EMP CONTRA TU TERMINAL.`, 'log-line-remote');
                    } else {
                        appendLog(`> NODO REMOTO ELIMINÓ UNA PALABRA FALSA.`, 'log-line-remote');
                    }
                }
            })
            .listen('.TerminalInterference', (e) => {
                if (e.senderPlayerNumber !== PLAYER_NUM) {
                    triggerCrtGlitchEffect(e.senderName);
                }
            })
            .listen('.TerminalHacked', (e) => {
                isGameActive = false;
                if (typeof zaxInterval !== 'undefined' && zaxInterval) clearInterval(zaxInterval);
                if (e.winnerNumber === 0) {
                    document.getElementById('lossTitle').innerHTML = '====================================<br>DOBLE BLOQUEO DE SEGURIDAD<br>====================================';
                    document.getElementById('lossDesc').innerHTML = `AMBOS OPERADORES HAN AGOTADO SUS INTENTOS.<br><br>LA CONTRASEÑA SECRETA ERA: [${e.secretWord}].<br>SUBRUTINA CLAUSURADA.`;
                    document.getElementById('lossOverlay').classList.add('active');
                    if (window.robcoAudio) window.robcoAudio.playAlarm();
                } else if (e.winnerNumber === PLAYER_NUM) {
                    document.getElementById('winSecretWord').innerText = e.secretWord;
                    const winTitle = document.getElementById('winTitle');
                    const winDesc = document.getElementById('winDesc');
                    if (e.isForfeit) {
                        if (winTitle) winTitle.innerHTML = '====================================<br>¡VICTORIA POR FORFEIT!<br>====================================';
                        if (winDesc) winDesc.innerHTML = `EL NODO REMOTO [${(e.forfeitPlayerName || 'RIVAL').toUpperCase()}] HA ABANDONADO LA FRECUENCIA.<br><br>CONTROL TOTAL DE LA BÓVEDA ASIGNADO A TU TERMINAL.<br>LA CONTRASEÑA ERA: [${e.secretWord}].`;
                        appendLog(`> [ALERTA DE SISTEMA] NODO REMOTO HA ABANDONADO LA TERMINAL. ¡VICTORIA POR FORFEIT!`, 'log-line-sys');
                    } else {
                        if (winTitle) winTitle.innerHTML = '====================================<br>¡ACCESO CONCEDIDO!<br>====================================';
                        if (winDesc) winDesc.innerHTML = `SISTEMA DE SEGURIDAD DE BÓVEDA DESBLOQUEADO.<br>CONTRASEÑA VERIFICADA: [<span id="winSecretWord">${e.secretWord}</span>]<br><br>AUTORIZACIÓN DE SUPERVISOR CONCEDIDA.`;
                    }
                    document.getElementById('winOverlay').classList.add('active');
                    if (window.robcoAudio) window.robcoAudio.playAccessGranted();
                    recordServiceStat('games_won', 1);
                    if (IS_AI_MODE) recordServiceStat('ai_games_won', 1);
                } else {
                    document.getElementById('lossTitle').innerHTML = e.isForfeit
                        ? '====================================<br>TERMINAL ABANDONADA<br>===================================='
                        : '====================================<br>ACCESO DENEGADO<br>====================================';
                    document.getElementById('lossDesc').innerHTML = e.isForfeit
                        ? `HAS CERRADO LA TRANSMISIÓN Y CEDIDO EL CONTROL DE LA BÓVEDA AL NODO REMOTO [${e.winnerName.toUpperCase()}].`
                        : `EL NODO REMOTO [${e.winnerName.toUpperCase()}] HA DESCIFRADO LA CONTRASEÑA [${e.secretWord}].<br><br>CONTROL DE BÓVEDA PERDIDO.`;
                    document.getElementById('lossOverlay').classList.add('active');
                    if (window.robcoAudio) window.robcoAudio.playAlarm();
                }
            })
            .listen('.TerminalRematch', (e) => {
                appendLog('> EL OTRO OPERADOR HA INICIADO UNA REVANCHA // RECARGANDO TERMINAL...', 'log-line-sys');
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            })
            .listen('.RoomChatMessage', (e) => {
                appendRoomChatMessage(e);
            });
    }

    // Scroll inicial del chat de sala
    scrollRoomChatToBottom();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRobcoTerminal);
} else {
    initRobcoTerminal();
}

// ── MANEJO DEL CHAT DE SALA (INTER-BÓVEDA) ────────────────────────────────
function scrollRoomChatToBottom() {
    const feed = document.getElementById('roomChatFeed');
    if (feed) {
        feed.scrollTop = feed.scrollHeight;
    }
}

function appendRoomChatMessage(data) {
    const feed = document.getElementById('roomChatFeed');
    if (!feed) return;

    // 1. Deduplicación por ID único (proveniente de BD / WebSocket / HTTP)
    if (data.id && feed.querySelector(`[data-chat-id="${data.id}"]`)) {
        return;
    }

    const isOwn = (data.playerToken === MY_TOKEN);

    // 2. Salvaguarda para mensajes propios: evita duplicación local
    // si el evento WebSocket y la respuesta HTTP llegan desincronizados
    if (isOwn) {
        const ownMsgs = feed.querySelectorAll('.room-chat-msg-own');
        const lastOwn = ownMsgs.length > 0 ? ownMsgs[ownMsgs.length - 1] : null;
        if (lastOwn) {
            const lastText = lastOwn.querySelector('.room-chat-text')?.textContent;
            const existingId = lastOwn.dataset.chatId;

            // Si ambos tienen ID asignado y son distintos, son mensajes intencionales diferentes
            const bothHaveDifferentIds = (data.id && existingId && String(data.id) !== String(existingId));

            if (!bothHaveDifferentIds && lastText === data.message) {
                if (data.id && !existingId) {
                    lastOwn.dataset.chatId = data.id;
                }
                return;
            }
        }
    }

    const empty = document.getElementById('noRoomChatMsg');
    if (empty) empty.remove();

    const div = document.createElement('div');
    if (data.id) div.dataset.chatId = data.id;
    div.className = `room-chat-msg ${isOwn ? 'room-chat-msg-own' : 'room-chat-msg-rival'}`;
    div.innerHTML = `
        <span class="room-chat-time">[${data.time || 'AHORA'}]</span>
        <span class="room-chat-author">&lt;${(data.playerName || 'OPERADOR').toUpperCase()}&gt;:</span>
        <span class="room-chat-text"></span>
    `;
    div.querySelector('.room-chat-text').textContent = data.message;
    feed.appendChild(div);
    scrollRoomChatToBottom();

    if (!isOwn && window.robcoAudio) {
        window.robcoAudio.playKeyClick();
    }
}

async function handleSendRoomChat(e) {
    e.preventDefault();
    const input = document.getElementById('roomChatInput');
    const btn = document.getElementById('btnSendRoomChat');
    if (!input) return;

    const message = input.value.trim();
    if (!message) return;

    if (btn) btn.disabled = true;

    try {
        const res = await fetch(`/terminal/sala/${ROOM_CODE}/chat`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        });
        const data = await res.json();
        if (data.success && data.data) {
            input.value = '';
            // Renderizado optimista inmediato sin necesidad de recargar la página
            appendRoomChatMessage(data.data);
            if (window.robcoAudio) window.robcoAudio.playKeyClick();
        } else if (data.success) {
            input.value = '';
        } else {
            alert(data.message || 'Error al transmitir mensaje.');
        }
    } catch (err) {
        console.error('Error enviando mensaje en sala:', err);
    } finally {
        if (btn) btn.disabled = false;
        input.focus();
    }
}

// Enviar intento al servidor
async function submitWordGuess(word) {
    if (window.robcoAudio) window.robcoAudio.playKeyClick();
    appendLog(`> ENTRADA: ${word}...`, 'log-line-local');

    try {
        const res = await fetch(`/terminal/sala/${ROOM_CODE}/probar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ word: word })
        });
        const data = await res.json();

        if (!data.success) {
            appendLog(`> ${data.message}`, 'log-line-sys');
            return;
        }

        updateMyAttempts(data.attempts_left);

        if (data.luck_saved) {
            appendLog(`> [🍀 PERK SUERTE]: ¡PROBABILIDAD CRÍTICA! EL INTENTO NO SE HA CONSUMIDO.`, 'log-line-sys');
            if (window.robcoAudio) window.robcoAudio.playBracketBonus();
        }

        if (data.is_correct) {
            appendLog(`> ¡CONTRASEÑA CORRECTA! ACCESO CONCEDIDO.`, 'log-line-sys');
            if (window.robcoAudio) window.robcoAudio.playAccessGranted();
            recordServiceStat('games_won', 1);
            if (data.attempts_used === 1) {
                recordServiceStat('one_shot_wins', 1);
                appendLog('> [HOLOCINTA]: "DESCIFRADO PERFECTO" LOGRADO AL PRIMER INTENTO.', 'log-line-sys');
            }
            if (IS_AI_MODE) {
                recordServiceStat('ai_games_won', 1);
            }
        } else {
            appendLog(`> ENTRADA DENEGADA. SEMEJANZA = ${data.likeness}`, 'log-line-local');
            if (window.robcoAudio) window.robcoAudio.playAccessDenied();

            if (data.is_locked) {
                appendLog(`> TERMINAL BLOQUEADA. TODOS LOS INTENTOS AGOTADOS.`, 'log-line-sys');
                document.getElementById('lossTitle').innerHTML = '====================================<br>TERMINAL BLOQUEADA<br>====================================';
                document.getElementById('lossDesc').innerHTML = 'HAS AGOTADO TODOS TUS INTENTOS DE SEGURIDAD.<br><br>ESPERANDO RESOLUCIÓN DEL NODO REMOTO.';
                document.getElementById('lossOverlay').classList.add('active');
            }
        }
    } catch (err) {
        console.error(err);
    }
}

// Reclamar ayuda por corchetes
async function claimBracket(bracketId, bonusType) {
    if (window.robcoAudio) window.robcoAudio.playBracketBonus();

    const activeWords = [];
    document.querySelectorAll('.cell[data-type="word"]:not(.dud-removed)').forEach(c => {
        if (!activeWords.includes(c.dataset.word)) {
            activeWords.push(c.dataset.word);
        }
    });

    document.querySelectorAll(`.cell[data-bracket-id="${bracketId}"]`).forEach(c => {
        c.innerText = '.';
        c.style.pointerEvents = 'none';
    });

    try {
        const res = await fetch(`/terminal/sala/${ROOM_CODE}/corchete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                bonus_type: bonusType,
                active_words: activeWords,
            })
        });
        const data = await res.json();

        if (data.bonus_type === 'restore_attempts') {
            updateMyAttempts(data.attempts || MAX_ATTEMPTS);
            appendLog(`> CORCHETE: INTENTOS RESTABLECIDOS.`, 'log-line-sys');
            recordServiceStat('brackets_used', 1);
        } else if (data.bonus_type === 'remove_dud' && data.removed_dud) {
            appendLog(`> CORCHETE: PALABRA FALSA '${data.removed_dud}' ELIMINADA.`, 'log-line-sys');
            document.querySelectorAll(`.cell[data-word="${data.removed_dud}"]`).forEach(c => {
                c.classList.add('dud-removed');
                c.innerText = '.';
            });
            recordServiceStat('brackets_used', 1);
            recordServiceStat('duds_removed', 1);
        } else if (data.bonus_type === 'offensive_glitch') {
            appendLog(`> CORCHETE: ¡ATAQUE ELECTROMAGNÉTICO EMITIDO HACIA EL NODO RIVAL!`, 'log-line-sys');
            recordServiceStat('brackets_used', 1);
            recordServiceStat('interferences_sent', 1);
            if (window.robcoAudio) window.robcoAudio.playAlarm();
        }
    } catch (err) {
        console.error(err);
    }
}
</script>

<script>
const BOOT_ROOM_CODE = "{{ $room->code }}";

// ── TEMA PIP-BOY ───────────────────────────────────────────────────────────
function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('robco_theme', theme);
    document.querySelectorAll('.theme-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.t === theme);
    });
}

// ── BOOT BIOS ANIMATION ────────────────────────────────────────────────────
(function robcoBoot() {
    const savedTheme = localStorage.getItem('robco_theme') || 'green';
    applyTheme(savedTheme);

    const skipKey = 'robco_booted_room_' + BOOT_ROOM_CODE;
    const overlay = document.getElementById('boot-overlay');
    const bootText = document.getElementById('boot-text');

    if (sessionStorage.getItem(skipKey)) {
        overlay.style.display = 'none';
        return;
    }

    const lines = [
        "ROBCO INDUSTRIES (TM) TERMLINK PROTOCOL",
        "SECURITY SUBSYSTEM v4.0.12 — VAULT NODE " + BOOT_ROOM_CODE,
        "",
        "LINKING TO REMOTE NODE...",
        "  AUTHENTICATION:      CREDENTIAL TOKEN    [ OK ]",
        "  CHANNEL:             REVERB-ENCRYPTED    [ OK ]",
        "  MEMORY DUMP:         HEX GRID LOADED     [ OK ]",
        "  PASSWORD LENGTH:     " + WORD_LENGTH + " CHARACTERS        [ ARMED ]",
        "",
        "INTRUSION DETECTION SYSTEM: ACTIVE",
        "  CONFIGURACIÓN DE SUBRED CARGADA:",
        "  ------------------------------------------------",
        "  MODO DE PROTOCOLO:   " + MODE_LABEL,
        "  INTENTOS ASIGNADOS:  " + MAX_ATTEMPTS + (IS_COOP ? " (POOL COMPARTIDO)" : " POR OPERADOR"),
        ...(IS_TIMED && TIME_LIMIT ? ["  LÍMITE TEMPORAL:     " + TIME_LIMIT + " SEGUNDOS"] : []),
        ...(IS_AI_MODE ? ["  SISTEMA HOSTIL:      ZAX MAINFRAME [" + AI_DIFFICULTY.toUpperCase() + "]"] : []),
        "  PAQUETE DE DATOS:    " + PACK_LABEL,
        "  MÓDULO S.P.E.C.I.A.L.: " + PERK_LABEL,
        "  SEMEJANZA VISIBLE:   " + (SHOW_LIKENESS ? "SÍ [X/Y]" : "NO [CLASIFICADO]"),
        "  MÓDULOS DE CORCHETE: ENABLED",
        "  ------------------------------------------------",
        "",
        ">>> INTRODUZCA CONTRASEÑA PARA ACCEDER A SISTEMAS DE BÓVEDA.",
        ">>> ACCESO NO AUTORIZADO ACTIVARÁ BLOQUEO DE TERMINAL.",
        "__________________________________________________",
    ];

    let lineIdx = 0, charIdx = 0, output = '';
    const CHAR_DELAY = 12;
    const LINE_PAUSE = 90;

    function typeNext() {
        if (lineIdx >= lines.length) {
            sessionStorage.setItem(skipKey, '1');
            setTimeout(() => {
                overlay.classList.add('fade-out');
                setTimeout(() => overlay.style.display = 'none', 650);
            }, 500);
            return;
        }
        const line = lines[lineIdx];
        if (charIdx < line.length) {
            output += line[charIdx++];
            bootText.textContent = output;
            setTimeout(typeNext, CHAR_DELAY);
        } else {
            output += '\n';
            bootText.textContent = output;
            lineIdx++; charIdx = 0;
            setTimeout(typeNext, line === '' ? 35 : LINE_PAUSE);
        }
    }

    const skip = () => { sessionStorage.setItem(skipKey, '1'); overlay.style.display = 'none'; };
    document.addEventListener('keydown', skip, { once: true });
    overlay.addEventListener('click', skip, { once: true });
    setTimeout(typeNext, 150);
})();
</script>

<script>
// ── Mostrar indicadores de modo en el DOM ─────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Pool cooperativo
    const coopEl = document.getElementById('coopPool');
    if (IS_COOP && coopEl) coopEl.style.display = 'block';

    // Temporizador: mostrar y arrancar si ya estamos jugando
    if (IS_TIMED && TIME_LIMIT) {
        const wrap = document.getElementById('timerWrap');
        if (wrap) wrap.style.display = 'block';
        if (isGameActive && {{ $room->status === 'playing' ? 'true' : 'false' }}) {
            startGameTimer(TIME_LIMIT);
        }
    }
});

// ── Temporizador de misión ────────────────────────────────────────────────
let timerInterval    = null;
let timerSecondsLeft = TIME_LIMIT || 0;

function startGameTimer(totalSeconds) {
    if (!IS_TIMED || !totalSeconds) return;
    clearInterval(timerInterval);
    timerSecondsLeft = totalSeconds;

    const wrap   = document.getElementById('timerWrap');
    const fill   = document.getElementById('timerFill');
    const disp   = document.getElementById('timerDisplay');
    const panic  = document.getElementById('timerPanic');
    if (wrap) wrap.style.display = 'block';

    timerInterval = setInterval(() => {
        timerSecondsLeft--;
        const pct  = Math.max(0, (timerSecondsLeft / totalSeconds) * 100);
        const mins = Math.floor(timerSecondsLeft / 60);
        const secs = timerSecondsLeft % 60;

        if (fill)  { fill.style.width      = pct + '%'; fill.style.background = timerSecondsLeft <= 10 ? 'var(--term-red)' : 'var(--term-green)'; }
        if (disp)  disp.textContent = `${String(mins).padStart(2,'0')}:${String(secs).padStart(2,'0')}`;
        if (panic) panic.style.display = timerSecondsLeft <= 10 ? 'inline' : 'none';

        if (timerSecondsLeft <= 0) {
            clearInterval(timerInterval);
            isGameActive = false;
            appendLog('> TIEMPO AGOTADO. BLOQUEO DE SEGURIDAD ACTIVADO.', 'log-line-sys');
            if (window.robcoAudio) window.robcoAudio.playAlarm();
            document.getElementById('lossTitle').innerHTML = '====================================<br>TIEMPO AGOTADO<br>====================================';
            document.getElementById('lossDesc').innerHTML  = 'EL LÍMITE DE TIEMPO DE MISIÓN HA EXPIRADO.<br><br>SUBRUTINA DE SEGURIDAD CLAUSURADA.';
            document.getElementById('lossOverlay').classList.add('active');
        }
    }, 1000);
}

// ── Parche: arrancar timer cuando se une el jugador 2 ────────────────────
const _origPlayerJoinedHandler = window.__playerJoinedHook;
window.__timerStartOnJoin = function() {
    if (IS_TIMED && TIME_LIMIT && !timerInterval) {
        startGameTimer(TIME_LIMIT);
    }
};

// Se llama desde el listener de TerminalPlayerJoined en el script principal
// Hook inyectado en DOMContentLoaded para evitar orden de dependencia
document.addEventListener('DOMContentLoaded', () => {
    // Parchear Echo listener después de que se registre el handler original
    setTimeout(() => {
        if (window.Echo) {
            window.Echo.channel(`robco-room.${ROOM_CODE}`)
                .listen('.TerminalPlayerJoined', () => {
                    window.__timerStartOnJoin();
                });
        }
    }, 100);
});

// ── Parche de updateMyAttempts para modo cooperativo ─────────────────────
const _baseUpdateMyAttempts = updateMyAttempts;
window.updateMyAttempts = function(left) {
    _baseUpdateMyAttempts(left);
    if (IS_COOP) {
        const coopCount = document.getElementById('coopCount');
        if (coopCount) coopCount.textContent = left;
    }
};

// ── Parche de submitWordGuess para enmascarar semejanza si show_likeness=false
const _baseSubmitWordGuess = submitWordGuess;
window.submitWordGuess = async function(word) {
    if (!SHOW_LIKENESS) {
        // Intercepción: hacemos el fetch manualmente para controlar el mensaje
        if (window.robcoAudio) window.robcoAudio.playKeyClick();
        appendLog(`> ENTRADA: ${word}...`, 'log-line-local');
        try {
            const res  = await fetch(`/terminal/sala/${ROOM_CODE}/probar`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: JSON.stringify({ word })
            });
            const data = await res.json();
            if (!data.success) { appendLog(`> ${data.message}`, 'log-line-sys'); return; }
            updateMyAttempts(data.attempts_left);
            if (data.luck_saved) {
                appendLog(`> [🍀 PERK SUERTE]: ¡PROBABILIDAD CRÍTICA! EL INTENTO NO SE HA CONSUMIDO.`, 'log-line-sys');
                if (window.robcoAudio) window.robcoAudio.playBracketBonus();
            }
            if (data.is_correct) {
                appendLog('> ¡CONTRASEÑA CORRECTA! ACCESO CONCEDIDO.', 'log-line-sys');
                if (window.robcoAudio) window.robcoAudio.playAccessGranted();
                recordServiceStat('games_won', 1);
                if (data.attempts_used === 1) recordServiceStat('one_shot_wins', 1);
                if (IS_AI_MODE) recordServiceStat('ai_games_won', 1);
            } else {
                appendLog('> ENTRADA DENEGADA. SEMEJANZA: [CLASIFICADO]', 'log-line-local');
                if (window.robcoAudio) window.robcoAudio.playAccessDenied();
                if (data.is_locked) {
                    appendLog('> TERMINAL BLOQUEADA. TODOS LOS INTENTOS AGOTADOS.', 'log-line-sys');
                    document.getElementById('lossTitle').innerHTML = '====================================<br>TERMINAL BLOQUEADA<br>====================================';
                    document.getElementById('lossDesc').innerHTML  = 'HAS AGOTADO TODOS TUS INTENTOS DE SEGURIDAD.<br><br>ESPERANDO RESOLUCIÓN DEL NODO REMOTO.';
                    document.getElementById('lossOverlay').classList.add('active');
                }
            }
        } catch (err) { console.error(err); }
        return;
    }
    return _baseSubmitWordGuess(word);
};

// Parche rival log para ocultar semejanza numérica en modo oculto
if (!SHOW_LIKENESS) {
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Echo) {
            window.Echo.channel(`robco-room.${ROOM_CODE}`)
                .listen('.TerminalAttemptMade', (e) => {
                    if (e.playerNumber !== PLAYER_NUM) {
                        updateRivalAttempts(e.attemptsLeft);
                        appendLog('> NODO REMOTO REALIZÓ UN INTENTO · SEMEJANZA: [CLASIFICADO]', 'log-line-remote');
                        document.getElementById('rival-last-action').innerText = '> ÚLTIMO INTENTO: [CLASIFICADO]';
                    }
                });
        }
    });
}
</script>

@include('terminal.partials.settings-modal')
</body>
</html>
