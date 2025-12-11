<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation Room - Live Workshop Feedback Tool</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-900">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-6">Live Feedback für Ihre Workshops</h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Sammeln Sie Ideen, Fragen und Feedback von Ihren Teilnehmern in Echtzeit.
                Perfekt für Konferenzen, Schulungen und Team-Events.
            </p>
            <a href="{{ route('signup') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-bold hover:bg-gray-100">
                Jetzt starten - €49/Monat
            </a>
        </div>
    </div>

    <!-- Features -->
    <div class="container mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-center mb-12">Warum Situation Room?</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="text-5xl mb-4">📱</div>
                <h3 class="text-xl font-bold mb-2">Einfache Teilnahme</h3>
                <p>Teilnehmer scannen QR-Code, öffnen Link - fertig. Keine App, keine Registration.</p>
            </div>
            <div class="text-center">
                <div class="text-5xl mb-4">👀</div>
                <h3 class="text-xl font-bold mb-2">Live Dashboard</h3>
                <p>Zeigen Sie eingegangene Beiträge live auf der Leinwand. Moderieren Sie in Echtzeit.</p>
            </div>
            <div class="text-center">
                <div class="text-5xl mb-4">🔒</div>
                <h3 class="text-xl font-bold mb-2">DSGVO-konform</h3>
                <p>Server in Deutschland. Keine Weitergabe an Dritte. Ihre Daten gehören Ihnen.</p>
            </div>
        </div>
    </div>

    <!-- Pricing -->
    <div class="bg-gray-100 py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-8">Transparente Preise</h2>
            <div class="bg-white max-w-md mx-auto p-8 rounded-lg shadow-lg">
                <h3 class="text-2xl font-bold mb-4">Professional</h3>
                <div class="text-5xl font-bold mb-6">€49<span class="text-2xl text-gray-600">/Monat</span></div>
                <ul class="text-left mb-8 space-y-2">
                    <li>✅ Eigene Subdomain</li>
                    <li>✅ Unbegrenzte Workshops</li>
                    <li>✅ Bis zu 200 Teilnehmer gleichzeitig</li>
                    <li>✅ Admin-Panel mit Moderation</li>
                    <li>✅ PDF Export</li>
                    <li>✅ Email Support</li>
                    <li>✅ Jederzeit kündbar</li>
                </ul>
                <a href="{{ route('signup') }}" class="block bg-blue-600 text-white px-8 py-4 rounded-lg font-bold hover:bg-blue-700">
                    Jetzt starten
                </a>
            </div>
        </div>
    </div>

    <!-- Use Cases -->
    <div class="container mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-center mb-12">Wer nutzt Situation Room?</h2>
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-xl font-bold mb-3">🏢 Unternehmens-Trainings</h3>
                <p>"Wir nutzen Situation Room für unsere monatlichen Team-Workshops. Die Hürde zur Teilnahme ist viel niedriger als bei anderen Tools."</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-xl font-bold mb-3">🎓 Bildungseinrichtungen</h3>
                <p>"Perfekt für Feedback-Runden mit Studierenden. Endlich kommentieren auch die stillen Teilnehmer."</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-xl font-bold mb-3">🤝 NGO-Veranstaltungen</h3>
                <p>"Bei unseren Stakeholder-Dialogen sammeln wir damit strukturiert Input von bis zu 150 Personen."</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-xl font-bold mb-3">📊 Konferenzen</h3>
                <p>"Q&A Sessions werden deutlich interaktiver. Teilnehmer können anonym Fragen stellen."</p>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-blue-600 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">Bereit für Ihren ersten Workshop?</h2>
            <p class="text-xl mb-8">Setup in 5 Minuten. Jederzeit kündbar.</p>
            <a href="{{ route('signup') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-bold hover:bg-gray-100">
                Account erstellen
            </a>
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p>© 2025 Situation Room. Impressum | Datenschutz | Kontakt: hi@situationroom.eu</p>
        </div>
    </div>
</body>
</html>
