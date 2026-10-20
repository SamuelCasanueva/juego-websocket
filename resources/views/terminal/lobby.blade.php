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
    <title>ROBCO INDUSTRIES TERMLINK</title>
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
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 16px;
            text-shadow: var(--term-glow);
            letter-spacing: 1px;
            user-select: none;
        }

        @media (min-height: 800px) and (min-width: 768px) {
            body {
                align-items: center;
            }
        }

        /* Pantalla CRT RobCo */
        .terminal-screen {
            width: 100%;
            max-width: 960px;
            background: var(--term-bg);
            border: 2px solid var(--term-green-dim);
            padding: 24px;
            position: relative;
            box-shadow: 0 0 30px rgba(14, 133, 28, 0.35), inset 0 0 40px rgba(0, 0, 0, 0.95);
        }

        /* Scanlines auténticos */
        .terminal-screen::before {
            content: " ";
            display: block;
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.5) 50%);
            background-size: 100% 4px;
            z-index: 10;
            pointer-events: none;
        }

        /* Viñeta sutil de tubo catódico */
        .terminal-screen::after {
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
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .term-header-title {
            font-size: 1.3rem;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .net-status {
            font-size: 0.85rem;
            color: var(--term-green);
        }

        .term-header-sub {
            font-size: 0.85rem;
            color: var(--term-green);
            opacity: 0.85;
            margin-top: 4px;
        }

        .term-divider {
            margin: 16px 0;
            border-top: 1px dashed var(--term-green-dim);
        }

        /* Sección de comandos y paneles */
        .term-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .term-grid {
                grid-template-columns: 1fr;
            }
        }

        .term-panel {
            border: 1px solid var(--term-green-dim);
            background: rgba(5, 51, 10, 0.15);
            padding: 16px;
        }

        .term-panel-header {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 12px;
            border-bottom: 1px dashed var(--term-green-dim);
            padding-bottom: 4px;
        }

        .term-field {
            margin-bottom: 14px;
        }

        .term-label {
            display: block;
            font-size: 0.85rem;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .term-input {
            width: 100%;
            background: #000000;
            border: 1px solid var(--term-green);
            color: var(--term-green);
            font-family: inherit;
            font-size: 0.95rem;
            padding: 8px 10px;
            outline: none;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .term-input:focus {
            box-shadow: 0 0 8px rgba(27, 248, 53, 0.8);
            background: #021203;
        }

        .term-btn {
            display: block;
            width: 100%;
            background: transparent;
            color: var(--term-green);
            border: 1px solid var(--term-green);
            padding: 10px;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 1px;
            transition: none;
        }

        .term-btn:hover {
            background: var(--term-green);
            color: #000000;
            text-shadow: none;
        }

        /* Lista de terminales activas */
        .term-rooms-section {
            border: 1px solid var(--term-green-dim);
            background: rgba(5, 51, 10, 0.1);
            padding: 16px;
        }

        .term-rooms-title {
            font-size: 0.95rem;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            border-bottom: 1px dashed var(--term-green-dim);
            padding-bottom: 4px;
        }

        .term-rooms-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 200px;
            overflow-y: auto;
        }

        .term-rooms-list::-webkit-scrollbar {
            width: 6px;
        }

        .term-rooms-list::-webkit-scrollbar-thumb {
            background: var(--term-green-dim);
        }

        .room-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--term-green-dark);
            padding: 8px 12px;
            background: rgba(0, 0, 0, 0.6);
            font-size: 0.85rem;
        }

        .room-row:hover {
            border-color: var(--term-green-dim);
        }

        .room-row-new {
            background: var(--term-green-dark);
            border-color: var(--term-green);
        }

        .room-link-btn {
            background: transparent;
            border: 1px solid var(--term-green);
            color: var(--term-green);
            padding: 4px 10px;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: bold;
            cursor: pointer;
        }

        .room-link-btn:hover {
            background: var(--term-green);
            color: #000000;
            text-shadow: none;
        }

        .term-footer {
            margin-top: 20px;
            border-top: 1px solid var(--term-green-dim);
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            opacity: 0.75;
        }

        .cursor-block {
            display: inline-block;
            width: 8px;
            height: 14px;
            background: var(--term-green);
            vertical-align: middle;
            animation: blink 0.9s step-end infinite;
        }

        @keyframes blink {
            50% { opacity: 0; }
        }

        /* Panel de Identificador de Operador RobCo */
        .term-operator-box {
            border: 1px solid var(--term-green);
            background: rgba(5, 51, 10, 0.25);
            padding: 14px 16px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(14, 133, 28, 0.2);
        }

        .term-operator-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.95rem;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px dashed var(--term-green-dim);
            padding-bottom: 6px;
        }

        .term-operator-form {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .term-operator-input-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .term-operator-prefix {
            font-size: 0.9rem;
            font-weight: bold;
            white-space: nowrap;
        }

        .term-btn-compact {
            width: auto;
            white-space: nowrap;
            padding: 8px 16px;
        }

        .term-operator-subtext {
            font-size: 0.75rem;
            opacity: 0.75;
            margin-top: 4px;
        }

        .tag-status {
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        @media (max-width: 600px) {
            .term-operator-input-row {
                flex-direction: column;
                align-items: stretch;
            }
        }

        /* Modal RobCo Auténtico */
        .robco-modal-backdrop {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.88);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .robco-modal-dialog {
            background: var(--term-bg);
            border: 2px solid var(--term-green);
            padding: 24px;
            max-width: 520px;
            width: 100%;
            box-shadow: 0 0 35px rgba(27, 248, 53, 0.45), inset 0 0 30px rgba(0, 0, 0, 0.95);
            color: var(--term-green);
            font-family: inherit;
        }

        .robco-modal-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: var(--term-green);
            border-bottom: 1px dashed var(--term-green-dim);
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .robco-modal-body {
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .robco-modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 18px;
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
            animation: blink 0.8s step-end infinite;
        }

        /* ── THEME SWITCHER ──────────────────────────────────────────── */
        .theme-switcher {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .theme-btn {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.3);
            cursor: pointer;
            transition: transform 0.15s, border-color 0.15s;
        }
        .theme-btn:hover { transform: scale(1.25); }
        .theme-btn.active { border-color: #ffffff; transform: scale(1.2); }
        .theme-btn[data-t="green"]  { background: #1bf835; box-shadow: 0 0 6px #1bf835; }
        .theme-btn[data-t="amber"]  { background: #f5a623; box-shadow: 0 0 6px #f5a623; }
        .theme-btn[data-t="blue"]   { background: #7dd3fc; box-shadow: 0 0 6px #7dd3fc; }

        /* ── CONFIGURACIÓN DE MISIÓN ─────────────────────────────────── */
        .mission-config {
            margin-top: 14px;
            border: 1px dashed var(--term-green-dim);
            background: rgba(0,0,0,0.3);
        }
        .mission-config summary {
            padding: 8px 12px;
            cursor: pointer;
            font-size: 0.82rem;
            list-style: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            user-select: none;
            letter-spacing: 1px;
        }
        .mission-config summary::-webkit-details-marker { display: none; }
        .mission-config summary:hover { background: rgba(27,248,53,0.06); }
        .mission-config[open] summary::after { content: '[-]'; }
        .mission-config:not([open]) summary::after { content: '[+]'; }
        .mission-config-body {
            padding: 12px;
            border-top: 1px dashed var(--term-green-dark);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .cfg-row {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .cfg-label {
            font-size: 0.75rem;
            opacity: 0.85;
            letter-spacing: 1px;
        }
        .cfg-options {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .cfg-opt {
            background: transparent;
            border: 1px solid var(--term-green-dim);
            color: var(--term-green);
            font-family: inherit;
            font-size: 0.78rem;
            padding: 4px 10px;
            cursor: pointer;
            letter-spacing: 1px;
            transition: background 0.1s;
        }
        .cfg-opt.selected,
        .cfg-opt:hover {
            background: var(--term-green);
            color: #000;
            border-color: var(--term-green);
            text-shadow: none;
        }
        /* hidden radio inputs that drive the cfg-opt buttons */
        .cfg-radio { display: none; }

        /* ── HOLOCINTA DE LOGROS & PERKS ────────────────────────────── */
        .badge-perk {
            background: rgba(27, 248, 53, 0.12);
            border: 1px solid var(--term-green);
            padding: 2px 7px;
            font-size: 0.72rem;
            color: var(--term-green);
            display: inline-flex;
            align-items: center;
            gap: 4px;
            letter-spacing: 0.5px;
        }
        .achievements-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 10px;
            margin-top: 14px;
            max-height: 280px;
            overflow-y: auto;
            padding-right: 4px;
        }
        .achievement-card {
            border: 1px solid var(--term-green-dark);
            background: rgba(0, 0, 0, 0.6);
            padding: 8px 10px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            position: relative;
        }
        .achievement-card.unlocked {
            border-color: var(--term-green);
            background: rgba(27, 248, 53, 0.06);
        }
        .achievement-card.equipped {
            border-color: var(--term-amber);
            box-shadow: 0 0 8px rgba(245, 166, 35, 0.35);
        }
        .achievement-title {
            font-size: 0.85rem;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
        }
        .achievement-desc {
            font-size: 0.72rem;
            opacity: 0.8;
            line-height: 1.3;
        }
        .achievement-btn {
            margin-top: 6px;
            padding: 4px 8px;
            font-size: 0.7rem;
            background: transparent;
            border: 1px solid var(--term-green);
            color: var(--term-green);
            cursor: pointer;
            text-transform: uppercase;
            font-family: inherit;
        }
        .achievement-btn:hover {
            background: var(--term-green);
            color: #000;
        }
        .stats-summary-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 10px;
            background: rgba(5, 51, 10, 0.25);
            border: 1px dashed var(--term-green-dim);
            padding: 8px;
            text-align: center;
        }
        .stat-item-num {
            font-size: 1.15rem;
            font-weight: bold;
            color: var(--term-green);
        }
        .stat-item-lbl {
            font-size: 0.68rem;
            opacity: 0.8;
        }

        /* ── HUB DE TRANSMISIÓN GLOBAL (CHAT) ────────────────────────── */
        .term-chat-wrapper {
            background: rgba(0, 0, 0, 0.45);
            border: 1px solid var(--term-green-dark);
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .term-chat-feed {
            height: 190px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding-right: 6px;
            scrollbar-width: thin;
            scrollbar-color: var(--term-green-dim) rgba(0, 0, 0, 0.3);
        }
        .term-chat-feed::-webkit-scrollbar {
            width: 6px;
        }
        .term-chat-feed::-webkit-scrollbar-thumb {
            background: var(--term-green-dim);
        }
        .chat-msg {
            font-size: 0.85rem;
            line-height: 1.35;
            word-break: break-word;
            padding: 2px 6px;
            border-left: 2px solid transparent;
            transition: background 0.15s;
        }
        .chat-msg:hover {
            background: rgba(27, 248, 53, 0.05);
        }
        .chat-msg-own {
            border-left-color: var(--term-green);
            background: rgba(27, 248, 53, 0.04);
        }
        .chat-time {
            font-size: 0.72rem;
            opacity: 0.65;
            margin-right: 4px;
        }
        .chat-author {
            color: var(--term-amber);
            font-weight: bold;
            margin-right: 6px;
            letter-spacing: 0.5px;
        }
        .chat-msg-own .chat-author {
            color: var(--term-green);
        }
        .chat-content {
            color: var(--term-green);
        }
        .chat-empty {
            font-size: 0.82rem;
            opacity: 0.75;
            padding: 16px;
            text-align: center;
        }
        .term-chat-form {
            display: flex;
            align-items: center;
            gap: 8px;
            border-top: 1px dashed var(--term-green-dark);
            padding-top: 8px;
        }
        .term-chat-prefix {
            font-size: 0.85rem;
            font-weight: bold;
            opacity: 0.9;
        }
        .term-chat-input {
            flex: 1;
            font-size: 0.85rem;
            padding: 6px 10px;
        }

        /* ── RESPONSIVE ADAPTATIONS (MOBILE & TABLET) ── */
        @media (max-width: 768px) {
            body {
                padding: 8px;
            }
            .terminal-screen {
                padding: 16px 12px;
                border-width: 1.5px;
            }
            .term-header-title {
                font-size: 1.05rem;
            }
            .theme-switcher {
                gap: 6px;
            }
            .theme-btn {
                width: 16px;
                height: 16px;
            }
            .net-status {
                font-size: 0.75rem;
            }
            .term-header-sub {
                font-size: 0.75rem;
                line-height: 1.35;
            }
            .term-operator-box {
                padding: 12px;
            }
            .term-operator-header {
                flex-wrap: wrap;
                gap: 6px;
                font-size: 0.85rem;
            }
            .term-rooms-section {
                padding: 12px;
            }
            .term-rooms-title {
                font-size: 0.85rem;
                flex-wrap: wrap;
                gap: 4px;
            }
            .room-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
                padding: 8px 10px;
            }
            .room-row > div {
                word-break: break-word;
                line-height: 1.35;
                font-size: 0.8rem;
            }
            .room-link-btn {
                align-self: flex-end;
                padding: 5px 12px;
                font-size: 0.78rem;
            }
            .term-chat-feed {
                height: 160px;
            }
            .chat-msg {
                font-size: 0.8rem;
            }
            .term-chat-form {
                flex-wrap: wrap;
                gap: 6px;
            }
            .term-chat-prefix {
                display: none;
            }
            .term-chat-input {
                width: 100%;
                flex: 1 1 100%;
            }
            #btnSendLobbyChat {
                width: 100%;
                padding: 8px;
            }
            .term-footer {
                flex-direction: column;
                gap: 6px;
                text-align: center;
                font-size: 0.75rem;
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
            .terminal-screen {
                padding: 12px 8px;
            }
            .term-header-title {
                font-size: 0.95rem;
            }
            .term-operator-input-row {
                flex-direction: column;
                align-items: stretch;
            }
            .term-btn-compact {
                width: 100%;
                text-align: center;
            }
            .cfg-options {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 4px;
            }
            .cfg-opt {
                text-align: center;
                padding: 6px 4px;
                font-size: 0.72rem;
            }
        }
    </style>
</head>
<body>

<!-- Boot Overlay RobCo BIOS -->
<div id="boot-overlay">
    <div id="boot-text"></div><span id="boot-cursor"></span>
</div>

<div class="terminal-screen">
    <!-- Header RobCo -->
    <div class="term-header">
        <div class="term-header-title">
            <span>ROBCO INDUSTRIES (TM) TERMLINK PROTOCOL</span>
            <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
                <!-- Selector de Tema Pip-Boy -->
                <div class="theme-switcher" title="TEMA DE PANTALLA PIP-BOY">
                    <span style="font-size:0.75rem; opacity:0.8;">[ PIP-BOY:</span>
                    <button class="theme-btn active" data-t="green"  onclick="applyTheme('green')"></button>
                    <button class="theme-btn"        data-t="amber"  onclick="applyTheme('amber')"></button>
                    <button class="theme-btn"        data-t="blue"   onclick="applyTheme('blue')"></button>
                    <span style="font-size:0.75rem; opacity:0.8;">]</span>
                </div>
                <button type="button" class="settings-trigger-btn" onclick="window.toggleRobcoSettings(true)" title="Ajustes de visualización (Filtro CRT, Contraste, Tamaño)">
                    ⚙ [AJUSTES]
                </button>
                <span class="net-status robco-ws-badge" onclick="window.configureRobcoWsHost()" style="cursor:pointer;" title="Clic para configurar URL de WebSocket / Reverb">[ENLACE: DETECTANDO...]</span>
            </div>
        </div>
        <div class="term-header-sub">
            SISTEMA OPERATIVO UNIFICADO ROBCO // COPYRIGHT 2075-2077 ROBCO INDUSTRIES<br>
            TERMINAL MAESTRA DE SEGURIDAD // SUBRED LOCAL N224
        </div>
    </div>

    <!-- [0] Identificador de Operador RobCo -->
    <div class="term-operator-box">
        <div class="term-operator-header">
            <span>> [0] IDENTIFICADOR DE OPERADOR // CREDENCIAL ACTIVA</span>
            <span id="operatorStatusTag" class="tag-status">[ESTADO: SIN REGISTRAR]</span>
        </div>
        <div class="term-operator-form">
            <div class="term-operator-input-row">
                <span class="term-operator-prefix">OPERADOR:</span>
                <input type="text" id="globalOperatorId" class="term-input" 
                       value="{{ $operatorName }}" 
                       placeholder="EJ: SUPERVISOR-101 / PIP-BOY / COURIER-6" 
                       maxlength="24" autocomplete="off" spellcheck="false">
                <button type="button" class="term-btn term-btn-compact" onclick="saveOperatorId(true)">
                    > [REGISTRAR IDENTIFICADOR]
                </button>
            </div>
            <div class="term-operator-subtext" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-top:8px;">
                <span>* TU IDENTIFICADOR SE USARÁ AUTOMÁTICAMENTE AL INICIALIZAR BÓVEDAS O ENLAZAR TERMINALES.</span>
                <div style="display:flex; gap:10px; align-items:center;">
                    <span id="operatorActiveTitleBadge" class="badge-perk" style="color:var(--term-amber); border-color:var(--term-amber);">[TÍTULO: NOVATO DEL YERMO]</span>
                    <button type="button" class="term-btn term-btn-compact" style="font-size:0.75rem; padding:4px 10px;" onclick="openAchievementsModal()">
                        > 🏆 [HOLOCINTA DE LOGROS]
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Paneles de Entrada RobCo -->
    <div class="term-grid">
        <!-- Panel 1: Inicializar Terminal Local -->
        <div class="term-panel">
            <div class="term-panel-header">> [1] INICIALIZAR TERMINAL LOCAL</div>
            <form action="{{ route('terminal.create') }}" method="POST" onsubmit="handleCreate(event)">
                @csrf
                <div class="term-field">
                    <label class="term-label">IDENTIFICADOR DE OPERADOR:</label>
                    <input type="text" class="term-input" id="p1_name" name="player_name" value="{{ $operatorName }}" placeholder="SUPERVISOR-101" maxlength="24" autocomplete="off" spellcheck="false">
                </div>

                <!-- ── CONFIGURACIÓN DE MISIÓN ─────────────────────────── -->
                <details class="mission-config" id="missionConfig">
                    <summary>⚙ CONFIGURACIÓN DE MISIÓN</summary>
                    <div class="mission-config-body">

                        <!-- Modo de juego -->
                        <div class="cfg-row">
                            <span class="cfg-label">> PROTOCOLO DE SEGURIDAD:</span>
                            <div class="cfg-options">
                                <input class="cfg-radio" type="radio" name="mode" id="mode_competitive" value="competitive" checked>
                                <label class="cfg-opt selected" for="mode_competitive" onclick="selectCfg(this)">COMPETITIVO</label>

                                <input class="cfg-radio" type="radio" name="mode" id="mode_cooperative" value="cooperative">
                                <label class="cfg-opt" for="mode_cooperative" onclick="selectCfg(this)">COOPERATIVO</label>

                                <input class="cfg-radio" type="radio" name="mode" id="mode_timed" value="timed">
                                <label class="cfg-opt" for="mode_timed" onclick="selectCfg(this)">CONTRARRELOJ</label>
                            </div>
                        </div>

                        <!-- Intentos -->
                        <div class="cfg-row">
                            <span class="cfg-label">> INTENTOS POR OPERADOR:</span>
                            <div class="cfg-options">
                                <input class="cfg-radio" type="radio" name="attempts" id="att_2" value="2">
                                <label class="cfg-opt" for="att_2" onclick="selectCfg(this)">2 INTENTOS</label>

                                <input class="cfg-radio" type="radio" name="attempts" id="att_3" value="3">
                                <label class="cfg-opt" for="att_3" onclick="selectCfg(this)">3 INTENTOS</label>

                                <input class="cfg-radio" type="radio" name="attempts" id="att_4" value="4" checked>
                                <label class="cfg-opt selected" for="att_4" onclick="selectCfg(this)">4 INTENTOS</label>

                                <input class="cfg-radio" type="radio" name="attempts" id="att_6" value="6">
                                <label class="cfg-opt" for="att_6" onclick="selectCfg(this)">6 INTENTOS</label>
                            </div>
                        </div>

                        <!-- Límite de tiempo (solo visible en modo timed) -->
                        <div class="cfg-row" id="timeLimitRow" style="display:none">
                            <span class="cfg-label">> LÍMITE DE TIEMPO:</span>
                            <div class="cfg-options">
                                <input class="cfg-radio" type="radio" name="time_limit" id="tl_30" value="30" checked>
                                <label class="cfg-opt selected" for="tl_30" onclick="selectCfg(this)">30 SEG</label>

                                <input class="cfg-radio" type="radio" name="time_limit" id="tl_60" value="60">
                                <label class="cfg-opt" for="tl_60" onclick="selectCfg(this)">60 SEG</label>

                                <input class="cfg-radio" type="radio" name="time_limit" id="tl_90" value="90">
                                <label class="cfg-opt" for="tl_90" onclick="selectCfg(this)">90 SEG</label>
                            </div>
                        </div>

                        <!-- Pack de palabras -->
                        <div class="cfg-row">
                            <span class="cfg-label">> DICCIONARIO TEMÁTICO:</span>
                            <div class="cfg-options">
                                <input class="cfg-radio" type="radio" name="word_pack" id="pack_yermo" value="yermo" checked>
                                <label class="cfg-opt selected" for="pack_yermo" onclick="selectCfg(this)">🏚 YERMO</label>

                                <input class="cfg-radio" type="radio" name="word_pack" id="pack_robco" value="robco">
                                <label class="cfg-opt" for="pack_robco" onclick="selectCfg(this)">🤖 ROBCO</label>

                                <input class="cfg-radio" type="radio" name="word_pack" id="pack_vault" value="vault">
                                <label class="cfg-opt" for="pack_vault" onclick="selectCfg(this)">⚗️ VAULT-TEC</label>

                                <input class="cfg-radio" type="radio" name="word_pack" id="pack_vegas" value="vegas">
                                <label class="cfg-opt" for="pack_vegas" onclick="selectCfg(this)">🎰 NUEVA VEGAS</label>
                            </div>
                        </div>

                        <!-- Mostrar semejanza -->
                        <div class="cfg-row">
                            <span class="cfg-label">> DIAGNÓSTICO DE SEMEJANZA:</span>
                            <div class="cfg-options">
                                <input class="cfg-radio" type="radio" name="show_likeness" id="lik_yes" value="1" checked>
                                <label class="cfg-opt selected" for="lik_yes" onclick="selectCfg(this)">VISIBLE [X/6]</label>

                                <input class="cfg-radio" type="radio" name="show_likeness" id="lik_no" value="0">
                                <label class="cfg-opt" for="lik_no" onclick="selectCfg(this)">OCULTO [???]</label>
                            </div>
                        </div>

                        <!-- Perk S.P.E.C.I.A.L. -->
                        <div class="cfg-row">
                            <span class="cfg-label">> PERK S.P.E.C.I.A.L. ACTIVO:</span>
                            <div class="cfg-options">
                                <input class="cfg-radio" type="radio" name="perk" id="perk_science" value="science" checked>
                                <label class="cfg-opt selected" for="perk_science" onclick="selectCfg(this)">🧠 CIENCIA (1ª LETRA)</label>

                                <input class="cfg-radio" type="radio" name="perk" id="perk_luck" value="luck">
                                <label class="cfg-opt" for="perk_luck" onclick="selectCfg(this)">🍀 SUERTE (25% SAFE)</label>

                                <input class="cfg-radio" type="radio" name="perk" id="perk_percep" value="perception">
                                <label class="cfg-opt" for="perk_percep" onclick="selectCfg(this)">👁️ PERCEPCIÓN</label>

                                <input class="cfg-radio" type="radio" name="perk" id="perk_inject" value="injection">
                                <label class="cfg-opt" for="perk_inject" onclick="selectCfg(this)">⚡ INYECCIÓN (1x)</label>
                            </div>
                        </div>

                    </div>
                </details>

                <button type="submit" class="term-btn" style="margin-top:14px" onclick="if(window.robcoAudio) window.robcoAudio.playKeyClick()">
                    > ASIGNAR Y BLOQUEAR BÓVEDA
                </button>
            </form>
        </div>

        <!-- Panel 2: Enlazar Nodo Remoto -->
        <div class="term-panel">
            <div class="term-panel-header">> [2] ENLACE A NODO REMOTO</div>
            <form id="joinForm" onsubmit="handleJoin(event)">
                @csrf
                <div class="term-field">
                    <label class="term-label">CÓDIGO DE FRECUENCIA BÓVEDA:</label>
                    <input type="text" class="term-input" id="room_code" placeholder="V-XXXX" required autocomplete="off" spellcheck="false">
                </div>
                <div class="term-field">
                    <label class="term-label">ALIAS DE INTRUSIÓN:</label>
                    <input type="text" class="term-input" id="p2_name" value="{{ $operatorName }}" placeholder="INFILTRADO" maxlength="24" autocomplete="off" spellcheck="false">
                </div>
                <div class="term-field">
                    <label class="term-label">> TU PERK S.P.E.C.I.A.L. DE INTRUSIÓN:</label>
                    <div class="cfg-options">
                        <input class="cfg-radio" type="radio" name="perk_join" id="p2_perk_science" value="science" checked>
                        <label class="cfg-opt selected" for="p2_perk_science" onclick="selectCfg(this)">🧠 CIENCIA</label>

                        <input class="cfg-radio" type="radio" name="perk_join" id="p2_perk_luck" value="luck">
                        <label class="cfg-opt" for="p2_perk_luck" onclick="selectCfg(this)">🍀 SUERTE</label>

                        <input class="cfg-radio" type="radio" name="perk_join" id="p2_perk_percep" value="perception">
                        <label class="cfg-opt" for="p2_perk_percep" onclick="selectCfg(this)">👁️ PERCEPCIÓN</label>

                        <input class="cfg-radio" type="radio" name="perk_join" id="p2_perk_inject" value="injection">
                        <label class="cfg-opt" for="p2_perk_inject" onclick="selectCfg(this)">⚡ INYECCIÓN</label>
                    </div>
                </div>
                <button type="submit" class="term-btn" onclick="if(window.robcoAudio) window.robcoAudio.playKeyClick()">
                    > FORZAR CONEXIÓN REMOTA
                </button>
            </form>
        </div>

        <!-- Panel 3: Simulación en Solitario vs IA ZAX -->
        <div class="term-panel" style="border-color: var(--term-amber); background: rgba(30, 20, 0, 0.25);">
            <div class="term-panel-header" style="color: var(--term-amber); border-color: var(--term-amber);">
                > [3] SIMULACIÓN VS IA ROBCO (ZAX)
            </div>
            <form action="{{ route('terminal.create.ai') }}" method="POST" onsubmit="handleCreateAi(event)">
                @csrf
                <div class="term-field">
                    <label class="term-label" style="color: var(--term-amber);">OPERADOR EN SIMULACIÓN:</label>
                    <input type="text" class="term-input" id="ai_player_name" name="player_name" value="{{ $operatorName }}" placeholder="SUPERVISOR-101" maxlength="24" autocomplete="off" spellcheck="false" style="border-color: var(--term-amber); color: var(--term-amber);">
                </div>

                <div class="term-field">
                    <label class="term-label">> DIFICULTAD DE MAINFRAME ZAX:</label>
                    <div class="cfg-options">
                        <input class="cfg-radio" type="radio" name="ai_difficulty" id="ai_easy" value="easy">
                        <label class="cfg-opt" for="ai_easy" onclick="selectCfg(this)">NOVATO [LENTO]</label>

                        <input class="cfg-radio" type="radio" name="ai_difficulty" id="ai_normal" value="normal" checked>
                        <label class="cfg-opt selected" for="ai_normal" onclick="selectCfg(this)">CENTINELA [ESTÁNDAR]</label>

                        <input class="cfg-radio" type="radio" name="ai_difficulty" id="ai_hard" value="hard">
                        <label class="cfg-opt" for="ai_hard" onclick="selectCfg(this)">SUPERVISOR [RÁPIDO]</label>
                    </div>
                </div>

                <div class="term-field">
                    <label class="term-label">> PERK S.P.E.C.I.A.L. ACTIVO:</label>
                    <div class="cfg-options">
                        <input class="cfg-radio" type="radio" name="perk" id="perk_science_ai" value="science" checked>
                        <label class="cfg-opt selected" for="perk_science_ai" onclick="selectCfg(this)">🧠 CIENCIA (1ª LETRA)</label>

                        <input class="cfg-radio" type="radio" name="perk" id="perk_luck_ai" value="luck">
                        <label class="cfg-opt" for="perk_luck_ai" onclick="selectCfg(this)">🍀 SUERTE (25% SAFE)</label>

                        <input class="cfg-radio" type="radio" name="perk" id="perk_percep_ai" value="perception">
                        <label class="cfg-opt" for="perk_percep_ai" onclick="selectCfg(this)">👁️ PERCEPCIÓN</label>

                        <input class="cfg-radio" type="radio" name="perk" id="perk_inject_ai" value="injection">
                        <label class="cfg-opt" for="perk_inject_ai" onclick="selectCfg(this)">⚡ INYECCIÓN (1x)</label>
                    </div>
                </div>

                <button type="submit" class="term-btn" style="border-color: var(--term-amber); color: var(--term-amber); margin-top: 10px;" onclick="if(window.robcoAudio) window.robcoAudio.playKeyClick()">
                    > INICIAR SIMULACIÓN CONTRA ZAX
                </button>
            </form>
        </div>
    </div>

    <!-- Lista de Terminales Detectadas en Frecuencia -->
    <div class="term-rooms-section">
        <div class="term-rooms-title" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <span>> [3] REGISTRO DE TERMINALES ACTIVAS EN LA SUBRED</span>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span id="roomsCount">[{{ count($recentRooms) }} DETECTADAS]</span>
                <button type="button" class="term-btn term-btn-compact" id="btnPurgeRooms" onclick="handlePurgeRooms()" style="font-size: 0.72rem; padding: 3px 8px; border-color: var(--term-amber); color: var(--term-amber);" title="Purgar terminales inactivas o abandonadas de la subred">
                    ⚡ [PURGAR SUBRED]
                </button>
            </div>
        </div>
        <div class="term-rooms-list" id="roomsContainer">
            @forelse($recentRooms as $room)
                <div class="room-row" id="room-card-{{ $room->code }}">
                    <div>
                        <span>[{{ $room->code }}]</span>
                        <span> · OPERADOR: {{ strtoupper($room->player1_name) }}</span>
                        <span> · ESTADO: <span id="status-{{ $room->code }}">{{ strtoupper($room->status) }}</span></span>
                    </div>
                    <button type="button" class="room-link-btn" onclick="joinRoomPrompt('{{ $room->code }}')">
                        > ENLAZAR
                    </button>
                </div>
            @empty
                <div id="noRoomsMsg" style="padding: 10px; font-size: 0.85rem; opacity: 0.8;">
                    NO SE DETECTAN TERMINALES EN ESTA FRECUENCIA. INICIALIZA UNA TERMINAL LOCAL.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Salón de la Fama / Leaderboard de Hackeos -->
    <div class="term-rooms-section" style="margin-top: 16px;">
        <div class="term-rooms-title">
            <span>> [4] SALÓN DE LA FAMA // REGISTRO DE INTRUSIONES EXITOSAS</span>
            <span id="leaderboardCount">[{{ count($leaderboard) }} REGISTRADOS]</span>
        </div>
        <div class="term-rooms-list" id="leaderboardContainer">
            @forelse($leaderboard as $lb)
                <div class="room-row">
                    <div>
                        <span>[{{ $lb->code }}]</span>
                        <span> · OPERADOR: <strong>{{ strtoupper($lb->winner_name) }}</strong></span>
                        <span> · CLAVE: [{{ $lb->secret_word }}]</span>
                        <span> · INTENTOS USADOS: {{ $lb->attempts_used ?? 1 }}/4</span>
                    </div>
                    <span style="font-size: 0.75rem; opacity: 0.8;">{{ $lb->updated_at->format('H:i') }}</span>
                </div>
            @empty
                <div id="noLeaderboardMsg" style="padding: 10px; font-size: 0.85rem; opacity: 0.8;">
                    NO SE REGISTRAN BÓVEDAS DESCIFRADAS AÚN. ¡SÉ EL PRIMER HACKER DEL YERMO!
                </div>
            @endforelse
        </div>
    </div>

    <!-- [5] Subred de Transmisión Global // Hub RobCo -->
    <div class="term-rooms-section" style="margin-top: 16px;">
        <div class="term-rooms-title">
            <span>> [5] FRECUENCIA ABIERTA // SUBRED N224 (HUB DE TRANSMISIÓN GLOBAL)</span>
            <span id="chatStatusTag" style="font-size: 0.78rem;">[CANAL SINTONIZADO]</span>
        </div>
        <div class="term-chat-wrapper">
            <div class="term-chat-feed" id="lobbyChatFeed">
                @forelse($globalChat as $msg)
                    <div class="chat-msg {{ $msg->player_token === $token ? 'chat-msg-own' : '' }}">
                        <span class="chat-time">[{{ $msg->created_at->format('H:i') }}]</span>
                        <span class="chat-author">&lt;{{ strtoupper($msg->player_name) }}&gt;:</span>
                        <span class="chat-content">{{ e($msg->message) }}</span>
                    </div>
                @empty
                    <div id="noLobbyChatMsg" class="chat-empty">
                        > SILENCIO EN LA SUBRED. SÉ EL PRIMERO EN TRANSMITIR POR ESTA FRECUENCIA.
                    </div>
                @endforelse
            </div>
            <form id="lobbyChatForm" onsubmit="handleSendLobbyChat(event)" class="term-chat-form">
                @csrf
                <span class="term-chat-prefix">> TX:</span>
                <input type="text" id="lobbyChatInput" class="term-input term-chat-input" 
                       placeholder="ESCRIBE UN MENSAJE PARA TODOS LOS OPERADORES EN LA SUBRED..." 
                       maxlength="240" autocomplete="off" spellcheck="false">
                <button type="submit" class="term-btn term-btn-compact" id="btnSendLobbyChat">
                    > TRANSMITIR [ENTER]
                </button>
            </form>
        </div>
    </div>

    <!-- Footer RobCo -->
    <div class="term-footer">
        <div>ROBCO PROTOCOL V4.0.12 // LISTO <span class="cursor-block"></span></div>
        <div>MEMORIA: 64KB OK // SISTEMA PROTEGIDO</div>
    </div>
</div>

<!-- Modal Auténtico RobCo para Identificación Requerida -->
<div id="operatorModal" class="robco-modal-backdrop" style="display: none;">
    <div class="robco-modal-dialog">
        <div class="robco-modal-title">
            [!] ALERTA DEL PROTOCOLO DE SEGURIDAD ROBCO U-238
        </div>
        <div class="robco-modal-body">
            SE REQUIERE UN IDENTIFICADOR DE OPERADOR PARA ENLAZAR LA TERMINAL [<span id="modalRoomTarget" style="font-weight: bold; color: #fff;"></span>].<br><br>
            NO SE PERMITEN INTRUSIONES ANÓNIMAS EN LA SUBRED N224.
        </div>
        <div class="term-field" style="margin-top: 14px;">
            <label class="term-label">INGRESE SU IDENTIFICADOR DE OPERADOR:</label>
            <input type="text" id="modalOperatorInput" class="term-input" placeholder="EJ: INFILTRADO-77 / SUPERVISOR-101" maxlength="24" autocomplete="off" spellcheck="false" onkeydown="if(event.key==='Enter') confirmModalJoin()">
        </div>
        <div class="robco-modal-actions">
            <button type="button" class="term-btn" onclick="confirmModalJoin()">
                > CONFIRMAR IDENTIFICADOR Y ENLAZAR
            </button>
            <button type="button" class="term-btn" style="border-color: var(--term-green-dim); opacity: 0.8;" onclick="closeOperatorModal()">
                > CANCELAR
            </button>
        </div>
    </div>
</div>

<!-- Modal Holocinta de Logros y Servicio RobCo -->
<div id="achievementsModal" class="robco-modal-backdrop" style="display: none;">
    <div class="robco-modal-dialog" style="max-width: 650px;">
        <div class="robco-modal-title" style="display: flex; justify-content: space-between; align-items: center;">
            <span>[🏆] HOLOCINTA DE SERVICIO // LOGROS DE OPERADOR</span>
            <span style="font-size: 0.75rem; opacity: 0.8;">ROBCO MIL-SPEC V4</span>
        </div>
        
        <div class="stats-summary-row" id="statsSummaryContainer">
            <div>
                <div class="stat-item-num" id="statGamesWon">0</div>
                <div class="stat-item-lbl">BÓVEDAS HACKEADAS</div>
            </div>
            <div>
                <div class="stat-item-num" id="statZaxDefeated">0</div>
                <div class="stat-item-lbl">ZAX DERROTADOS</div>
            </div>
            <div>
                <div class="stat-item-num" id="statOneShots">0</div>
                <div class="stat-item-lbl">HACKEOS ONE-SHOT</div>
            </div>
            <div>
                <div class="stat-item-num" id="statBrackets">0</div>
                <div class="stat-item-lbl">CORCHETES USADOS</div>
            </div>
            <div>
                <div class="stat-item-num" id="statInterferences">0</div>
                <div class="stat-item-lbl">INTERFERENCIAS TX</div>
            </div>
            <div>
                <div class="stat-item-num" id="statGamesPlayed">0</div>
                <div class="stat-item-lbl">TOTAL MISIONES</div>
            </div>
        </div>

        <div style="font-size: 0.85rem; font-weight: bold; margin-top: 14px; border-bottom: 1px dashed var(--term-green-dim); padding-bottom: 4px;">
            > HOLOCINTAS DE TÍTULOS DISPONIBLES (EQUIPA TU RANGO):
        </div>

        <div class="achievements-grid" id="achievementsList">
            <!-- Renderizado dinámico desde getServiceRecord() -->
        </div>

        <div class="robco-modal-actions" style="margin-top: 16px;">
            <button type="button" class="term-btn" onclick="closeAchievementsModal()">
                > CERRAR HOLOCINTA
            </button>
        </div>
    </div>
</div>

<script>
let currentModalTargetRoom = null;

// ── Selector visual de opciones de configuración de misión ────────────────
function selectCfg(label) {
    // Deselect siblings in same cfg-options group
    const group = label.closest('.cfg-options');
    if (group) {
        group.querySelectorAll('.cfg-opt').forEach(l => l.classList.remove('selected'));
    }
    label.classList.add('selected');

    const forId = label.getAttribute('for');
    if (forId) {
        const radio = document.getElementById(forId);
        if (radio) {
            radio.checked = true;
        }
    }

    // Show/hide time limit row based on mode selection
    if (forId && forId.startsWith('mode_')) {
        const timeLimitRow = document.getElementById('timeLimitRow');
        if (timeLimitRow) {
            timeLimitRow.style.display = (forId === 'mode_timed') ? 'flex' : 'none';
        }
        // Auto-open config panel when a mode is selected
        const cfg = document.getElementById('missionConfig');
        if (cfg) cfg.open = true;
    }
}

function getStoredOperator() {
    return (localStorage.getItem('robco_operator_id') || '').trim();
}

function updateOperatorDisplay(name) {
    const input = document.getElementById('globalOperatorId');
    const p1 = document.getElementById('p1_name');
    const p2 = document.getElementById('p2_name');
    const statusTag = document.getElementById('operatorStatusTag');

    if (input && input.value !== name) input.value = name;
    if (p1) p1.value = name;
    if (p2) p2.value = name;

    if (statusTag) {
        if (name) {
            statusTag.innerText = `[CREDENCIAL ACTIVA: ${name.toUpperCase()}]`;
            statusTag.style.color = 'var(--term-green)';
        } else {
            statusTag.innerText = '[ESTADO: SIN REGISTRAR]';
            statusTag.style.color = 'var(--term-green-dim)';
        }
    }
}

function saveOperatorId(playSound = false, customName = null) {
    const val = (customName !== null ? customName : document.getElementById('globalOperatorId').value).trim();
    if (val) {
        localStorage.setItem('robco_operator_id', val);
        updateOperatorDisplay(val);

        // Actualizar sesión en segundo plano
        fetch('{{ route('terminal.operator') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ player_name: val })
        }).catch(() => {});

        if (playSound && window.robcoAudio) window.robcoAudio.playBracketBonus();
    } else {
        localStorage.removeItem('robco_operator_id');
        updateOperatorDisplay('');
        if (playSound && window.robcoAudio) window.robcoAudio.playKeyClick();
    }
}

function joinRoomPrompt(roomCode) {
    if (window.robcoAudio) window.robcoAudio.playKeyClick();

    let opName = document.getElementById('globalOperatorId').value.trim();
    if (!opName) {
        opName = getStoredOperator();
    }

    if (opName) {
        submitJoin(roomCode, opName);
    } else {
        currentModalTargetRoom = roomCode;
        document.getElementById('modalRoomTarget').innerText = roomCode;
        const modal = document.getElementById('operatorModal');
        modal.style.display = 'flex';
        const modalInput = document.getElementById('modalOperatorInput');
        modalInput.value = '';
        setTimeout(() => modalInput.focus(), 60);
    }
}

function closeOperatorModal() {
    if (window.robcoAudio) window.robcoAudio.playKeyClick();
    document.getElementById('operatorModal').style.display = 'none';
    currentModalTargetRoom = null;
}

function confirmModalJoin() {
    if (!currentModalTargetRoom) return;
    const input = document.getElementById('modalOperatorInput');
    const name = input.value.trim() || 'INFILTRADO';
    saveOperatorId(false, name);
    document.getElementById('operatorModal').style.display = 'none';
    submitJoin(currentModalTargetRoom, name);
}

function submitJoin(roomCode, opName) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/terminal/unirse/' + encodeURIComponent(roomCode);

    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);

    if (opName) {
        const nameInput = document.createElement('input');
        nameInput.type = 'hidden';
        nameInput.name = 'player_name';
        nameInput.value = opName;
        form.appendChild(nameInput);
    }

    const p2PerkChecked = document.querySelector('input[name="perk_join"]:checked') || document.querySelector('input[name="perk"]:checked');
    if (p2PerkChecked) {
        const perkInput = document.createElement('input');
        perkInput.type = 'hidden';
        perkInput.name = 'perk';
        perkInput.value = p2PerkChecked.value;
        form.appendChild(perkInput);
    }

    document.body.appendChild(form);
    form.submit();
}

function handleCreate(e) {
    const p1 = document.getElementById('p1_name');
    if (!p1.value.trim()) {
        const stored = getStoredOperator();
        if (stored) p1.value = stored;
    } else {
        saveOperatorId(false, p1.value.trim());
    }
}

function handleCreateAi(e) {
    const aiName = document.getElementById('ai_player_name');
    if (aiName && !aiName.value.trim()) {
        const stored = getStoredOperator();
        if (stored) aiName.value = stored;
    } else if (aiName) {
        saveOperatorId(false, aiName.value.trim());
    }
}

function handleJoin(e) {
    e.preventDefault();
    const code = document.getElementById('room_code').value.trim().toUpperCase();
    let name = document.getElementById('p2_name').value.trim();
    if (!code) return;

    if (!name) {
        name = getStoredOperator();
    } else {
        saveOperatorId(false, name);
    }

    submitJoin(code, name);
}

// -------------------------------------------------------------
// Sincronización en Tiempo Real del Lobby vía Reverb / Echo
// -------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    // Restaurar identificador guardado
    const stored = getStoredOperator() || '{{ $operatorName }}';
    if (stored) {
        updateOperatorDisplay(stored);
        localStorage.setItem('robco_operator_id', stored);
    }

    // Sincronización reactiva mientras se tipea
    const globalInput = document.getElementById('globalOperatorId');
    if (globalInput) {
        globalInput.addEventListener('input', (e) => {
            const val = e.target.value.trim();
            const p1 = document.getElementById('p1_name');
            const p2 = document.getElementById('p2_name');
            if (p1) p1.value = val;
            if (p2) p2.value = val;
        });

        globalInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                saveOperatorId(true);
            }
        });
    }

    if (window.Echo) {
        window.Echo.channel('robco-lobby')
            .listen('.TerminalRoomCreated', (e) => {
                const container = document.getElementById('roomsContainer');
                const emptyMsg = document.getElementById('noRoomsMsg');
                if (emptyMsg) emptyMsg.remove();

                if (document.getElementById(`room-card-${e.code}`)) return;

                const row = document.createElement('div');
                row.className = 'room-row room-row-new';
                row.id = `room-card-${e.code}`;
                row.innerHTML = `
                    <div>
                        <span>[${e.code}]</span>
                        <span> · OPERADOR: ${e.hostName.toUpperCase()}</span>
                        <span> · ESTADO: <span id="status-${e.code}">${e.status.toUpperCase()}</span></span>
                    </div>
                    <button type="button" class="room-link-btn" onclick="joinRoomPrompt('${e.code}')">
                        > ENLAZAR
                    </button>
                `;

                container.prepend(row);
                updateRoomsCount();
                if (window.robcoAudio) window.robcoAudio.playBracketBonus();
            })
            .listen('.TerminalRoomUpdated', (e) => {
                const statusEl = document.getElementById(`status-${e.code}`);
                if (statusEl) {
                    statusEl.innerText = e.status.toUpperCase();
                }
            })
            .listen('.TerminalHacked', (e) => {
                if (e.winnerNumber === 0) return; // Si ambos fueron bloqueados, no entra al salón de la fama

                const lbContainer = document.getElementById('leaderboardContainer');
                const emptyLb = document.getElementById('noLeaderboardMsg');
                if (emptyLb) emptyLb.remove();

                const row = document.createElement('div');
                row.className = 'room-row room-row-new';
                row.innerHTML = `
                    <div>
                        <span>[${e.roomCode}]</span>
                        <span> · OPERADOR: <strong>${e.winnerName.toUpperCase()}</strong></span>
                        <span> · CLAVE: [${e.secretWord}]</span>
                        <span> · INTENTOS USADOS: ${e.attemptsUsed || 1}/4</span>
                    </div>
                    <span style="font-size: 0.75rem; opacity: 0.8;">${e.date || 'AHORA'}</span>
                `;

                lbContainer.prepend(row);
                if (window.robcoAudio) window.robcoAudio.playAccessGranted();
            })
            .listen('.TerminalRoomsPurged', (e) => {
                const container = document.getElementById('roomsContainer');
                if (container) {
                    container.innerHTML = `
                        <div id="noRoomsMsg" style="opacity: 0.6; padding: 16px 0; font-size: 0.85rem; text-align: center;">
                            > NO HAY TERMINALES ACTIVAS EN LA SUBRED. INICIALIZA UNA NUEVA SALA.
                        </div>
                    `;
                }
                updateRoomsCount();
                appendLobbyChatMessage({
                    playerName: 'SISTEMA ROBCO',
                    message: `⚡ [MANTENIMIENTO]: ${e.purgedCount} terminal(es) purgada(s) de la subred en tiempo real.`,
                    playerToken: 'SYS',
                    title: '[PROTOCOLO U-238]',
                    time: new Date().toLocaleTimeString().slice(0, 5)
                });
                if (window.robcoAudio) window.robcoAudio.playBracketBonus();
            })
            .listen('.LobbyChatCleared', (e) => clearLobbyChatFeed(e))
            .listen('LobbyChatCleared', (e) => clearLobbyChatFeed(e));

        // ── CHAT GLOBAL EN LOBBY (REVERB) ──────────────────────────────────
        window.Echo.channel('robco-lobby-chat')
            .listen('.LobbyChatMessage', (e) => {
                appendLobbyChatMessage(e);
            })
            .listen('.LobbyChatCleared', (e) => clearLobbyChatFeed(e))
            .listen('LobbyChatCleared', (e) => clearLobbyChatFeed(e));
    }

    // Scroll inicial al final del feed del chat
    scrollLobbyChatToBottom();
});

