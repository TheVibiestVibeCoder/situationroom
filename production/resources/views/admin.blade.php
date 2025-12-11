<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ $workspace->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cardo:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg-dark: #0a0a0a; --bg-card: #1a1a1a; --text-main: #ffffff; --text-muted: #888888; --border-subtle: #333333; --accent-success: #00cc66; --accent-danger: #ff3333; --accent-warning: #ff9933; --font-heading: 'Cardo', serif; --font-body: 'Inter', sans-serif; }
        body { background-color: var(--bg-dark); color: var(--text-main); font-family: var(--font-body); margin: 0; padding: 0; line-height: 1.6; }
        .mono-noise { position: fixed; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.04; pointer-events: none; z-index: -1; }
        .container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
        .admin-header { display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 2px solid white; padding-bottom: 1rem; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .admin-header h1 { font-family: var(--font-heading); font-size: 3rem; margin: 0; line-height: 1; }
        .subtitle { color: var(--text-muted); text-transform: uppercase; letter-spacing: 3px; font-size: 0.7rem; font-weight: 600; display: block; margin-bottom: 0.5rem; }
        .header-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn { padding: 10px 20px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white; text-decoration: none; font-weight: 600; letter-spacing: 0.5px; cursor: pointer; transition: 0.3s; font-size: 0.8rem; display: inline-block; text-align: center; }
        .btn:hover { background: white; color: black; }
        .btn-danger { background: rgba(255,51,51,0.2); border-color: var(--accent-danger); }
        .btn-danger:hover { background: var(--accent-danger); }
        .btn-success { background: rgba(0,204,102,0.2); border-color: var(--accent-success); }
        .btn-success:hover { background: var(--accent-success); color: black; }
        .btn-neutral { background: rgba(255,255,255,0.05); }
        .btn-sm { padding: 6px 12px; font-size: 0.75rem; }
        .command-panel { background: var(--bg-card); border: 1px solid var(--border-subtle); padding: 1.5rem; margin-bottom: 2rem; }
        .command-row { display: flex; gap: 2rem; align-items: flex-start; }
        .command-label { display: block; color: var(--text-muted); font-size: 0.7rem; font-weight: 600; letter-spacing: 1px; margin-bottom: 8px; }
        .global-btns { display: flex; gap: 5px; }
        #admin-feed { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem; }
        .admin-card { background: var(--bg-card); border: 1px solid var(--border-subtle); padding: 1rem; transition: 0.3s; position: relative; }
        .admin-card.status-live { border-left: 3px solid var(--accent-success); }
        .admin-card.status-hidden { border-left: 3px solid var(--border-subtle); }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
        .card-category { padding: 4px 8px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-subtle); color: white; font-size: 0.75rem; }
        .card-time { font-size: 0.7rem; color: var(--text-muted); }
        .card-body { font-size: 0.9rem; margin-bottom: 1rem; line-height: 1.4; min-height: 40px; word-wrap: break-word; }
        .card-actions { display: flex; gap: 6px; }
        .card-actions .btn { flex: 1; padding: 8px; font-size: 0.7rem; }
        .btn-focus { background: rgba(255,153,51,0.2); border-color: var(--accent-warning); }
        .btn-focus:hover { background: var(--accent-warning); color: black; }
        .btn-focus.is-focused { background: var(--accent-warning); color: black; font-weight: bold; }
        @media (max-width: 768px) {
            .container { padding: 1rem; }
            .admin-header { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
            .header-actions { width: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
            .command-row { flex-direction: column; gap: 1.5rem; }
            .global-btns { width: 100%; gap: 10px; }
            .global-btns .btn { flex: 1; }
            #admin-feed { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="mono-noise"></div>

    <div class="container">
        <header class="admin-header">
            <div>
                <span class="subtitle">Backend Control</span>
                <h1>{{ $workspace->name }}</h1>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.export-pdf') }}" target="_blank" class="btn">PDF Export</a>
                <a href="{{ route('dashboard') }}" target="_blank" class="btn">View Live</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </header>

        <div class="command-panel">
            <h3 style="margin: 0 0 1rem 0; font-size: 0.9rem; text-transform: uppercase; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 5px;">Mass Control</h3>

            <div class="command-row">
                <div>
                    <span class="command-label">GLOBAL ACTION</span>
                    <div class="global-btns">
                        <button onclick="if(confirm('ALLES Live schalten?')) runCmd('show-all')" class="btn btn-sm btn-success" style="flex:1">ALL LIVE</button>
                        <button onclick="if(confirm('ALLES verstecken?')) runCmd('hide-all')" class="btn btn-sm btn-neutral" style="flex:1">ALL HIDE</button>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 2px solid white; padding-bottom: 10px;">
                <h2 style="margin: 0;">Incoming Data Feed</h2>
                <div id="purge-btn-wrapper"></div>
            </div>
        </div>

        <div id="admin-feed">
            <div style="padding: 3rem; text-align: center; color: var(--text-muted); grid-column: 1 / -1;">Loading Data...</div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const categories = {
            'bildung': 'Bildung',
            'social': 'Social',
            'individuell': 'Individuell',
            'politik': 'Politik',
            'kreativ': 'Innovation'
        };

        function renderAdmin(data) {
            const feed = document.getElementById('admin-feed');
            const purgeWrapper = document.getElementById('purge-btn-wrapper');

            if (data.length > 0) {
                purgeWrapper.innerHTML = `<button onclick="if(confirm('WARNING: PURGE ALL?')) runCmd('purge-all')" class="btn btn-danger" style="font-size: 0.7rem;">PURGE ALL</button>`;
            } else {
                purgeWrapper.innerHTML = '';
                feed.innerHTML = '<div style="padding: 3rem; text-align: center; color: var(--text-muted); grid-column: 1 / -1;">NO DATA AVAILABLE</div>';
                return;
            }

            let html = '';

            data.forEach(entry => {
                const isVisible = entry.visible;
                const isFocused = entry.focused;
                const btnClass = isVisible ? 'btn-neutral' : 'btn-success';
                const btnText = isVisible ? 'HIDE' : 'GO LIVE';
                const focusClass = isFocused ? 'is-focused' : '';
                const cardStatusClass = isVisible ? 'status-live' : 'status-hidden';

                html += `
                <div class="admin-card ${cardStatusClass}" id="card-${entry.id}">
                    <div class="card-header">
                        <span class="card-category">${categories[entry.category] || entry.category}</span>
                        <span class="card-time">${new Date(entry.created_at).toLocaleTimeString('de-DE', {hour: '2-digit', minute:'2-digit'})}</span>
                    </div>

                    <div class="card-body">${entry.text}</div>

                    <div class="card-actions">
                        <button onclick="runCmd('toggle-focus', ${entry.id})" class="btn btn-focus ${focusClass}">FOCUS</button>
                        <button onclick="runCmd('toggle-visible', ${entry.id})" class="btn ${btnClass}">${btnText}</button>
                        <button onclick="if(confirm('Delete?')) runCmd('delete', ${entry.id})" class="btn btn-danger" style="flex: 0 0 auto;">✕</button>
                    </div>
                </div>`;
            });

            feed.innerHTML = html;
        }

        async function runCmd(action, entryId = null) {
            document.body.style.cursor = 'wait';

            try {
                let url, method = 'POST';

                switch(action) {
                    case 'toggle-visible':
                        url = `/admin/entries/${entryId}/visible`;
                        break;
                    case 'toggle-focus':
                        url = `/admin/entries/${entryId}/focus`;
                        break;
                    case 'delete':
                        url = `/admin/entries/${entryId}`;
                        method = 'DELETE';
                        break;
                    case 'show-all':
                        url = '/admin/show-all';
                        break;
                    case 'hide-all':
                        url = '/admin/hide-all';
                        break;
                    case 'purge-all':
                        url = '/admin/purge-all';
                        break;
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    updateAdminBoard();
                }
            } catch (e) {
                console.error(e);
            } finally {
                document.body.style.cursor = 'default';
            }
        }

        function updateAdminBoard() {
            fetch('/data')
                .then(response => response.json())
                .then(data => {
                    // Flatten grouped data
                    const flattened = [];
                    for (let category in data) {
                        flattened.push(...data[category]);
                    }
                    renderAdmin(flattened);
                })
                .catch(err => console.error(err));
        }

        updateAdminBoard();
        setInterval(updateAdminBoard, 2000);
    </script>
</body>
</html>
