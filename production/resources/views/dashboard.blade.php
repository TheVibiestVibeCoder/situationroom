<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $workspace->name }} | Situation Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Cardo:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    @include('partials.dashboard-styles')
</head>
<body>
    <div class="mono-noise"></div>
    <div class="spotlight"></div>

    <div class="toolbar">
        <button class="tool-btn" id="themeToggle" title="Toggle Theme">☀︎</button>
        @auth
            <a href="{{ route('admin') }}" class="tool-btn" title="Admin Panel">⚙</a>
        @endauth
    </div>

    <div class="overlay" id="qrOverlay">
        <div class="qr-overlay-content">
            <div id="qrcodeBig"></div>
        </div>
        <div class="overlay-instruction">Click anywhere to close</div>
    </div>

    <div class="overlay" id="focusOverlay">
        <div class="focus-text" id="focusContent"></div>
    </div>

    <div class="container">
        <header class="header-split">
            <div>
                <span class="subtitle">Live Feed</span>
                <h1>{{ $workspace->name }}</h1>
                <a href="{{ route('submit') }}" class="mobile-join-btn">↳ Eingabe starten</a>
            </div>

            <div class="qr-section" id="openQr">
                <div class="qr-text">SCAN TO<br>PARTICIPATE<br><span style="opacity: 0.5;">CLICK TO EXPAND</span></div>
                <div class="qr-wrapper" id="qrcodeSmall"></div>
            </div>
        </header>

        <div class="dashboard-grid" id="board">
            @php
                $categories = [
                    'bildung' => ['title' => 'BILDUNG & FORSCHUNG', 'icon' => '📚'],
                    'social' => ['title' => 'SOZIALE MEDIEN', 'icon' => '📱'],
                    'individuell' => ['title' => 'INDIV. VERANTWORTUNG', 'icon' => '🧑'],
                    'politik' => ['title' => 'POLITIK & RECHT', 'icon' => '⚖️'],
                    'kreativ' => ['title' => 'INNOVATIVE ANSÄTZE', 'icon' => '💡']
                ];
            @endphp

            @foreach($categories as $key => $info)
                <div class="column" id="col-{{ $key }}">
                    <h2>{{ $info['icon'] }} {{ $info['title'] }}</h2>
                    <div class="card-container"></div>
                </div>
            @endforeach
        </div>
    </div>

    @include('partials.dashboard-scripts')
</body>
</html>