function clearLobbyChatFeed(e) {
    const feed = document.getElementById('lobbyChatFeed');
    if (feed) {
        feed.innerHTML = `
            <div class="chat-empty" id="noLobbyChatMsg">
                > CANAL DE SUBRED PURGADO // FRECUENCIA LIMPIA.
            </div>
        `;
    }
    if (window.robcoAudio) window.robcoAudio.playBracketBonus();
}

function updateRoomsCount() {
    const rows = document.querySelectorAll('#roomsContainer .room-row');
    const countEl = document.getElementById('roomsCount');
    if (countEl) countEl.innerText = `[${rows.length} DETECTADAS]`;
}

async function handlePurgeRooms() {
    if (window.robcoAudio) window.robcoAudio.playKeyClick();
    if (!confirm('¿EJECUTAR PROTOCOLO DE PURGA? Se eliminarán terminales inactivas o abandonadas de la subred.')) {
        return;
    }
    const btn = document.getElementById('btnPurgeRooms');
    if (btn) {
        btn.disabled = true;
        btn.innerText = '⚡ [PURGANDO...]';
    }
    try {
        const res = await fetch('{{ route('terminal.rooms.purge') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ minutes: 15 })
        });
        const data = await res.json();
        if (data.success) {
            if (window.robcoAudio) window.robcoAudio.playBracketBonus();
            alert(`> ${data.message}`);
            window.location.reload();
        } else {
            alert(data.message || 'Error al purgar subred.');
        }
    } catch (err) {
        console.error('Error al purgar salas:', err);
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.innerText = '⚡ [PURGAR SUBRED]';
        }
    }
}

