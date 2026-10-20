<style>
    /* ── ESTILOS DE ACCESIBILIDAD Y LEGIBILIDAD ROBCO ────────────────────────── */

    /* 1. MODO NÍTIDO (SIN SCANLINES NI VIÑETA OSCURA) */
    html[data-crt="sharp"] .term-screen::before,
    html[data-crt="sharp"] .terminal-screen::before,
    html[data-crt="sharp"] .crt-monitor::before {
        display: none !important;
    }

    html[data-crt="sharp"] .term-screen::after,
    html[data-crt="sharp"] .terminal-screen::after,
    html[data-crt="sharp"] .crt-monitor::after {
        display: none !important;
    }

    html[data-crt="sharp"] body,
    html[data-crt="sharp"] .term-screen,
    html[data-crt="sharp"] .terminal-screen,
    html[data-crt="sharp"] .memory-columns {
        text-shadow: none !important;
    }

    /* 2. MODO ALTO CONTRASTE (PERCEPCIÓN: PALABRAS RESALTADAS) */
    html[data-contrast="high"] .cell[data-type="junk"] {
        opacity: 0.38 !important;
        filter: grayscale(40%);
    }

    html[data-contrast="high"] .cell[data-type="word"] {
        opacity: 1 !important;
        font-weight: bold !important;
        text-shadow: 0 0 6px var(--term-green) !important;
        border-bottom: 1px dotted var(--term-green) !important;
    }

    html[data-contrast="high"] .cell[data-type="bracket"] {
        opacity: 0.95 !important;
        color: var(--term-amber) !important;
        font-weight: bold !important;
    }

    /* 3. TAMAÑO DE FUENTE ESCALABLE (MEMORIA) */
    html[data-fontsize="large"] .memory-columns {
        font-size: 1.25rem !important;
        line-height: 1.55 !important;
        gap: 22px !important;
    }

    html[data-fontsize="large"] .mem-chars {
        letter-spacing: 3px !important;
    }

    html[data-fontsize="large"] .mem-addr {
        margin-right: 14px !important;
    }

    html[data-fontsize="xlarge"] .memory-columns {
        font-size: 1.45rem !important;
        line-height: 1.7 !important;
        gap: 26px !important;
    }

    html[data-fontsize="xlarge"] .mem-chars {
        letter-spacing: 4px !important;
    }

    html[data-fontsize="xlarge"] .mem-addr {
        margin-right: 18px !important;
    }

    /* ── VENTANA MODAL DE AJUSTES ROBCO ──────────────────────────────────────── */
    .robco-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.82);
        backdrop-filter: blur(4px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        animation: modal-fade 0.15s ease-out;
    }

    @keyframes modal-fade {
        from { opacity: 0; transform: scale(0.98); }
        to { opacity: 1; transform: scale(1); }
    }

    .robco-settings-dialog {
        width: 100%;
        max-width: 640px;
        background: var(--term-bg, #010802);
        border: 2px solid var(--term-green, #1bf835);
        box-shadow: 0 0 30px rgba(0,0,0,0.95), 0 0 16px var(--term-green-dim, #0e851c);
        border-radius: 4px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        color: var(--term-green, #1bf835);
        font-family: 'Share Tech Mono', monospace;
    }

    .settings-dialog-header {
        background: rgba(27, 248, 53, 0.12);
        border-bottom: 2px solid var(--term-green, #1bf835);
        padding: 12px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
        font-size: 0.95rem;
        letter-spacing: 1px;
    }

    .settings-close-btn {
        background: transparent;
        border: 1px solid var(--term-red, #ef4444);
        color: var(--term-red, #ef4444);
        cursor: pointer;
        font-family: inherit;
        font-size: 0.9rem;
        padding: 2px 8px;
        font-weight: bold;
        transition: all 0.15s ease;
    }

    .settings-close-btn:hover {
        background: var(--term-red, #ef4444);
        color: #000;
        box-shadow: 0 0 8px #ef4444;
    }

    .settings-dialog-body {
        padding: 18px 22px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        max-height: 72vh;
        overflow-y: auto;
    }

    .setting-group {
        border-bottom: 1px dashed var(--term-green-dim, #0e851c);
        padding-bottom: 14px;
    }

    .setting-group:last-child {
        border-bottom: none;
        padding-bottom: 4px;
    }

    .setting-label {
        display: block;
        font-weight: bold;
        font-size: 0.9rem;
        color: var(--term-green, #1bf835);
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }

    .setting-options {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 6px;
    }

    .setting-desc {
        font-size: 0.75rem;
        color: var(--term-green-dim, #0e851c);
        line-height: 1.4;
        opacity: 0.9;
    }

    .opt-btn {
        background: transparent;
        border: 1px solid var(--term-green-dim, #0e851c);
        color: var(--term-green-dim, #0e851c);
        font-family: inherit;
        font-size: 0.85rem;
        padding: 6px 14px;
        cursor: pointer;
        transition: all 0.15s ease;
        text-shadow: none;
    }

    .opt-btn:hover {
        border-color: var(--term-green, #1bf835);
        color: var(--term-green, #1bf835);
    }

    .opt-btn.active {
        background: var(--term-green, #1bf835);
        color: #000000 !important;
        font-weight: bold;
        border-color: var(--term-green, #1bf835);
        box-shadow: 0 0 8px var(--term-green, #1bf835);
    }

    .settings-dialog-footer {
        border-top: 1px solid var(--term-green-dim, #0e851c);
        padding: 12px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
        color: var(--term-green-dim, #0e851c);
        background: rgba(0, 0, 0, 0.4);
        flex-wrap: wrap;
        gap: 10px;
    }

    .settings-trigger-btn {
        background: transparent;
        border: 1px solid var(--term-green-dim, #0e851c);
        color: var(--term-green, #1bf835);
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.8rem;
        padding: 4px 10px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
        text-shadow: var(--term-glow, 0 0 6px rgba(27, 248, 53, 0.65));
    }

    .settings-trigger-btn:hover {
        background: var(--term-green, #1bf835);
        color: #000;
        box-shadow: 0 0 8px var(--term-green, #1bf835);
        text-shadow: none;
    }
</style>

<!-- MODAL DE CONFIGURACIÓN ROBCO -->
<div id="robcoSettingsModal" class="robco-modal-overlay" style="display:none;" onclick="if(event.target === this) window.toggleRobcoSettings(false);">
    <div class="robco-settings-dialog" role="dialog" aria-modal="true" aria-labelledby="settingsDialogTitle">
        <div class="settings-dialog-header">
            <span id="settingsDialogTitle">⚙ SUBRUTINA DE AJUSTES VISUALES // ROBCO TERMLINK</span>
            <button type="button" class="settings-close-btn" onclick="window.toggleRobcoSettings(false)" aria-label="Cerrar">[X]</button>
        </div>

        <div class="settings-dialog-body">
            <!-- 1. FILTRO DE PANTALLA CRT -->
            <div class="setting-group">
                <label class="setting-label">> FILTRO DE TUBO CRT Y SCANLINES</label>
                <div class="setting-options">
                    <button type="button" class="opt-btn" data-setting="crt" data-val="retro" onclick="window.setRobcoSetting('crt', 'retro')">
                        [● RETRO / VINTAGE]
                    </button>
                    <button type="button" class="opt-btn" data-setting="crt" data-val="sharp" onclick="window.setRobcoSetting('crt', 'sharp')">
                        [○ NÍTIDO / ALTA LEGIBILIDAD]
                    </button>
                </div>
                <div class="setting-desc">
                    El modo <strong>Nítido</strong> apaga las líneas de escaneo oscuras y elimina el desenfoque para que las letras se lean con total claridad.
                </div>
            </div>

            <!-- 2. CONTRASTE DE PALABRAS (MODO PERCEPCIÓN) -->
            <div class="setting-group">
                <label class="setting-label">> CONTRASTE DE MEMORIA (MODO PERCEPCIÓN)</label>
                <div class="setting-options">
                    <button type="button" class="opt-btn" data-setting="contrast" data-val="classic" onclick="window.setRobcoSetting('contrast', 'classic')">
                        [● CLÁSICO (UNIFORME)]
                    </button>
                    <button type="button" class="opt-btn" data-setting="contrast" data-val="high" onclick="window.setRobcoSetting('contrast', 'high')">
                        [○ ALTO CONTRASTE (RESALTAR PALABRAS)]
                    </button>
                </div>
                <div class="setting-desc">
                    El modo <strong>Alto Contraste</strong> atenúa los símbolos basura (<code>%!$#&amp;</code>) al 40% y resalta las contraseñas en negrita brillante para que salten a la vista.
                </div>
            </div>

            <!-- 3. TAMAÑO DE FUENTE DE LA MATRIZ DE MEMORIA -->
            <div class="setting-group">
                <label class="setting-label">> TAMAÑO DEL VOLCADO DE MEMORIA</label>
                <div class="setting-options">
                    <button type="button" class="opt-btn" data-setting="fontsize" data-val="normal" onclick="window.setRobcoSetting('fontsize', 'normal')">
                        [NORMAL 100%]
                    </button>
                    <button type="button" class="opt-btn" data-setting="fontsize" data-val="large" onclick="window.setRobcoSetting('fontsize', 'large')">
                        [GRANDE +20%]
                    </button>
                    <button type="button" class="opt-btn" data-setting="fontsize" data-val="xlarge" onclick="window.setRobcoSetting('fontsize', 'xlarge')">
                        [EXTRA +40%]
                    </button>
                </div>
                <div class="setting-desc">
                    Ajusta el zoom de los caracteres en la matriz de hackeo. Ideal para pantallas pequeñas o móviles.
                </div>
            </div>

            <!-- 4. TEMA PIP-BOY -->
            <div class="setting-group">
                <label class="setting-label">> TEMA DE FÓSFORO PIP-BOY</label>
                <div class="setting-options">
                    <button type="button" class="opt-btn" data-setting="theme" data-val="green" onclick="window.setRobcoTheme('green')">
                        [🟢 VERDE FÓSFORO]
                    </button>
                    <button type="button" class="opt-btn" data-setting="theme" data-val="amber" onclick="window.setRobcoTheme('amber')">
                        [🟠 ÁMBAR CLÁSICO]
                    </button>
                    <button type="button" class="opt-btn" data-setting="theme" data-val="blue" onclick="window.setRobcoTheme('blue')">
                        [🔵 AZUL VAULT-TEC]
                    </button>
                </div>
            </div>
        </div>

        <div class="settings-dialog-footer">
            <span>PREFERENCIAS GUARDADAS LOCALMENTE</span>
            <button type="button" class="settings-trigger-btn" onclick="window.toggleRobcoSettings(false)" style="font-weight:bold;">
                > [APLICAR Y CERRAR]
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    // Sonido sutil retro para interacción con ajustes
    function playSettingsClick() {
        try {
            const ctx = window.audioCtx || (window.AudioContext ? new (window.AudioContext || window.webkitAudioContext)() : null);
            if (!ctx) return;
            if (ctx.state === 'suspended') ctx.resume();
            const osc = ctx.createOscillator();
            const g = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(800, ctx.currentTime);
            g.gain.setValueAtTime(0.02, ctx.currentTime);
            g.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.04);
            osc.connect(g);
            g.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.04);
        } catch(e) {}
    }

    window.setRobcoSetting = function(key, val) {
        playSettingsClick();
        if (key === 'crt') {
            document.documentElement.setAttribute('data-crt', val);
            localStorage.setItem('robco_crt', val);
        } else if (key === 'contrast') {
            document.documentElement.setAttribute('data-contrast', val);
            localStorage.setItem('robco_contrast', val);
        } else if (key === 'fontsize') {
            document.documentElement.setAttribute('data-fontsize', val);
            localStorage.setItem('robco_font_size', val);
        }
        window.syncRobcoSettingsUI();
    };

    window.setRobcoTheme = function(theme) {
        playSettingsClick();
        if (typeof applyTheme === 'function') {
            applyTheme(theme);
        } else {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('robco_theme', theme);
            document.querySelectorAll('.theme-btn').forEach(b => {
                b.classList.toggle('active', b.dataset.t === theme);
            });
        }
        window.syncRobcoSettingsUI();
    };

    window.syncRobcoSettingsUI = function() {
        const crt = localStorage.getItem('robco_crt') || 'retro';
        const contrast = localStorage.getItem('robco_contrast') || 'classic';
        const fontSize = localStorage.getItem('robco_font_size') || 'normal';
        const theme = localStorage.getItem('robco_theme') || 'green';

        document.querySelectorAll('.opt-btn').forEach(btn => {
            const s = btn.dataset.setting;
            const v = btn.dataset.val;
            if (s === 'crt') btn.classList.toggle('active', v === crt);
            if (s === 'contrast') btn.classList.toggle('active', v === contrast);
            if (s === 'fontsize') btn.classList.toggle('active', v === fontSize);
            if (s === 'theme') btn.classList.toggle('active', v === theme);
        });
    };

    window.toggleRobcoSettings = function(show) {
        const modal = document.getElementById('robcoSettingsModal');
        if (!modal) return;
        playSettingsClick();
        const isVisible = (modal.style.display !== 'none');
        const targetState = (show !== undefined) ? show : !isVisible;
        modal.style.display = targetState ? 'flex' : 'none';
        if (targetState) {
            window.syncRobcoSettingsUI();
        }
    };

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('robcoSettingsModal');
            if (modal && modal.style.display !== 'none') {
                window.toggleRobcoSettings(false);
            }
        }
    });

    // Sincronizar botones al cargar
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', window.syncRobcoSettingsUI);
    } else {
        window.syncRobcoSettingsUI();
    }
})();
</script>
