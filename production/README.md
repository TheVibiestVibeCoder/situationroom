# 🚀 Situation Room MVP - Production Code

**Laravel-based Multi-Tenant SaaS Platform**

This folder contains the complete MVP codebase for transforming your workshop tool into a subscription-based SaaS product.

---

## 📁 What's Inside

```
production/
├── app/
│   ├── Console/Commands/
│   │   └── ImportLegacyData.php         # Import old JSON data
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php      # Admin panel logic
│   │   │   ├── DashboardController.php  # Public dashboard
│   │   │   ├── SignupController.php     # Stripe signup flow
│   │   │   └── SubmitController.php     # Entry submission
│   │   └── Middleware/
│   │       └── IdentifyWorkspace.php    # Subdomain → workspace resolver
│   ├── Models/
│   │   ├── Entry.php                    # Workshop entries
│   │   ├── User.php                     # Admin users
│   │   └── Workspace.php                # Customer workspaces
│   └── Policies/
│       └── EntryPolicy.php              # Authorization rules
│
├── database/migrations/
│   ├── 2024_01_01_000001_create_workspaces_table.php
│   ├── 2024_01_01_000002_create_entries_table.php
│   └── 2024_01_01_000003_add_workspace_columns_to_users_table.php
│
├── resources/views/
│   ├── admin.blade.php                  # Admin control panel
│   ├── dashboard.blade.php              # Live public view
│   ├── signup.blade.php                 # Customer signup
│   ├── submit.blade.php                 # Entry submission form
│   ├── welcome.blade.php                # Landing page
│   ├── pdf-export.blade.php             # PDF export view
│   └── partials/
│       ├── dashboard-styles.blade.php
│       └── dashboard-scripts.blade.php
│
├── routes/
│   └── web.php                          # All application routes
│
├── scripts/
│   ├── server-setup.sh                  # Initial server setup
│   ├── deploy.sh                        # Automated deployment
│   └── backup.sh                        # Daily backup script
│
├── public/
│   └── Caddyfile                        # Web server config (auto-SSL)
│
├── bootstrap/
│   └── app.php                          # Middleware registration
│
├── config/
│   └── services.php                     # Stripe configuration
│
├── .env.example                         # Environment template
├── TODO_BRIEFING.md                     # **START HERE** - Setup guide
└── README.md                            # This file
```

---

## ✨ Features Implemented

### Core Functionality
- ✅ Multi-tenant architecture (single database, workspace_id scoping)
- ✅ Subdomain routing (customer.situationroom.eu)
- ✅ Public submission form (no login required)
- ✅ Live dashboard with auto-refresh (5-second polling)
- ✅ Admin panel with authentication
- ✅ All 5 categories from original tool
- ✅ Visibility toggle (show/hide entries)
- ✅ Focus mode (spotlight one entry)
- ✅ QR code generation
- ✅ Dark/Light theme toggle
- ✅ PDF export
- ✅ Mobile responsive

### Business Features
- ✅ Stripe subscription integration
- ✅ Self-service signup flow
- ✅ Landing page with pricing
- ✅ €49/month subscription model

### DevOps
- ✅ Automated server setup script
- ✅ One-command deployment
- ✅ Daily automated backups
- ✅ Auto-SSL with Caddy

---

## 🚀 Quick Start

**Read the full setup guide**: [`TODO_BRIEFING.md`](./TODO_BRIEFING.md)

### TL;DR for Local Testing

```bash
# 1. Create Laravel project
composer create-project laravel/laravel situation-room
cd situation-room

# 2. Copy files from this production folder
cp -r /path/to/production/* .

# 3. Install dependencies
composer require laravel/breeze --dev
php artisan breeze:install blade
composer require laravel/cashier
npm install && npm run build

# 4. Setup database
touch database/database.sqlite
php artisan migrate

# 5. Create test workspace
php artisan tinker
# (follow instructions in TODO_BRIEFING.md)

# 6. Start server
php artisan serve --host=situationroom.local
```

Visit: http://test.situationroom.local:8000

---

## 📊 Architecture Overview

### Multi-Tenancy Strategy
**Approach**: Single database with `workspace_id` foreign key