// ── MANEJO DEL CHAT GLOBAL ───────────────────────────────────────────────
const MY_PLAYER_TOKEN = '{{ $token }}';

function scrollLobbyChatToBottom() {
    const feed = document.getElementById('lobbyChatFeed');
    if (feed) {
        feed.scrollTop = feed.scrollHeight;
    }
}

function appendLobbyChatMessage(msgData) {
    const feed = document.getElementById('lobbyChatFeed');
    if (!feed) return;

    // Deduplicación para no mostrar dos veces el mismo mensaje
    if (msgData.id && feed.querySelector(`[data-chat-id="${msgData.id}"]`)) {
        return;
    }

    const emptyMsg = document.getElementById('noLobbyChatMsg');
    if (emptyMsg) emptyMsg.remove();

    const isOwn = (msgData.playerToken === MY_PLAYER_TOKEN);
    const div = document.createElement('div');
    if (msgData.id) div.dataset.chatId = msgData.id;
    div.className = `chat-msg ${isOwn ? 'chat-msg-own' : ''}`;
    div.innerHTML = `
        <span class="chat-time">[${msgData.time || 'AHORA'}]</span>
        <span class="chat-author">&lt;${(msgData.playerName || 'OPERADOR').toUpperCase()}&gt;:</span>
        <span class="chat-content"></span>
    `;
    div.querySelector('.chat-content').textContent = msgData.message;

    feed.appendChild(div);
    scrollLobbyChatToBottom();

    if (!isOwn && window.robcoAudio) {
        window.robcoAudio.playKeyClick();
    }
}

