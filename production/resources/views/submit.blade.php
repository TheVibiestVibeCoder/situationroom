<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Eingabe | {{ $workspace->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cardo:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        :root {
            --bg-dark: #050505; --bg-input: rgba(255, 255, 255, 0.05); --border-subtle: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff; --text-muted: #a0a0a0; --accent-success: #00cc66; --accent-error: #ff3333; --accent-info: #3399ff;
            --font-heading: 'Cardo', serif; --font-body: 'Inter', sans-serif;
        }
        body { background-color: var(--bg-dark); color: var(--text-main); font-family: var(--font-body); margin: 0; padding: 0; line-height: 1.6; min-height: 100vh; display: flex; flex-direction: column; overflow-x: hidden; }
        .mono-noise { position: fixed; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.06; pointer-events: none; z-index: -1; }
        .spotlight { position: fixed; top: -20%; left: 50%; transform: translateX(-50%); width: 80vw; height: 80vw; background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%); filter: blur(80px); pointer-events: none; z-index: -1; }
        .form-wrapper { width: 100%; max-width: 600px; margin: 0 auto; padding: 2rem; flex-grow: 1; display: flex; flex-direction: column; justify-content: center; }
        select, textarea { width: 100%; padding: 14px; background: var(--bg-input); border: 1px solid var(--border-subtle); color: white; font-family: var(--font-body); font-size: 1rem; transition: 0.3s; border-radius: 0; -webkit-appearance: none; }
        select:focus, textarea:focus { outline: none; border-color: white; background: rgba(255,255,255,0.08); }
        textarea { resize: vertical; min-height: 150px; }
        select option { background: #1a1a1a; color: white; }
        .form-group { margin-bottom: 2rem; }
        label { display: block; margin-bottom: 8px; color: var(--text-muted); font-weight: 600; letter-spacing: 1px; font-size: 0.8rem; text-transform: uppercase; }
        .info-box { display: none; padding: 1.5rem; background: rgba(51, 153, 255, 0.05); border-left: 3px solid var(--accent-info); margin-bottom: 2rem; animation: fadeIn 0.5s; }
        .info-label { text-transform: uppercase; letter-spacing: 1px; font-size: 0.75rem; font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 0.75rem; }
        .info-content { font-family: var(--font-body); font-size: 1rem; line-height: 1.6; color: #ffffff; font-style: normal; font-weight: 400; }
        .info-content ul { padding-left: 20px; margin: 0; }
        .info-content li { margin-bottom: 12px; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .btn-submit { width: 100%; padding: 16px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white; font-weight: 600; letter-spacing: 1px; cursor: pointer; transition: 0.3s; text-transform: uppercase; font-size: 0.9rem; -webkit-tap-highlight-color: transparent; }
        .btn-submit:hover { background: white; color: black; box-shadow: 0 0 30px rgba(255,255,255,0.3); }
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }
        .alert { padding: 1rem; margin-bottom: 2rem; border-left: 3px solid; background: rgba(255,255,255,0.03); font-size: 0.9rem; }
        .alert-success { border-color: var(--accent-success); color: var(--accent-success); }
        .alert-error { border-color: var(--accent-error); color: var(--accent-error); }
        h1 { font-family: var(--font-heading); font-size: clamp(2.2rem, 6vw, 3.5rem); margin: 0 0 10px 0; line-height: 1.1; color: white; word-wrap: break-word; }
        .subtitle { color: var(--text-muted); text-transform: uppercase; letter-spacing: 3px; font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 1rem; }
        .link-subtle { color: var(--text-muted); text-decoration: none; border-bottom: 1px solid transparent; transition: 0.3s; padding-bottom: 2px; }
        .link-subtle:hover { color: white; border-color: white; }
        @media (max-width: 600px) {
            .form-wrapper { padding: 1.5rem 1.25rem; margin-top: 1rem; display: block; }
            .spotlight { top: -10%; width: 120vw; height: 120vw; }
            h1 { font-size: 2.2rem; }
            textarea { min-height: 120px; }
            .info-content { font-size: 0.95rem; }
        }
    </style>
</head>
<body>
    <div class="mono-noise"></div>
    <div class="spotlight"></div>

    <div class="form-wrapper">
        <header style="text-align: center; margin-bottom: 2.5rem;">
            <span class="subtitle">Workshop Eingabe</span>
            <h1>{{ $workspace->name }}</h1>
            <p style="color: var(--text-muted); margin-top: 10px; font-size: 0.95rem;">Wähle deine Gruppe, um die Leitfragen zu sehen.</p>
        </header>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('submit.store') }}" id="ideaForm">
            @csrf
            <div class="form-group">
                <label for="category">1. Station Wählen</label>
                <select name="category" id="category" required>
                    <option value="" disabled selected>-- Gruppe wählen --</option>
                    <option value="bildung">📚 Bildung & Schule</option>
                    <option value="social">📱 Verantwortung Social Media</option>
                    <option value="individuell">🧑 Individuelle Verantwortung</option>
                    <option value="politik">⚖️ Politik & Recht</option>
                    <option value="kreativ">💡 Kreative & innovative Ansätze</option>
                </select>
            </div>

            <div id="infoBox" class="info-box">
                <span class="info-label">Leitfragen</span>
                <div id="infoContent" class="info-content"></div>
            </div>

            <div class="form-group">
                <label for="text">2. Maßnahme definieren</label>
                <textarea name="text" id="text" rows="6" placeholder="Bitte zuerst Gruppe wählen..." required maxlength="500"></textarea>
                <div style="text-align: right; font-size: 0.75rem; color: var(--text-muted); margin-top: 5px;">
                    <span id="charCount">0</span> / 500 Zeichen
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">Antwort Senden</button>
        </form>

        <div style="text-align: center; margin-top: 3rem; margin-bottom: 2rem;">
            <a href="{{ route('dashboard') }}" class="link-subtle">📊 Zum Live-Dashboard</a>
        </div>
    </div>

    <script>
        const leitfragen = {
            'bildung': '<ul><li>Was können Schulen tun, um beim Kampf gegen Desinformation zu helfen?</li><li>Was bräuchtet ihr im Unterricht, um besser damit umgehen zu können?</li><li>Was würdet ihr gern lernen?</li></ul>',
            'social': '<ul><li>Was würde euch auf Social Media helfen, Desinformation besser zu erkennen?</li><li>Wie sollten Plattformen mit Desinformation umgehen? Was könnten sie besser machen?</li><li>Wie könnten Plattformen gestaltet sein, damit Fakten mehr Chancen haben als Desinformation?</li></ul>',
            'individuell': '<ul><li>Was braucht es, damit Menschen besser mit Desinformation umgehen können?</li><li>Was sollten wir als Gesellschaft tun, um Menschen aufzuklären?</li><li>Wenn ihr an eure Oma denkt: Wie wird sie resilient gegen Desinformation?</li></ul>',
            'politik': '<ul><li>Welche Regeln oder Gesetze braucht es, damit wir Desinformation eindämmen können?</li><li>Was sollte es geben, das es noch nicht gibt?</li><li>Was könnten Politiker:innen tun, um beim Kampf gegen Desinformation zu helfen?</li></ul>',
            'kreativ': '<ul><li>Welche Out-Of-The-Box-Ideen fallen dir ein, wie man das Thema besser angehen könnte?</li><li>Such dir eine Maßnahme aus, mit der du Desinformation bekämpfen würdest – wer müsste was tun und wieso?</li><li>Du hast unlimitiert viel Geld: Was würdest du bauen / tun, um Desinformation zu bekämpfen?</li></ul>'
        };

        const select = document.getElementById('category');
        const infoBox = document.getElementById('infoBox');
        const infoContent = document.getElementById('infoContent');
        const textarea = document.getElementById('text');
        const charCount = document.getElementById('charCount');

        select.addEventListener('change', function() {
            if (leitfragen[this.value]) {
                infoContent.innerHTML = leitfragen[this.value];
                infoBox.style.display = 'none';
                infoBox.offsetHeight;
                infoBox.style.display = 'block';
                textarea.placeholder = "Antworte auf die Fragen oder beschreibe deine eigene Maßnahme...";
            }
        });

        textarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            charCount.style.color = this.value.length > 450 ? '#ff3333' : '#a0a0a0';
        });

        let isSubmitting = false;
        document.getElementById('ideaForm').addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            isSubmitting = true;
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML = 'ÜBERMITTLUNG LÄUFT...';
        });
    </script>
</body>
</html>
