<!DOCTYPE html>
<html lang="es" data-theme="green">
<head>
    <meta charset="UTF-8">
    <script>
        (function() {
            var params = new URLSearchParams(window.location.search);
            var theme = params.get('theme') || localStorage.getItem('robco_theme') || 'green';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROBCO SUPERVISOR // VIGILANCIA {{ $room->code }}</title>
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

        /* Theme Switcher Styles */
        .theme-switcher {
            display: flex;
            gap: 6px;
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

        .spec-screen {
            width: 100%;
            max-width: 1200px;
            background: var(--term-bg);
            border: 2px solid var(--term-green-dim);
            padding: 24px;
            position: relative;
            box-shadow: 0 0 40px rgba(14, 133, 28, 0.35), inset 0 0 60px rgba(0, 0, 0, 0.95);
        }

        .spec-screen::before {
            content: " ";
            display: block;
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.45) 50%);
            background-size: 100% 4px;
            z-index: 10;
            pointer-events: none;
        }

        .header {
            border-bottom: 1px solid var(--term-green);
            padding-bottom: 12px;
            margin-bottom: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .title {
            font-size: 1.3rem;
            font-weight: bold;
        }

        .actions {
            display: flex;
            gap: 12px;
        }

        .btn-link {
            background: transparent;
            border: 1px solid var(--term-green);
            color: var(--term-green);
            padding: 4px 10px;
            font-family: inherit;
            font-size: 0.8rem;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-link:hover {
            background: var(--term-green);
            color: #000;
            text-shadow: none;
        }

        /* Pantalla Dividida: Jugador 1 | Feed Central | Jugador 2 */
        .spec-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 18px;
        }

        @media (max-width: 800px) {
            .spec-grid {
                grid-template-columns: 1fr;
            }
        }

        .player-card {
            border: 1px solid var(--term-green-dim);
            background: rgba(5, 51, 10, 0.15);
            padding: 16px;
        }

        .player-card.p2 {
            border-color: var(--term-amber);
            color: var(--term-amber);
            text-shadow: 0 0 6px rgba(245, 158, 11, 0.6);
        }

        .player-card.p2 .btn-link {
            border-color: var(--term-amber);
            color: var(--term-amber);
        }

        .player-card.p2 .btn-link:hover {
            background: var(--term-amber);
            color: #000;
        }

        .card-header {
            font-size: 1.05rem;
            font-weight: bold;
            border-bottom: 1px dashed currentColor;
            padding-bottom: 6px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
        }

        .att-blocks {
            display: flex;
            gap: 6px;
            margin: 10px 0;
        }

        .att-box {
            width: 16px;
            height: 20px;
            background-color: currentColor;
            box-shadow: 0 0 6px currentColor;
        }

        .att-box.spent {
            background-color: transparent;
            border: 1px dashed currentColor;
            box-shadow: none;
        }

        .history-box {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid currentColor;
            height: 180px;
            overflow-y: auto;
            padding: 10px;
            font-size: 0.85rem;
            line-height: 1.5;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        /* Panel Central de Telemetría */
        .telemetry-bar {
            border: 1px solid var(--term-green-dim);
            background: rgba(0, 0, 0, 0.6);
            padding: 14px;
            font-size: 0.85rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .secret-reveal {
            cursor: pointer;
            border: 1px dashed var(--term-green);
            padding: 2px 8px;
        }

        /* Banner de Fin de Duelo */
        .winner-banner {
            display: none;
            margin-top: 16px;
            padding: 16px;
            border: 2px solid var(--term-green);
            background: rgba(5, 51, 10, 0.3);
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .winner-banner.active {
            display: block;
        }

        /* ── RESPONSIVE ADAPTATIONS (MOBILE & TABLET) ── */
        @media (max-width: 768px) {
            body {
                padding: 8px;
            }
            .spec-screen {
                padding: 14px 10px;
                border-width: 1.5px;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            .title {
                font-size: 1.05rem;
            }
            .actions {
                flex-wrap: wrap;
                gap: 6px;
                width: 100%;
            }
            .btn-link {
                font-size: 0.75rem;
                padding: 4px 8px;
            }
            .telemetry-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
                padding: 10px;
            }
            .history-box {
                height: 140px;
            }
            .winner-banner {
                font-size: 1rem;
                padding: 12px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 4px;
            }
            .spec-screen {
                padding: 10px 6px;
            }
            .actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 4px;
            }
            .actions .btn-link {
                text-align: center;
            }
            .card-header {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<div class="spec-screen">
    <!-- Header -->
    <div class="header">
        <div>
            <div class="title">ROBCO INDUSTRIES // MODO SUPERVISOR [VIGILANCIA EN VIVO]</div>
            <div style="font-size: 0.85rem; opacity: 0.8; margin-top: 2px;">
                CANAL: robco-room.{{ $room->code }} · NODO DE BÓVEDA [{{ $room->code }}]
            </div>
        </div>
        <div class="actions">
            <!-- Selector de Tema Pip-Boy -->
            <div class="theme-switcher" title="TEMA DE PANTALLA PIP-BOY" style="margin-right:6px;">
                <span style="font-size:0.75rem; opacity:0.8;">[PIP:</span>
                <button class="theme-btn active" data-t="green" onclick="applyTheme('green')"></button>
                <button class="theme-btn"        data-t="amber" onclick="applyTheme('amber')"></button>
                <button class="theme-btn"        data-t="blue"  onclick="applyTheme('blue')"></button>
                <span style="font-size:0.75rem; opacity:0.8;">]</span>
            </div>
            <a href="{{ route('terminal.room', ['code' => $room->code]) }}" class="btn-link">
                > [ENTRAR COMO JUGADOR]
            </a>
            <a href="{{ route('terminal.lobby') }}" class="btn-link">
                > [LOBBY]
            </a>
        </div>
    </div>

    <!-- Pantalla Dividida (Jugador 1 vs Jugador 2) -->
    <div class="spec-grid">
        <!-- Jugador 1 (Supervisor) -->
        <div class="player-card">
            <div class="card-header">
                <span>[1] {{ strtoupper($room->player1_name) }}</span>
                <span id="p1-status">EN LÍNEA</span>
            </div>
            @php $maxAtt = $room->settings['attempts'] ?? 4; @endphp
            <div>INTENTOS DISPONIBLES:</div>
            <div class="att-blocks" id="p1-blocks">
                @for($i = 0; $i < $maxAtt; $i++)
                    <div class="att-box {{ $i >= $room->player1_attempts ? 'spent' : '' }}" id="p1-att-{{ $i }}"></div>
                @endfor
            </div>
            <div style="margin-top: 10px; margin-bottom: 4px;">REGISTRO DE OPERADOR 1:</div>
            <div class="history-box" id="p1-log">
                <div>> INICIALIZÓ TERMINAL. ESPERANDO INTENTOS...</div>
            </div>
        </div>

        <!-- Jugador 2 (Infiltrado) -->
        <div class="player-card p2">
            <div class="card-header">
                <span id="p2-name-title">[2] {{ strtoupper($room->player2_name ?? 'EN ESPERA DE INTRUSO...') }}</span>
                <span id="p2-status">{{ $room->player2_token ? 'EN LÍNEA' : 'CONECTANDO...' }}</span>
            </div>
            <div>INTENTOS DISPONIBLES:</div>
            <div class="att-blocks" id="p2-blocks">
                @for($i = 0; $i < $maxAtt; $i++)
                    <div class="att-box {{ $i >= $room->player2_attempts ? 'spent' : '' }}" id="p2-att-{{ $i }}"></div>
                @endfor
            </div>
            <div style="margin-top: 10px; margin-bottom: 4px;">REGISTRO DE OPERADOR 2:</div>
            <div class="history-box" id="p2-log">
                <div>> {{ $room->player2_token ? 'VINCULADO A LA TERMINAL.' : 'ESPERANDO INTRUSIÓN REMOTA...' }}</div>
            </div>
        </div>
    </div>

    <!-- Barra de Telemetría -->
    <div class="telemetry-bar">
        <div>LONGITUD DE CLAVE: {{ $room->word_length }} LETRAS</div>
        <div>ESTADO DE SEGURIDAD: <span style="color: var(--term-green);">[CIFRADO ROBCO ACTIVO]</span></div>
        <div id="liveClock">TRANSMISIÓN EN VIVO · REVERB 8080</div>
    </div>

    <!-- Banner de Resolución de Partida -->
    <div class="winner-banner" id="winnerBanner">
        <div id="winnerText">PARTIDA FINALIZADA</div>
    </div>
</div>

<script>
const ROOM_CODE = "{{ $room->code }}";
const MAX_ATTEMPTS = {{ $maxAtt }};

function updateBlocks(prefix, left) {
    for (let i = 0; i < MAX_ATTEMPTS; i++) {
        const el = document.getElementById(`${prefix}-att-${i}`);
        if (el) {
            if (i < left) el.classList.remove('spent');
            else el.classList.add('spent');
        }
    }
}

function appendLog(boxId, text) {
    const box = document.getElementById(boxId);
    const line = document.createElement('div');
    line.innerText = `> ${text}`;
    box.appendChild(line);
    box.scrollTop = box.scrollHeight;
}

document.addEventListener('DOMContentLoaded', () => {
    if (window.Echo) {
        window.Echo.channel(`robco-room.${ROOM_CODE}`)
            .listen('.TerminalPlayerJoined', (e) => {
                document.getElementById('p2-name-title').innerText = `[2] ${e.player2Name.toUpperCase()}`;
                document.getElementById('p2-status').innerText = 'EN LÍNEA';
                appendLog('p2-log', `INTRUSO ${e.player2Name.toUpperCase()} CONECTADO.`);
            })
            .listen('.TerminalAttemptMade', (e) => {
                const prefix = (e.playerNumber === 1) ? 'p1' : 'p2';
                updateBlocks(prefix, e.attemptsLeft);
                appendLog(`${prefix}-log`, `Probó palabra. SEMEJANZA: ${e.likeness}/${e.wordLength} (Quedan ${e.attemptsLeft})`);

                if (e.isLocked) {
                    appendLog(`${prefix}-log`, `¡BLOQUEADO POR AGOTAR INTENTOS!`);
                }
            })
            .listen('.TerminalBracketUsed', (e) => {
                const prefix = (e.playerNumber === 1) ? 'p1' : 'p2';
                if (e.bonusType === 'restore_attempts') {
                    updateBlocks(prefix, e.attemptsRestored || MAX_ATTEMPTS);
                    appendLog(`${prefix}-log`, `Usó corchete: INTENTOS RESTABLECIDOS.`);
                } else if (e.bonusType === 'offensive_glitch') {
                    appendLog(`${prefix}-log`, `⚡ Usó corchete: EMITIÓ DESCARGA ELECTROMAGNÉTICA AL RIVAL.`);
                } else {
                    appendLog(`${prefix}-log`, `Usó corchete: Eliminó '${e.removedDud}'.`);
                }
            })
            .listen('.TerminalInterference', (e) => {
                const target = (e.senderPlayerNumber === 1) ? 'p2' : 'p1';
                appendLog(`${target}-log`, `⚡ ALERTA: INTERFERENCIA ELECTROMAGNÉTICA RECIBIDA DE ${e.senderName.toUpperCase()}.`);
            })
            .listen('.TerminalHacked', (e) => {
                const banner = document.getElementById('winnerBanner');
                const text = document.getElementById('winnerText');
                banner.classList.add('active');

                if (e.winnerNumber === 0) {
                    text.innerHTML = `⚠️ AMBOS OPERADORES HAN SIDO BLOQUEADOS.<br>LA CONTRASEÑA ERA [${e.secretWord}].`;
                    banner.style.borderColor = '#ef4444';
                    banner.style.color = '#ef4444';
                } else {
                    text.innerHTML = `🏆 ¡OPERADOR ${e.winnerNumber} (${e.winnerName.toUpperCase()}) HA HACKEADO LA BÓVEDA!<br>CONTRASEÑA: [${e.secretWord}] EN ${e.attemptsUsed} INTENTO(S).`;
                    banner.style.borderColor = '#1bf835';
                    banner.style.color = '#1bf835';
                }
            })
            .listen('.TerminalRematch', (e) => {
                document.getElementById('winnerBanner').classList.remove('active');
                updateBlocks('p1', 4);
                updateBlocks('p2', 4);
                appendLog('p1-log', '--- REVANCHA INICIADA (4 INTENTOS) ---');
                appendLog('p2-log', '--- REVANCHA INICIADA (4 INTENTOS) ---');
            });
    }
});

// ── TEMA PIP-BOY ───────────────────────────────────────────────────────────
function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('robco_theme', theme);
    document.querySelectorAll('.theme-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.t === theme);
    });
}
(function initTheme() {
    const params = new URLSearchParams(window.location.search);
    const saved = params.get('theme') || localStorage.getItem('robco_theme') || 'green';
    applyTheme(saved);
})();
</script>
</body>
</html>
