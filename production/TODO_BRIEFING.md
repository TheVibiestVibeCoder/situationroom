# 🚀 Situation Room MVP - Setup Instructions

**Status**: ✅ MVP Code Complete
**Next Step**: Set up Laravel project and deploy

---

## 📦 What You Have Here

This `production` folder contains a **complete Laravel-based MVP** of the Situation Room SaaS platform with:

- ✅ **Multi-tenant architecture** (row-based with workspace_id)
- ✅ **All 3 core views**: Dashboard, Submit Form, Admin Panel
- ✅ **Stripe payment integration** (signup & subscription)
- ✅ **Landing page** for customer acquisition
- ✅ **Database-backed** (PostgreSQL for production, SQLite for local)
- ✅ **Deployment scripts** for automated server setup
- ✅ **All features from your original PHP files**

---

## 🎯 Quick Start Path

There are **two paths** depending on your goal:

### Path A: Test Locally First (Recommended)
Follow **Section 1** to set up Laravel on your computer and test everything locally.

### Path B: Deploy Directly to Server
Follow **Section 2** to deploy straight to Hetzner and go live.

---

## 1️⃣ LOCAL DEVELOPMENT SETUP

### Step 1.1: Install Laravel Herd

**Mac/Windows**:
1. Download Laravel Herd: https://herd.laravel.com
2. Install (automatic setup of PHP, Composer, MySQL)
3. Verify installation:
   ```bash
   php --version  # Should show PHP 8.3.x
   composer --version  # Should show Composer 2.x
   ```

### Step 1.2: Create New Laravel Project

```bash
# Navigate to your projects folder
cd ~/Documents  # or wherever you keep projects

# Create new Laravel 11 project
composer create-project laravel/laravel situation-room

cd situation-room
```

### Step 1.3: Copy MVP Files Into Laravel Project

**Copy these folders from `production/` to your Laravel project:**

```bash
# Assuming you're in the Laravel project root
cp -r /path/to/production/app/Http/Controllers/* app/Http/Controllers/
cp -r /path/to/production/app/Http/Middleware/* app/Http/Middleware/
cp -r /path/to/production/app/Models/* app/Models/
cp -r /path/to/production/app/Console/Commands/* app/Console/Commands/
cp -r /path/to/production/app/Policies/* app/Policies/
cp -r /path/to/production/database/migrations/* database/migrations/
cp -r /path/to/production/resources/views/* resources/views/
cp /path/to/production/routes/web.php routes/web.php
cp /path/to/production/bootstrap/app.php bootstrap/app.php
cp /path/to/production/config/services.php config/services.php
cp /path/to/production/.env.example .env.example
```

**Or manually copy the files using your file explorer.**

### Step 1.4: Install Laravel Breeze (for authentication)

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run build
```

### Step 1.5: Install Laravel Cashier (for Stripe)

```bash
composer require laravel/cashier
php artisan vendor:publish --tag="cashier-migrations"
```

### Step 1.6: Configure Environment

```bash
# Copy the example env file
cp .env.example .env

# Generate application key
php artisan key:generate
```

**Edit `.env` file**:
```env
APP_NAME="Situation Room"
APP_URL=http://situationroom.local
APP_DOMAIN=situationroom.local
APP_MAIN_DOMAIN=situationroom.local

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/your/situation-room/database/database.sqlite

# Stripe TEST keys (get from Stripe Dashboard)
STRIPE_KEY=pk_test_your_key_here
STRIPE_SECRET=sk_test_your_secret_here
STRIPE_PRICE_ID=price_test_your_product_price_id
```

### Step 1.7: Create Database & Run Migrations

```bash
# Create SQLite database file
touch database/database.sqlite