function handleSendLobbyChat(e) {
    e.preventDefault();
    const input = document.getElementById('lobbyChatInput');
    const btn = document.getElementById('btnSendLobbyChat');
    if (!input) return;

    const message = input.value.trim();
    if (!message) return;

    // Si aún no se registró operador, guardar lo que esté en el campo global
    const currentOp = getStoredOperator();
    if (!currentOp) {
        saveOperatorId(false);
    }

    if (btn) btn.disabled = true;

    const activeTitle = getActiveTitle();

    fetch('{{ route('terminal.chat.global') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ message: message, title: activeTitle })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success && res.data) {
            input.value = '';
            // Renderizado optimista inmediato sin necesidad de recargar la página
            appendLobbyChatMessage(res.data);
            if (window.robcoAudio) window.robcoAudio.playKeyClick();
        } else {
            alert(res.message || 'Error al transmitir mensaje.');
        }
    })
    .catch(err => {
        console.error('Error enviando mensaje al chat:', err);
    })
    .finally(() => {
        if (btn) btn.disabled = false;
        input.focus();
    });
}

// ── REGISTRO DE SERVICIO Y HOLOCINTAS DE LOGROS ───────────────────────────
const ALL_ACHIEVEMENTS = [
    { id: 'recruit', title: '[NOVATO DEL YERMO]', desc: 'Credencial básica de operador asignada por RobCo.', check: () => true },
    { id: 'first_win', title: '[INFILTRADO DE BÓVEDA]', desc: 'Hackea exitosamente tu primera terminal (PvP o IA).', check: (s) => (s.games_won || 0) >= 1 },
    { id: 'zax_slayer', title: '[DESTRUCTOR DE ZAX]', desc: 'Vence a la supercomputadora ZAX en modo solitario.', check: (s) => (s.zax_defeated || 0) >= 1 },
    { id: 'one_shot', title: '[FANTASMA // ONE-SHOT]', desc: 'Descifra la contraseña al primer intento exacto.', check: (s) => (s.one_shots || 0) >= 1 },
    { id: 'bracket_tech', title: '[TÉCNICO DE CORCHETES]', desc: 'Ejecuta al menos 10 secuencias de corchetes.', check: (s) => (s.brackets_found || 0) >= 10 },
    { id: 'cyber_raider', title: '[GUERRILLERO CIBERNÉTICO]', desc: 'Emite 3 o más ataques de interferencia electromagnética.', check: (s) => (s.interferences_sent || 0) >= 3 },
    { id: 'master_overseer', title: '[SUPERVISOR SUPREMO]', desc: 'Gana 5 o más hackeos de bóveda.', check: (s) => (s.games_won || 0) >= 5 },
    { id: 'vegas_legend', title: '[LEYENDA DE NUEVA VEGAS]', desc: 'Gana 10 partidas y derrota al menos a 3 IAs ZAX.', check: (s) => (s.games_won || 0) >= 10 && (s.zax_defeated || 0) >= 3 }
];

