<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>{{ $workspace->name }} - Workshop Protokoll</title>
    <link href="https://fonts.googleapis.com/css2?family=Cardo:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; color: #111; line-height: 1.5; padding: 40px; max-width: 900px; margin: 0 auto; }
        h1 { font-family: 'Cardo', serif; font-size: 2.5rem; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 5px; }
        .meta { color: #666; font-size: 0.9rem; margin-bottom: 3rem; }
        .section { margin-bottom: 3rem; page-break-inside: avoid; }
        .section-title { font-size: 1.2rem; text-transform: uppercase; letter-spacing: 2px; font-weight: bold; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 1.5rem; color: #333; }
        .entry { margin-bottom: 1.5rem; padding-left: 15px; border-left: 3px solid #eee; }
        .entry-text { font-size: 1rem; margin-bottom: 5px; }
        .entry-meta { font-size: 0.75rem; color: #888; }
        .no-data { color: #999; font-style: italic; }
        @media print { body { padding: 0; } }
    </style>
</head>
<body onload="window.print()">
    <h1>{{ $workspace->name }}</h1>
    <div class="meta">Workshop Ergebnisse • Generiert am {{ now()->format('d.m.Y \u\m H:i') }} Uhr</div>

    @foreach($categories as $key => $label)
        <div class="section">
            <div class="section-title">{{ $label }}</div>

            @if(isset($entries[$key]) && count($entries[$key]) > 0)
                @foreach($entries[$key] as $entry)
                    <div class="entry">
                        <div class="entry-text">{{ $entry->text }}</div>
                        <div class="entry-meta">
                            {{ $entry->created_at->format('H:i') }} Uhr •
                            Status: {{ $entry->visible ? 'LIVE' : 'ENTWURF' }}
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-data">Keine Einträge vorhanden.</div>
            @endif
        </div>
    @endforeach
</body>
</html>