# Run all migrations
php artisan migrate
```

### Step 1.8: Setup Local Subdomains

**Edit hosts file**:
- **Mac/Linux**: `/etc/hosts`
- **Windows**: `C:\Windows\System32\drivers\etc\hosts`

Add these lines:
```
127.0.0.1  situationroom.local
127.0.0.1  test.situationroom.local
127.0.0.1  demo.situationroom.local
```

### Step 1.9: Create Test Workspace

```bash
php artisan tinker
```

In tinker shell:
```php
$workspace = \App\Models\Workspace::create([
    'name' => 'Test Workshop',
    'subdomain' => 'test',
    'status' => 'active'
]);

$user = \App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@test.com',
    'password' => bcrypt('password'),
    'workspace_id' => $workspace->id,
    'role' => 'admin'
]);

echo "Workspace created: test.situationroom.local\n";
echo "Login: admin@test.com / password\n";
exit
```

### Step 1.10: Start Local Server

```bash
php artisan serve --host=situationroom.local --port=8000
```

### Step 1.11: Test Everything

Open in browser:
- **Landing page**: http://situationroom.local:8000
- **Workspace dashboard**: http://test.situationroom.local:8000
- **Submit form**: http://test.situationroom.local:8000/submit
- **Admin panel**: http://test.situationroom.local:8000/admin
  - Login with: `admin@test.com` / `password`

✅ **SUCCESS**: If all pages load, your MVP is working locally!

---

## 2️⃣ PRODUCTION DEPLOYMENT

### Step 2.1: Buy Hetzner Server

1. Go to https://console.hetzner.cloud
2. Create project: "Situation Room"
3. Add server:
   - Type: **CX21** (€5.83/month)
   - Location: **Nürnberg** (DSGVO-compliant)
   - Image: **Ubuntu 22.04**
   - SSH Key: Generate on your computer first

**Generate SSH key**:
```bash
ssh-keygen -t ed25519 -C "your_email@example.com"
# Press Enter 3 times
cat ~/.ssh/id_ed25519.pub  # Copy this and paste into Hetzner
```

### Step 2.2: Connect to Server

```bash
ssh root@YOUR_SERVER_IP
```

### Step 2.3: Run Server Setup Script

**On the server**, download and run the setup script:

```bash
# Upload the server-setup.sh script to server
# Or copy-paste its contents and save as setup.sh

chmod +x setup.sh
bash setup.sh
```

This will install:
- PHP 8.3
- PostgreSQL
- Caddy (auto-SSL)
- Redis
- Composer
- Node.js

**Save the database password it gives you!**

### Step 2.4: Clone Repository

```bash
cd /var/www
git clone https://github.com/YOUR-USERNAME/situation-room.git
cd situation-room
```

### Step 2.5: Setup Laravel on Server

```bash
# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Setup environment
cp .env.example .env
nano .env  # Edit configuration
```

**Edit `.env` for production**:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://situationroom.eu
APP_DOMAIN=situationroom.eu
APP_MAIN_DOMAIN=situationroom.eu

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=situationroom
DB_USERNAME=situationroom
DB_PASSWORD=your_password_from_setup_script

# IMPORTANT: Use LIVE Stripe keys for production!
STRIPE_KEY=pk_live_your_live_key
STRIPE_SECRET=sk_live_your_live_secret
STRIPE_PRICE_ID=price_live_your_price_id
```

Generate app key:
```bash
php artisan key:generate
```

### Step 2.6: Run Migrations

```bash
php artisan migrate --force
```

### Step 2.7: Set Permissions

```bash
chown -R www-data:www-data /var/www/situation-room
chmod -R 755 /var/www/situation-room/storage
chmod -R 755 /var/www/situation-room/bootstrap/cache
```

### Step 2.8: Configure Caddy

```bash
# Copy Caddyfile to system location
cp public/Caddyfile /etc/caddy/Caddyfile

# Edit with your domain
nano /etc/caddy/Caddyfile
# Change "situationroom.eu" to your domain if different

# Restart Caddy
systemctl restart caddy
```

### Step 2.9: Configure DNS

**In Cloudflare (or your DNS provider)**:

