<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emisor | Reverb WebSocket</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg: #0f172a;
            --card: #1e293b;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --success: #10b981;
            --error: #ef4444;
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
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .wrap {
            width: 100%;
            max-width: 680px;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
        }

        .badge {
            display: inline-block;
            background: rgba(79, 70, 229, 0.2);
            color: #818cf8;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 9999px;
            border: 1px solid rgba(129, 140, 248, 0.3);
            margin-bottom: 8px;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
        }

        p.subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-top: 4px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
        }

        .alert-ok {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid var(--success);
            color: #34d399;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-err {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid var(--error);
            color: #f87171;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .field {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 6px;
        }

        input[type="text"], textarea {
            width: 100%;
            background: #0f172a;
            border: 1px solid var(--border);
            border-radius: 10px;
            color: #fff;
            padding: 12px 14px;
            font-size: 0.95rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        textarea {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.875rem;
            resize: vertical;
        }

        input[type="text"]:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25);
        }

        .presets {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .preset-btn {
            background: #334155;
            color: #e2e8f0;
            border: none;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: background 0.15s;
        }

        .preset-btn:hover {
            background: #475569;
        }

        .submit-btn {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .submit-btn:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .footer-links {
            margin-top: 20px;
            text-align: center;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .footer-links a {
            color: #818cf8;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <span class="badge">Programación IV · UTN</span>
        <h1>Panel Emisor</h1>
        <p class="subtitle">Envía datos y estilos en tiempo real a los receptores conectados</p>
    </div>

    <div class="card">
        @if (session('ok'))
            <div class="alert-ok">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('ok') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-err">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('emitir') }}">
            @csrf

            <div class="field">
                <label for="newText">Texto a mostrar</label>
                <input type="text" id="newText" name="newText" placeholder="Ej: Ahora atendiendo: A-037" value="{{ old('newText') }}" required>
            </div>

            <div class="field">
                <label for="newStyle">CSS inline (opcional)</label>
                <textarea id="newStyle" name="newStyle" rows="5" placeholder="Ej: background-color: #e02424; color: #fff; border-radius: 12px; padding: 20px; text-align: center;">{{ old('newStyle') }}</textarea>

                <div class="presets">
                    <span style="font-size: 0.75rem; color: #94a3b8; align-self: center;">Estilos de prueba:</span>
                    <button type="button" class="preset-btn" onclick="applyPreset('red')">Rojo Alerta</button>
                    <button type="button" class="preset-btn" onclick="applyPreset('cyber')">Cyber Neon</button>
                    <button type="button" class="preset-btn" onclick="applyPreset('gold')">Dorado VIP</button>
                    <button type="button" class="preset-btn" onclick="applyPreset('clean')">Limpiar</button>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <span>Emitir por WebSocket</span>
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </form>

        <div class="footer-links">
            Abrí <a href="{{ route('receptor') }}" target="_blank">/receptor</a> en otra pestaña para ver los cambios instantáneamente.
        </div>
    </div>
</div>

<script>
function applyPreset(type) {
    const textarea = document.getElementById('newStyle');
    const input = document.getElementById('newText');
    if (type === 'red') {
        input.value = '¡ATENCIÓN! Turno A-037';
        textarea.value = 'width: 80%; max-width: 600px; margin: 30px auto; padding: 24px; background-color: #e02424; border: 2px solid #b91c1c; border-radius: 16px; box-shadow: 0 4px 25px rgba(220, 38, 38, 0.6); color: #fff; font-family: Segoe UI, sans-serif; text-align: center; font-size: 1.5rem; font-weight: bold;';
    } else if (type === 'cyber') {
        input.value = '👾 JUGADOR 1 HA ENTRADO';
        textarea.value = 'width: 85%; max-width: 600px; margin: 30px auto; padding: 24px; background: linear-gradient(135deg, #09090b, #18181b); border: 2px solid #22c55e; border-radius: 16px; box-shadow: 0 0 30px rgba(34, 197, 94, 0.4); color: #22c55e; font-family: monospace; text-align: center; font-size: 1.4rem; letter-spacing: 2px;';
    } else if (type === 'gold') {
        input.value = '🏆 ¡NUEVO RÉCORD: 9,850 PTS!';
        textarea.value = 'width: 85%; max-width: 600px; margin: 30px auto; padding: 24px; background: linear-gradient(135deg, #78350f, #b45309); border: 2px solid #f59e0b; border-radius: 16px; box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4); color: #fef3c7; font-family: system-ui; text-align: center; font-size: 1.5rem; font-weight: 700;';
    } else if (type === 'clean') {
        textarea.value = '';
    }
}
</script>
</body>
</html>