**Why not separate databases?**
- Simpler to manage
- Lower resource usage
- Easier backups
- Perfect for MVP (can migrate later if needed)

### Request Flow

```
User visits: demo.situationroom.eu
↓
IdentifyWorkspace middleware extracts "demo"
↓
Finds Workspace with subdomain="demo"
↓
Injects workspace into request
↓
Controller uses workspace_id to scope queries
```

### Data Model

```
Workspace (customer account)
├── subdomain (e.g., "raiffeisen")
├── stripe_subscription_id
└── status (active/canceled)

Entry (workshop submission)
├── workspace_id → Workspace
├── category (bildung/social/etc)
├── text
├── visible (admin control)
└── focused (spotlight mode)

User (admin login)
├── workspace_id → Workspace
├── email/password
└── role (admin/moderator)
```

---

## 🔐 Security Features

- **CSRF Protection**: All POST requests protected
- **SQL Injection**: Prevented via Eloquent ORM
- **XSS**: Blade auto-escaping
- **Authorization**: Policy-based (users can only edit their workspace)
- **Subdomain Isolation**: Middleware enforces workspace boundaries
- **HTTPS**: Auto-SSL via Caddy

---

## 💰 Business Model

**Pricing**: €49/month per workspace
**Payment**: Stripe subscriptions
**Signup Flow**:
1. User fills form (name, subdomain, email)
2. Redirects to Stripe Checkout
3. After payment: workspace activated
4. User auto-logged in to admin panel

**Test Mode**: Uses Stripe test keys (4242 4242 4242 4242)

---

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **Frontend**: Blade templates + Tailwind CSS (via CDN)
- **Database**: PostgreSQL (production) / SQLite (local)
- **Payments**: Stripe (via Laravel Cashier)
- **Auth**: Laravel Breeze
- **Web Server**: Caddy (auto-SSL)
- **Server**: Ubuntu 22.04 on Hetzner Cloud

---

## 📦 Dependencies

**Required Composer Packages**:
- `laravel/framework`: ^11.0
- `laravel/breeze`: ^2.0 (authentication scaffolding)
- `laravel/cashier`: ^15.0 (Stripe integration)

**Required NPM Packages**:
- Standard Laravel Mix setup (see package.json in Laravel)

---

## 🚦 Next Steps

1. **Read [`TODO_BRIEFING.md`](./TODO_BRIEFING.md)** - Complete setup guide
2. **Test locally** - Follow Week 1-2 from roadmap
3. **Deploy to Hetzner** - Follow Week 4 from roadmap
4. **Get first customers** - Follow Week 5-6 from roadmap

---

## ⚠️ Important Notes

### Before Production Deployment

- [ ] Change all default passwords
- [ ] Use **live** Stripe keys (not test)
- [ ] Set `APP_DEBUG=false`
- [ ] Configure real email (SMTP, not log)
- [ ] Add privacy policy page (GDPR requirement)
- [ ] Test full signup flow with real card
- [ ] Setup monitoring (error tracking)

### Not Included (Add Later)

- Real-time WebSockets (using polling for MVP)
- Custom branding per workspace
- Team collaboration
- API access
- Advanced analytics

**Why?** These add complexity. Build them only after 10+ paying customers validate demand.

---

## 🐛 Troubleshooting

**Class not found error**:
```bash
composer dump-autoload
php artisan config:clear
```

**Routes not working**:
```bash
php artisan route:clear
php artisan route:cache
```

**Styles broken**:
```bash
npm run build
php artisan view:clear
```

**500 errors**:
```bash
tail -f storage/logs/laravel.log
```

---

## 📚 Resources

- **Laravel Docs**: https://laravel.com/docs
- **Stripe Docs**: https://stripe.com/docs
- **Cashier Docs**: https://laravel.com/docs/billing
- **Support**: Laravel Discord (https://discord.gg/laravel)

---

## 📝 License

Proprietary - All rights reserved

---

**Built with the roadmap strategy**: Follow [`../roadmap.md`](../roadmap.md) for the complete go-to-market plan.

**Questions?** Check [`TODO_BRIEFING.md`](./TODO_BRIEFING.md) for detailed answers.

---

Last Updated: 2025-12-11
Version: 1.0 (MVP Complete)
Ready for deployment 🚀