Add these DNS records:
```
Type: A     Name: @     Content: YOUR_SERVER_IP
Type: A     Name: *     Content: YOUR_SERVER_IP
```

Wait 5-10 minutes for DNS propagation.

### Step 2.10: Test Production Site

Visit: **https://situationroom.eu**

You should see:
- ✅ SSL certificate (green lock)
- ✅ Landing page loads
- ✅ No errors

### Step 2.11: Setup Automatic Backups

```bash
# Make backup script executable
chmod +x /var/www/situation-room/scripts/backup.sh

# Add to crontab (runs daily at 2 AM)
crontab -e

# Add this line:
0 2 * * * /var/www/situation-room/scripts/backup.sh
```

### Step 2.12: Create First Test Workspace

```bash
cd /var/www/situation-room
php artisan tinker
```

```php
$workspace = \App\Models\Workspace::create([
    'name' => 'Demo Workspace',
    'subdomain' => 'demo',
    'status' => 'active'
]);

$user = \App\Models\User::create([
    'name' => 'Demo Admin',
    'email' => 'admin@demo.com',
    'password' => bcrypt('SecurePassword123!'),
    'workspace_id' => $workspace->id,
    'role' => 'admin'
]);

echo "✅ Created: demo.situationroom.eu\n";
exit
```

### Step 2.13: Test Everything in Production

- **https://situationroom.eu** → Landing page
- **https://situationroom.eu/signup** → Signup form (TEST with Stripe test cards)
- **https://demo.situationroom.eu** → Demo workspace dashboard
- **https://demo.situationroom.eu/admin** → Login with `admin@demo.com`

✅ **SUCCESS**: Your MVP is live!

---

## 3️⃣ STRIPE SETUP

### Step 3.1: Create Stripe Account

1. Go to https://stripe.com
2. Sign up / Login
3. Activate account (provide business details)

### Step 3.2: Create Product & Price

**In Stripe Dashboard → Products**:
1. Click "Add Product"
2. Name: "Situation Room Workspace"
3. Description: "Monthly subscription for workshop tool"
4. Price: **€49.00 EUR** / Recurring / Monthly
5. Save

Copy the **Price ID** (starts with `price_xxx`)

### Step 3.3: Get API Keys

**In Stripe Dashboard → Developers → API Keys**:

For **testing locally**:
- Copy "Publishable key" (starts with `pk_test_`)
- Copy "Secret key" (starts with `sk_test_`)

For **production**:
- Toggle to "Live mode"
- Copy "Publishable key" (starts with `pk_live_`)
- Copy "Secret key" (starts with `sk_live_`)

### Step 3.4: Test Stripe Integration

**Test credit cards** (only work in test mode):
- Success: `4242 4242 4242 4242`
- Decline: `4000 0000 0000 0002`
- Expiry: Any future date
- CVC: Any 3 digits

Test signup:
1. Go to http://situationroom.local:8000/signup (local) or https://situationroom.eu/signup (live)
2. Fill form
3. Use test card
4. Should redirect to workspace after payment

---

## 4️⃣ IMPORTING OLD DATA

If you have existing `daten.json` from your old PHP system:

```bash
# Copy daten.json to server
scp daten.json root@YOUR_SERVER_IP:/var/www/situation-room/

# On server
cd /var/www/situation-room
php artisan import:legacy daten.json WORKSPACE_ID

# Get WORKSPACE_ID:
php artisan tinker
> \App\Models\Workspace::all(['id', 'name']);
> exit
```

---

## 5️⃣ ONGOING MAINTENANCE

### Deploy Updates

When you push code changes to GitHub:

```bash
# On server
cd /var/www/situation-room
bash scripts/deploy.sh
```

This automatically:
- Pulls latest code
- Installs dependencies
- Runs migrations
- Clears caches
- Restarts services

### Check Backups

```bash
ls -lh /root/backups
```

### View Logs

