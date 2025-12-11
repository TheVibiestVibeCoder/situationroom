🚀 Situation Room SaaS - Transformation Roadmap
Projekt: Workshop-Tool → Multi-Tenant SaaS Platform
 Ziel: Monetarisierung durch Subscription-basiertes Modell
 Technische Komplexität: Mittel (für Nicht-Programmierer machbar mit Unterstützung)

📋 ÜBERBLICK: Was wir bauen
Aktuell: 3 PHP-Dateien auf einer Subdomain, alle Nutzer teilen sich daten.json
 Ziel: Jeder Kunde bekommt eigenen "Workspace" mit eigenem Login, eigenen Daten, eigener Subdomain
Beispiel:
raiffeisen.situationroom.eu → Raiffeisen Bank Workshop
msf.situationroom.eu → MSF Österreich Workshop
konrad-adenauer.situationroom.eu → KAS Workshop

🎯 PHASE 0: Vorbereitung & Setup (1-2 Tage)
Was du brauchst:
Services & Accounts
GitHub Account (kostenlos)


Für Code-Versionierung
Zusammenarbeit mit Entwicklern möglich
https://github.com
Hetzner Cloud Account (€10-20/Monat zum Start)


Deutscher Hosting-Anbieter (DSGVO-konform!)
Server in Nürnberg/Falkenstein
https://www.hetzner.com/cloud
Empfehlung: CX21 Server (2 vCPU, 4GB RAM) für €5.83/Monat
Domain (falls noch nicht vorhanden)


z.B. situationroom.eu oder workshoptool.at
Brauchen wir für Wildcard-Subdomains
Bei Hetzner oder Cloudflare registrieren
Stripe Account (für Zahlungen, später)


Kostenlos, Gebühren nur bei Transaktionen
https://stripe.com
Wichtig: Erst in Phase 3 nötig!
Lokale Tools (auf deinem Computer)
Visual Studio Code (kostenlos)


Code-Editor: https://code.visualstudio.com
Git (kostenlos)


Für Code-Versionierung
Windows: https://git-scm.com/download/win
Mac: bereits installiert
TablePlus oder DBeaver (kostenlos)


Für Datenbank-Ansicht
https://tableplus.com oder https://dbeaver.io

🔨 PHASE 1: Foundation - Laravel Setup (Woche 1)
Ziel: Dein PHP-Code läuft in einem modernen Framework mit Datenbank
Warum Laravel?
PHP-basiert (du kennst schon PHP!)
Beste Dokumentation in der PHP-Welt
Integrierte Multi-Tenancy Packages
Riesige Community für Hilfe
Step 1.1: Lokale Entwicklungsumgebung (2-3 Stunden)
Was du machst:
Laravel Herd installieren (einfachster Weg für Mac/Windows)

 # Download: https://herd.laravel.com
# Installiert PHP, Composer, Laravel automatisch


Neues Laravel Projekt erstellen

 cd ~/Projekte  # Oder wo immer du arbeiten willst
composer create-project laravel/laravel situation-room
cd situation-room
php artisan serve  # Startet lokalen Server


Im Browser öffnen: http://localhost:8000


Solltest du Laravel Welcome Screen sehen
Checkpoint: Laravel läuft lokal? ✅

Step 1.2: Datenbank Design (1-2 Stunden)
Was wir bauen:
┌─────────────────┐
│   workspaces    │  ← Deine Kunden
├─────────────────┤
│ id              │
│ name            │  z.B. "Raiffeisen Bank"
│ subdomain       │  z.B. "raiffeisen"
│ logo_url        │
│ plan_type       │  starter/pro/enterprise
│ created_at      │
└─────────────────┘
         │
         │ 1:N
         ▼
┌─────────────────┐
│     users       │  ← Moderatoren/Admins
├─────────────────┤
│ id              │
│ workspace_id    │
│ email           │
│ password        │
│ role            │  admin/moderator
│ created_at      │
└─────────────────┘
         │
         │ 1:N
         ▼
┌─────────────────┐
│    entries      │  ← Workshop-Beiträge
├─────────────────┤
│ id              │
│ workspace_id    │
│ category        │  bildung/social/etc
│ text            │
│ visible         │  boolean
│ focus           │  boolean
│ created_at      │
└─────────────────┘

Implementierung:
# Migrations erstellen
php artisan make:migration create_workspaces_table
php artisan make:migration create_entries_table
php artisan make:migration add_workspace_id_to_users_table

Ich helfe dir dann, die Migration-Dateien zu schreiben!

Step 1.3: Daten Migration von JSON → DB (2-3 Stunden)
Was du machst:
Migration Script erstellen

 php artisan make:command ImportLegacyData


Deine daten.json hochladen und importieren


Script liest JSON aus
Erstellt automatisch Workspace
Importiert alle Entries
Checkpoint: Alte Daten in neuer Datenbank? ✅

