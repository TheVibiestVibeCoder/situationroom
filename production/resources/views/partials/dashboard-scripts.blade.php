<script>
    // Theme toggle
    const themeBtn = document.getElementById('themeToggle');
    const body = document.body;

    function updateIcon(isLight) {
        themeBtn.innerText = isLight ? '☾' : '☀︎';
    }

    if (localStorage.getItem('theme') === 'light') {
        body.classList.add('light-mode');
        updateIcon(true);
    }

    themeBtn.addEventListener('click', () => {
        body.classList.toggle('light-mode');
        const isLight = body.classList.contains('light-mode');
        localStorage.setItem('theme', isLight ? 'light' : 'dark');
        updateIcon(isLight);
    });

    // QR Code generation
    const currentUrl = window.location.href;
    const submitUrl = currentUrl.includes('/') ?
        currentUrl.split('/')[0] + '//' + currentUrl.split('/')[2] + '/submit' :
        currentUrl + '/submit';

    // Small QR
    new QRCode(document.getElementById("qrcodeSmall"), {
        text: submitUrl, width: 80, height: 80,
        colorDark: "#000000", colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    // Big QR (lazy load)
    const qrOverlay = document.getElementById('qrOverlay');
    const openQrBtn = document.getElementById('openQr');
    let bigQrGenerated = false;

    openQrBtn.addEventListener('click', () => {
        qrOverlay.classList.add('active');
        if (!bigQrGenerated) {
            new QRCode(document.getElementById("qrcodeBig"), {
                text: submitUrl, width: 400, height: 400,
                colorDark: "#000000", colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
            bigQrGenerated = true;
        }
    });

    qrOverlay.addEventListener('click', () => qrOverlay.classList.remove('active'));

    // Focus mode
    const focusOverlay = document.getElementById('focusOverlay');
    const focusContent = document.getElementById('focusContent');
    const board = document.getElementById('board');
    let currentFocusId = null;

    board.addEventListener('click', function(e) {
        const wrapper = e.target.closest('.idea-card-wrapper');
        if (wrapper) {
            const card = wrapper.querySelector('.idea-card');
            if (card && !card.classList.contains('blurred')) {
                focusContent.innerText = card.innerText;
                focusOverlay.classList.add('active');
            }
        }
    });

    focusOverlay.addEventListener('click', () => focusOverlay.classList.remove('active'));

    // Data rendering
    const categories = {
        'bildung': { title: 'BILDUNG & FORSCHUNG', icon: '📚' },
        'social': { title: 'SOZIALE MEDIEN', icon: '📱' },
        'individuell': { title: 'INDIV. VERANTWORTUNG', icon: '🧑' },
        'politik': { title: 'POLITIK & RECHT', icon: '⚖️' },
        'kreativ': { title: 'INNOVATIVE ANSÄTZE', icon: '💡' }
    };

    function renderData(data) {
        const existingIds = new Set();
        document.querySelectorAll('.idea-card-wrapper').forEach(el => existingIds.add(el.getAttribute('data-id')));
        const validIds = new Set();

        // Check for focused entry
        checkRemoteFocus(data);

        // Render cards
        for (let category in data) {
            const entries = data[category];
            const container = document.querySelector(`#col-${category} .card-container`);

            if (!container) continue;

            entries.forEach(entry => {
                validIds.add(entry.id);
                let wrapper = document.getElementById('wrap-' + entry.id);

                if (!wrapper) {
                    // Create new card
                    wrapper = document.createElement('div');
                    wrapper.id = 'wrap-' + entry.id;
                    wrapper.setAttribute('data-id', entry.id);
                    wrapper.className = 'idea-card-wrapper';

                    const card = document.createElement('div');
                    card.className = 'idea-card animate-in';
                    card.innerText = entry.text;

                    wrapper.appendChild(card);
                    container.insertBefore(wrapper, container.firstChild);
                } else {
                    // Update existing card
                    const card = wrapper.querySelector('.idea-card');
                    if (card && card.innerText !== entry.text) {
                        card.innerText = entry.text;
                    }
                }
            });
        }

        // Remove deleted cards
        existingIds.forEach(id => {
            if (!validIds.has(id)) {
                const el = document.getElementById('wrap-' + id);
                if (el) el.remove();
            }
        });
    }

    function checkRemoteFocus(data) {
        let focusedEntry = null;

        // Find focused entry across all categories
        for (let category in data) {
            const found = data[category].find(e => e.focused === true);
            if (found) {
                focusedEntry = found;
                break;
            }
        }

        if (focusedEntry && focusedEntry.id !== currentFocusId) {
            focusContent.innerText = focusedEntry.text;
            focusOverlay.classList.add('active');
            currentFocusId = focusedEntry.id;
        } else if (!focusedEntry && currentFocusId) {
            focusOverlay.classList.remove('active');
            currentFocusId = null;
        }
    }

    function updateBoard() {
        fetch('/data')
            .then(response => response.json())
            .then(data => renderData(data))
            .catch(err => console.error('Update failed:', err));
    }

    // Initial render
    updateBoard();

    // Poll every 5 seconds
    setInterval(updateBoard, 5000);
</script>