```bash
# Laravel logs
tail -f /var/www/situation-room/storage/logs/laravel.log

# Caddy logs
tail -f /var/log/caddy/situationroom-access.log
```

### Monitor Stripe

- Check Stripe Dashboard daily for:
  - New subscriptions
  - Failed payments
  - Cancellations

---

## 6️⃣ WHAT'S INCLUDED

### ✅ Features Implemented

- [x] Multi-tenant architecture (workspace-based)
- [x] Public submission form (no login required)
- [x] Live dashboard with auto-refresh (5-second polling)
- [x] Admin panel with authentication
- [x] Visibility toggle (show/hide entries)
- [x] Focus mode (spotlight one entry)
- [x] Category system (5 categories from original)
- [x] PDF export
- [x] QR code generation (via JS library)
- [x] Dark/Light theme toggle
- [x] Stripe subscription payments
- [x] Self-service signup flow
- [x] Subdomain routing
- [x] Landing page
- [x] Mobile responsive design
- [x] Deployment automation
- [x] Automatic backups

### 🚧 Not Included (Add Later)

- [ ] Real-time WebSockets (using simple polling for MVP)
- [ ] Custom branding per workspace
- [ ] Team collaboration features
- [ ] API access
- [ ] Advanced analytics
- [ ] Email notifications
- [ ] Multiple admins per workspace
- [ ] Subscription management dashboard

**These are intentionally left out for MVP. Add them only after you get 10+ paying customers!**

---

## 7️⃣ IMPORTANT NOTES

### ⚠️ Before Going Live

1. **Change all default passwords**
2. **Use LIVE Stripe keys** (not test keys)
3. **Create a real Price in Stripe** (not test price)
4. **Set APP_DEBUG=false** in .env
5. **Setup proper email** (change MAIL_MAILER from 'log' to real SMTP)
6. **Add privacy policy & terms** (required for GDPR & Stripe)
7. **Test signup flow** with real credit card
8. **Monitor error logs** daily in first week

### 📱 Support Contacts

If you get stuck:
- **Laravel**: https://laravel.com/docs
- **Stripe**: https://stripe.com/docs
- **Laravel Discord**: https://discord.gg/laravel
- **Stack Overflow**: Tag with `[laravel]`

### 💡 Quick Fixes

**"Workspace not found" error**:
```bash
# Check workspace exists
php artisan tinker
> \App\Models\Workspace::all();
```

**"Class not found" error**:
```bash
composer dump-autoload
php artisan config:clear
```

**Styling broken**:
```bash
npm run build
php artisan view:clear
```

**500 error**:
```bash
# Check logs
tail -f storage/logs/laravel.log
```

---

## 8️⃣ NEXT STEPS (After MVP Launch)

### Week 1-2: Get First Customers

1. Contact your 5 validation prospects (from roadmap Week 0)
2. Offer **50% off first 3 months** for first 3 customers
3. Schedule 15-min demos
4. Goal: 2-3 sign-ups

### Week 3-4: Iterate Based on Feedback

1. Collect feedback from first customers
2. Fix any critical bugs
3. Add most-requested feature (only if 3+ customers ask)

### Week 5-6: Expand Marketing

1. Post on LinkedIn / Twitter
2. Reach out to event agencies
3. Create demo video (Loom)
4. Goal: 5-10 total customers

### Month 3: Decision Point

**If you have 10+ customers**: Scale up! Hire support, add features
**If you have <5 customers**: Pivot or shut down (see roadmap.md)

---

## 🎉 YOU'RE READY!

You now have:
- ✅ Complete Laravel MVP codebase
- ✅ Step-by-step setup instructions
- ✅ Deployment automation
- ✅ Production-ready infrastructure

**Estimated time to first paying customer**: 2-4 weeks (following roadmap.md)

**Questions?** Re-read the [roadmap.md](../roadmap.md) for the full strategy.

**Let's build this! 🚀**

---

Last Updated: 2025-12-11
Version: 1.0 (MVP Complete)