Step 1.4: Core Features nachbauen (3-4 Tage)
Was wir umsetzen:
A) Dashboard (index.php → resources/views/dashboard.blade.php)
Live-View der Entries
Gleiche UI wie jetzt (Dark Mode, Categories)
WebSocket statt Polling (für Echtzeit-Updates)
B) Eingabe-Formular (eingabe.php → /submit Route)
Öffentlich zugänglich (kein Login nötig für Teilnehmer)
Speichert in DB statt JSON
Gleiche Kategorien
C) Admin Panel (admin.php → /admin Routes)
Login erforderlich
Toggle visibility
Focus Mode
PDF Export
Tech Stack Details:
Frontend: Blade Templates (Laravel's Template Engine)
          + Alpine.js (für Interaktivität, wie dein jQuery)
          + Tailwind CSS (dein Custom CSS → Tailwind)

Backend:  Laravel Controllers
          Laravel Broadcasting (für Live-Updates)

Realtime: Laravel Echo + Pusher (oder Soketi - self-hosted)

Checkpoint: Alle 3 Views funktionieren lokal? ✅

🏢 PHASE 2: Multi-Tenancy (Woche 2)
Ziel: Jeder Kunde bekommt eigenen Workspace mit Isolation
Step 2.1: Tenancy Package installieren (1 Tag)
Wir nutzen: stancl/tenancy (beste Laravel Multi-Tenancy Lösung)
composer require stancl/tenancy
php artisan tenancy:install

Was das macht:
Automatische Workspace-Isolation
Subdomain-Routing
Datenbank-Separation (jeder Workspace = eigene DB)

Step 2.2: Subdomain Routing Setup (1 Tag)
Wie es funktioniert:
// routes/tenant.php (neue Datei)
Route::domain('{tenant}.situationroom.eu')->group(function () {
    Route::get('/', [DashboardController::class, 'show']);
    Route::get('/submit', [SubmitController::class, 'show']);
    Route::post('/submit', [SubmitController::class, 'store']);
    
    Route::middleware('auth')->group(function () {
        Route::get('/admin', [AdminController::class, 'index']);
        Route::post('/admin/toggle', [AdminController::class, 'toggle']);
    });
});

Testing lokal: Du musst /etc/hosts editieren:
127.0.0.1  demo.situationroom.test
127.0.0.1  test-kunde.situationroom.test

Dann kannst du http://demo.situationroom.test:8000 aufrufen!

Step 2.3: Workspace Onboarding (2 Tage)
Neuer Flow für Kunden:
Admin erstellt Workspace (du manuell, später automatisch)

 php artisan tenant:create raiffeisen "Raiffeisen Bank International"


Workspace bekommt:


Subdomain: raiffeisen.situationroom.eu
Eigene Datenbank
Admin-User mit zufälligem Passwort (wird per Email geschickt)
Kunde loggt sich ein:


Geht zu raiffeisen.situationroom.eu/admin
Loggt sich ein
Ändert Passwort
Kann Workshop starten
Features:
Workspace Settings (Logo upload, Farben ändern)
Eigene QR-Codes für Eingabe-URL
Daten-Export (CSV/PDF)
Checkpoint: Zwei Test-Workspaces laufen parallel lokal? ✅

🌐 PHASE 3: Production Deployment (Woche 3)
Ziel: System läuft auf echtem Server mit echter Domain
Step 3.1: Hetzner Server Setup (2-3 Stunden)
Was du machst:
Server erstellen in Hetzner Console


CX21 Server auswählen
Ubuntu 22.04 LTS
SSH Key hochladen (generierst du mit ssh-keygen)
Server Grundkonfiguration

 ssh root@<deine-server-ip>

# Updates
apt update && apt upgrade -y

# Firewall
ufw allow 22
ufw allow 80
ufw allow 443
ufw enable


Laravel Forge Account (optional, €12/Monat)


Automatisiert den ganzen Server-Setup
GUI für Deployments
Alternative: Laravel Envoy (kostenlos, manueller)
MIT Forge: Klick, klick, fertig (empfohlen für Nicht-Programmierer!)
 OHNE Forge: Ich gebe dir Bash-Scripts für manuelles Setup

Step 3.2: Domain & SSL Setup (1 Stunde)
Was du machst:
Domain DNS bei Cloudflare (kostenlos)


A Record: * → <deine-server-ip>
A Record: @ → <deine-server-ip>
SSL Zertifikat (automatisch mit Caddy oder Certbot)

 # Caddy installiert automatisch SSL für alle Subdomains
apt install caddy


Wildcard SSL testen


https://test.situationroom.eu sollte funktionieren
https://irgendetwas.situationroom.eu sollte funktionieren
Checkpoint: Jede Subdomain hat automatisch SSL? ✅

Step 3.3: Database Setup (1 Stunde)
Option A: Managed Database (empfohlen)
PlanetScale (kostenlos bis 5GB, dann $29/Monat)
Supabase (kostenlos bis 500MB, dann $25/Monat)
Vorteil: Automatische Backups, Skalierung
Option B: Self-Hosted PostgreSQL
apt install postgresql postgresql-contrib
# Konfiguration...

Backup Strategy:
Tägliche automatische Backups nach Hetzner Storage Box
Script mit Cron Job

Step 3.4: Deployment Automation (2-3 Stunden)
Mit Laravel Forge:
GitHub Repository mit Forge verbinden
Auto-Deploy bei Git Push aktivieren
Fertig!
Ohne Forge (manuell):
# Deployment Script
cd /var/www/situation-room
git pull origin main
composer install --no-dev
php artisan migrate --force
php artisan cache:clear
php artisan config:cache
sudo systemctl restart php8.2-fpm

Checkpoint: Code-Update geht live in 30 Sekunden? ✅

💰 PHASE 4: Monetarisierung (Woche 4)
Ziel: Kunden können selbst Accounts erstellen und bezahlen
Step 4.1: Stripe Integration (1-2 Tage)
Was du brauchst:
Stripe Account
Laravel Cashier Package
composer require laravel/cashier
php artisan cashier:install

Pricing Tiers:
// config/plans.php
return [
    'starter' => [
        'name' => 'Starter',
        'price' => 49, // EUR
        'stripe_price_id' => 'price_xxx',
        'limits' => [
            'workspaces' => 1,
            'concurrent_users' => 50,
            'data_retention_days' => 7,
        ],
    ],
    'pro' => [
        'name' => 'Professional',
        'price' => 149,
        'stripe_price_id' => 'price_yyy',
        'limits' => [
            'workspaces' => 5,
            'concurrent_users' => 200,
            'data_retention_days' => null, // unlimited
            'custom_branding' => true,
        ],
    ],
    'enterprise' => [
        'name' => 'Enterprise',
        'price' => null, // Custom pricing
        'contact_sales' => true,
    ],
];


Step 4.2: Self-Service Onboarding (2-3 Tage)
User Journey:
Landing Page: situationroom.eu


Feature-Übersicht
Pricing Table
"Start Free Trial" Button
Sign-Up Flow:

 Email eingeben
→ Workspace-Name wählen (wird zu subdomain)
→ Plan auswählen
→ Stripe Checkout
→ Account wird automatisch erstellt
→ Email mit Login-Daten
→ Redirect zu workspace.situationroom.eu/admin


Trial Period: 14 Tage kostenlos testen



Step 4.3: Admin Dashboard (für dich) (1-2 Tage)
Super-Admin Panel: admin.situationroom.eu
Features:
Übersicht aller Kunden
Umsatz-Statistiken
Support-Anfragen
Workspace manuell erstellen
Subscriptions verwalten
Usage Limits überwachen
Package: filamentphp.com (Laravel Admin Panel Generator)

📊 PHASE 5: Scaling & Optimierung (Ongoing)
Performance Optimierungen
1. Redis Caching
apt install redis-server
composer require predis/predis

Cache für Dashboard-Daten
Session Storage
Queue Jobs
2. WebSocket Server
Statt Polling alle 2 Sekunden
Laravel Echo + Soketi (self-hosted Pusher alternative)
Echtzeit-Updates für alle Clients
3. CDN für Assets
Cloudflare (kostenlos)
Bilder, CSS, JS auslagern

Monitoring & Maintenance
Tools die du brauchst:
Uptime Monitoring


UptimeRobot (kostenlos für 50 Monitors)
Benachrichtigung bei Downtime
Error Tracking


Sentry (kostenlos bis 5k Events/Monat)
Laravel Integration: composer require sentry/sentry-laravel
Analytics


Plausible (DSGVO-konform, €9/Monat)
Oder selbst gehostet: Matomo
Backups


Automated Daily DB Backups
Hetzner Storage Box (€3.20/Monat für 100GB)

💡 BONUS: Geplante Features für später
V2 Features (nach Launch)
White-Label Option: Kunden können komplett eigene Domain verwenden
API Access: Für Enterprise-Kunden (z.B. Integration in ihre Tools)
Slack/Teams Integration: Benachrichtigungen bei neuen Entries
AI-Moderation: Automatische Content-Filter für unangemessene Inhalte
Multi-Language Support: Englisch, Französisch, etc.
Mobile Apps: React Native App für iOS/Android
Advanced Analytics: Entry-Kategorisierung, Sentiment Analysis
Template Library: Vorgefertigte Workshop-Formate
Collaboration Features: Mehrere Moderatoren pro Workspace

📅 ZEITPLAN & MEILENSTEINE
Realistischer Timeline (bei 20h/Woche Arbeit):
Woche
Phase
Meilenstein
1
Foundation
Laravel läuft lokal mit DB
2
Multi-Tenancy
2 Test-Workspaces isoliert
3
Deployment
System läuft live auf Server
4
Monetarisierung
Stripe Integration fertig
5-6
Testing & Polish
Beta-Test mit 2-3 Kunden
7
Launch
Öffentlich verfügbar

Total: 6-8 Wochen bis zum Launch

💰 KOSTEN-ÜBERSICHT (Monthly Recurring)
Minimale Infrastruktur (Start):
Hetzner CX21 Server: €5.83
Domain (.eu): €1/Monat (amortisiert)
Managed Database (PlanetScale Free Tier): €0
SSL Zertifikat: €0 (Let's Encrypt)
TOTAL: ~€7/Monat
Mit ersten Kunden (10 Workspaces):
Hetzner CX31 Server (upgrade): €11.90
PlanetScale Scaler Plan: €29
Laravel Forge (optional): €12
Plausible Analytics: €9
Cloudflare Pro (optional): €20
TOTAL: ~€50-80/Monat
Bei Skalierung (50+ Kunden):
Hetzner CCX22 (4 vCPU, 8GB): €23.90
PlanetScale Scale Plan: €79
Backups & Storage: €10
Monitoring Tools: €20
TOTAL: ~€130/Monat
Break-Even: Bei €49/Monat Plan → 3 zahlende Kunden

🎓 LERN-RESSOURCEN
Für Laravel (wichtigste Skill):
Laracasts (beste Ressource!)


https://laracasts.com
"Laravel from Scratch" Serie
$15/Monat, erste Woche kostenlos
Laravel Bootcamp (kostenlos)


https://bootcamp.laravel.com
Offizielles Tutorial
Laravel Daily (YouTube Channel)


Praktische Videos zu spezifischen Features
Für Multi-Tenancy:
Stancl Tenancy Docs: https://tenancyforlaravel.com
Video Tutorial: "Multi-Tenancy in Laravel" (YouTube)
Für Deployment:
DigitalOcean Laravel Deployment Guide
Laravel Forge Video Tutorials

🆘 SUPPORT & HILFE
Wo du Hilfe bekommst:
Laravel Community:


Discord: discord.gg/laravel
Forum: laracasts.com/discuss
Reddit: r/laravel
Paid Support (bei Bedarf):


Laravel Freelancer auf Upwork (~€50-80/Stunde)
Österreichische Laravel Agencies (z.B. in Wien)
Mein Support:


Wir gehen das Schritt für Schritt gemeinsam durch
Bei jedem Step helfe ich dir konkret

✅ NÄCHSTE SCHRITTE (Action Items für DICH)
Diese Woche:
[ ] GitHub Account erstellen
[ ] Hetzner Cloud Account erstellen (noch NICHT Server buchen!)
[ ] Domain checken (hast du schon eine? Welche willst du nutzen?)
[ ] Laravel Herd installieren auf deinem Computer
[ ] Erstes Laravel Projekt erstellen (siehe Step 1.1)
Dann schreibst du mir:
"Laravel läuft, ich sehe den Welcome Screen"
"Ich hab Fragen zu [X]"
Oder: "Bei Step [Y] hänge ich"

🎯 ERFOLGS-KRITERIEN
Das Projekt ist erfolgreich wenn:
✅ 5 zahlende Kunden in ersten 3 Monaten
✅ System läuft stabil ohne tägliche Maintenance
✅ Du kannst selbst kleine Änderungen machen
✅ Kunden-Onboarding dauert <5 Minuten
✅ Wiederkehrende Revenue deckt Infrastruktur + deine Zeit
Realistisches Ziel nach 6 Monaten:
10-15 zahlende Kunden
€500-750 MRR (Monthly Recurring Revenue)
2-3 Stunden Maintenance/Woche
Pipeline für weitere Kunden

📝 SCHLUSSWORT
Das klingt nach viel Arbeit – und ja, es IST Arbeit. ABER:
Du machst das nicht alleine – ich helfe dir bei jedem Step
Es ist machbar – viele Non-Developers haben erfolgreiche SaaS gebaut
Es lohnt sich – Wiederkehrende Revenue ist Gold wert
Du lernst extrem viel – Skills die dir bei Narrative Capture auch helfen
Mein Vorschlag: Wir fangen mit Phase 1 an. Du machst die ersten Steps, wir schauen wie es läuft, und dann entscheidest du ob du weitermachen willst.
Kein Druck, kein Stress. Step by Step.
Ready to start? 🚀
Sag mir wenn du die ersten Action Items erledigt hast, dann gehts los mit der Laravel Foundation!

Erstellt: Dezember 2024
 Version: 1.0
 Nächstes Update: Nach Phase 1 Completion