function getServiceRecord() {
    try {
        const raw = localStorage.getItem('robco_service_record');
        if (raw) return JSON.parse(raw);
    } catch(e) {}
    return {
        games_played: 0,
        games_won: 0,
        zax_defeated: 0,
        one_shots: 0,
        brackets_found: 0,
        interferences_sent: 0,
        active_title: '[NOVATO DEL YERMO]'
    };
}

function saveServiceRecord(rec) {
    localStorage.setItem('robco_service_record', JSON.stringify(rec));
    updateTitleDisplay();
}

function getActiveTitle() {
    return getServiceRecord().active_title || '[NOVATO DEL YERMO]';
}

function updateTitleDisplay() {
    const badge = document.getElementById('operatorActiveTitleBadge');
    if (badge) {
        badge.textContent = `[TÍTULO: ${getActiveTitle().replace(/[\[\]]/g, '')}]`;
    }
}

function openAchievementsModal() {
    if (window.robcoAudio) window.robcoAudio.playKeyClick();
    const modal = document.getElementById('achievementsModal');
    if (!modal) return;

    const stats = getServiceRecord();
    document.getElementById('statGamesWon').textContent = stats.games_won || 0;
    document.getElementById('statZaxDefeated').textContent = stats.zax_defeated || 0;
    document.getElementById('statOneShots').textContent = stats.one_shots || 0;
    document.getElementById('statBrackets').textContent = stats.brackets_found || 0;
    document.getElementById('statInterferences').textContent = stats.interferences_sent || 0;
    document.getElementById('statGamesPlayed').textContent = stats.games_played || 0;

    const container = document.getElementById('achievementsList');
    container.innerHTML = '';

    ALL_ACHIEVEMENTS.forEach(ach => {
        const isUnlocked = ach.check(stats);
        const isEquipped = stats.active_title === ach.title;

        const card = document.createElement('div');
        card.className = `achievement-card ${isUnlocked ? 'unlocked' : ''} ${isEquipped ? 'equipped' : ''}`;
        card.innerHTML = `
            <div class="achievement-title">
                <span>${ach.title}</span>
                <span style="font-size:0.7rem; color:${isEquipped ? 'var(--term-amber)' : (isUnlocked ? 'var(--term-green)' : '#888')}">
                    ${isEquipped ? '[EQUIPADO]' : (isUnlocked ? '[DESBLOQUEADO]' : '[BLOQUEADO]')}
                </span>
            </div>
            <div class="achievement-desc">${ach.desc}</div>
            ${isUnlocked && !isEquipped ? `<button type="button" class="achievement-btn" onclick="equipTitle('${ach.title}')">> EQUIPAR TÍTULO</button>` : ''}
        `;
        container.appendChild(card);
    });

    modal.style.display = 'flex';
}

function closeAchievementsModal() {
    if (window.robcoAudio) window.robcoAudio.playKeyClick();
    const modal = document.getElementById('achievementsModal');
    if (modal) modal.style.display = 'none';
}

function equipTitle(title) {
    if (window.robcoAudio) window.robcoAudio.playBracketBonus();
    const stats = getServiceRecord();
    stats.active_title = title;
    saveServiceRecord(stats);
    openAchievementsModal();
}

function handleCreateAi(e) {
    const aiNameInput = document.getElementById('ai_player_name');
    if (!aiNameInput.value.trim()) {
        const stored = getStoredOperator();
        if (stored) aiNameInput.value = stored;
    } else {
        saveOperatorId(false, aiNameInput.value.trim());
    }
}

// Inicializar badge de título al cargar
updateTitleDisplay();
</script>

<script>
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
    // Aplicar tema guardado antes del boot
    const savedTheme = localStorage.getItem('robco_theme') || 'green';
    applyTheme(savedTheme);

    const skipKey = 'robco_booted_' + window.location.pathname;
    const alreadyBooted = sessionStorage.getItem(skipKey);
    const overlay = document.getElementById('boot-overlay');
    const bootText = document.getElementById('boot-text');
    const bootCursor = document.getElementById('boot-cursor');

    if (alreadyBooted) {
        overlay.style.display = 'none';
        return;
    }

    const lines = [
        "ROBCO INDUSTRIES (TM) UNIFIED OPERATING SYSTEM",
        "COPYRIGHT 2075-2077 ROBCO INDUSTRIES",
        "",
        "INITIALIZING HARDWARE...",
        "  CPU: ROBCO PROCUTRONICS 7.5 MHz        [ OK ]",
        "  RAM: 64KB DETECTED                     [ OK ]",
        "  CRT: PHOSPHOR DISPLAY LINKED           [ OK ]",
        "  NIC: REVERB SUBNET N224 CONNECTED      [ OK ]",
        "",
        "LOADING TERMLINK PROTOCOL v4.0.12...",
        "  SECURITY MODULE: ARMED                 [ OK ]",
        "  ENCRYPTION KEY: AES-256 ACTIVE         [ OK ]",
        "  BROADCAST NODE: ROBCO-LOBBY            [ OK ]",
        "",
        "WARNING: UNAUTHORIZED ACCESS IS PUNISHABLE",
        "  UNDER ROBCO SECURITY ORDINANCE 7-B.",
        "",
        "SYSTEM READY. WELCOME, OPERATOR.",
        "__________________________________________________",
    ];

    let lineIdx = 0;
    let charIdx = 0;
    let output = '';
    const CHAR_DELAY = 14;
    const LINE_PAUSE = 110;

    function typeNext() {
        if (lineIdx >= lines.length) {
            // Boot completo
            sessionStorage.setItem(skipKey, '1');
            setTimeout(() => {
                overlay.classList.add('fade-out');
                setTimeout(() => overlay.style.display = 'none', 650);
            }, 600);
            return;
        }

        const line = lines[lineIdx];
        if (charIdx < line.length) {
            output += line[charIdx];
            bootText.textContent = output;
            charIdx++;
            setTimeout(typeNext, CHAR_DELAY);
        } else {
            output += '\n';
            bootText.textContent = output;
            lineIdx++;
            charIdx = 0;
            const pause = line === '' ? 40 : LINE_PAUSE;
            setTimeout(typeNext, pause);
        }
    }

    // Permitir saltar el boot con cualquier tecla o clic
    const skip = () => {
        sessionStorage.setItem(skipKey, '1');
        overlay.style.display = 'none';
    };
    document.addEventListener('keydown', skip, { once: true });
    overlay.addEventListener('click', skip, { once: true });

    setTimeout(typeNext, 200);
})();
</script>

@include('terminal.partials.settings-modal')
</body>
</html>
