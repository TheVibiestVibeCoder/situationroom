# Situation Room MVP - Complete Execution Briefing

**From Zero to Selling Your First Subscription**

*Written for someone who knows some programming but isn't a full-stack developer*

---

## Table of Contents

1. [The Big Picture](#1-the-big-picture)
2. [Services You Need to Buy/Sign Up For](#2-services-you-need-to-buysign-up-for)
3. [Software to Install on Your Computer](#3-software-to-install-on-your-computer)
4. [Phase 1: Local Development Setup](#4-phase-1-local-development-setup)
5. [Phase 2: Understanding the Code](#5-phase-2-understanding-the-code)
6. [Phase 3: Testing Locally](#6-phase-3-testing-locally)
7. [Phase 4: Setting Up Stripe Payments](#7-phase-4-setting-up-stripe-payments)
8. [Phase 5: Buying and Setting Up Your Server](#8-phase-5-buying-and-setting-up-your-server)
9. [Phase 6: Deploying to Production](#9-phase-6-deploying-to-production)
10. [Phase 7: Going Live](#10-phase-7-going-live)
11. [Troubleshooting Guide](#11-troubleshooting-guide)
12. [Glossary of Terms](#12-glossary-of-terms)

---

## 1. The Big Picture

### What Are We Building?

You're building a **multi-tenant SaaS application**. Let me break that down:

- **SaaS** = Software as a Service. Instead of selling software once, you rent it monthly (€49/month)
- **Multi-tenant** = One application serves many customers. Each customer gets their own "workspace" (like `customer1.situationroom.eu`, `customer2.situationroom.eu`)
- **Application** = A workshop tool where participants submit ideas and moderators display them on a live dashboard

### How Does the Money Flow?

```
Customer visits situationroom.eu
        ↓
Clicks "Sign Up" → Fills form → Pays €49 via Stripe
        ↓
Stripe sends money to your bank account (minus ~2.9% fee)
        ↓
Customer gets their own subdomain (e.g., raiffeisen.situationroom.eu)
        ↓
They use it for workshops, you get €49/month recurring
```

### The Technology Stack (What Powers This)

| Component | Technology | What It Does |
|-----------|------------|--------------|
| **Backend** | PHP + Laravel | The "brain" - handles all logic, database, security |
| **Frontend** | HTML + Tailwind CSS | What users see in their browser |
| **Database** | PostgreSQL | Stores all data (users, entries, workspaces) |
| **Payments** | Stripe | Handles credit cards, subscriptions, payouts |
| **Web Server** | Caddy | Serves your website, handles HTTPS security |
| **Hosting** | Hetzner Cloud | The computer in a datacenter running your app 24/7 |
| **Domain** | Any registrar | Your address on the internet (situationroom.eu) |

---

## 2. Services You Need to Buy/Sign Up For

### 2.1 Domain Name (~€10-15/year)

**What is it?** Your address on the internet (like `situationroom.eu`)

**Where to buy:**
- [Namecheap](https://namecheap.com) - Recommended, cheap, good interface
- [Cloudflare Registrar](https://cloudflare.com) - At-cost pricing, great DNS
- [Google Domains](https://domains.google) - Simple, integrates with Google

**What to do:**
1. Go to any registrar
2. Search for your desired domain
3. Buy it (usually €10-15/year for .eu or .com)
4. You'll configure DNS later (I'll show you how)

**Why .eu?**
- Shows you're European (trust signal for EU customers)
- GDPR compliance is expected
- Alternatively: .com (international), .de (Germany), .at (Austria)

---

### 2.2 Hetzner Cloud Server (~€5-10/month)

**What is it?** A computer in a datacenter that runs your application 24/7. It's always on, always connected to fast internet.

**Why Hetzner?**
- German company = GDPR compliant, data stays in EU
- Very cheap (€4.85/month for entry level)
- Excellent performance
- Pay by the hour (can delete anytime)

**What to do:**
1. Go to [console.hetzner.cloud](https://console.hetzner.cloud)
2. Sign up with email
3. Verify identity (they may ask for ID - normal for EU hosting)
4. Add payment method (credit card or PayPal)
5. DON'T create a server yet - we'll do that in Phase 5

**Which server size?**

| Plan | RAM | CPU | Storage | Price | Good For |
|------|-----|-----|---------|-------|----------|
| CX22 | 4 GB | 2 vCPU | 40 GB | €4.85/mo | Starting out (0-50 customers) |
| CX32 | 8 GB | 4 vCPU | 80 GB | €9.59/mo | Growing (50-200 customers) |
| CX42 | 16 GB | 8 vCPU | 160 GB | €18.99/mo | Scaling (200+ customers) |

**Start with CX22** - you can upgrade with one click later.

---

### 2.3 Stripe Account (Free, ~2.9% per transaction)

**What is it?** Stripe handles all the scary payment stuff:
- Credit card processing
- Subscription management (automatic monthly billing)
- Invoices
- Sending money to your bank account
- Handling failed payments, refunds, etc.

**Why Stripe?**
- Industry standard, trusted worldwide
- Handles all PCI compliance (credit card security laws)
- Excellent developer tools
- Works in 40+ countries

**What to do:**
1. Go to [stripe.com](https://stripe.com)
2. Click "Start now" or "Create account"
3. Sign up with email
4. **Important**: You'll start in "Test Mode" (fake money for testing)
5. To receive real money, you need to "Activate your account":
   - Provide business details (can be sole proprietor/freelancer)
   - Add bank account for payouts
   - Verify identity

**Costs:**
- No monthly fee
- Per transaction: 1.5% + €0.25 (European cards) or 2.9% + €0.25 (non-European)
- Example: €49 payment → you receive ~€47.52

---

### 2.4 Cloudflare Account (Free tier is enough)

**What is it?** Cloudflare sits between your users and your server, providing:
- **DNS Management**: Translates `situationroom.eu` to your server's IP address
- **DDoS Protection**: Blocks malicious traffic trying to crash your site
- **CDN**: Caches your site globally for faster loading
- **SSL Management**: Helps with HTTPS security

**Why Cloudflare?**
- Free tier is very generous
- Makes DNS management easy
- Adds security layer
- Industry standard

**What to do:**
1. Go to [cloudflare.com](https://cloudflare.com)
2. Sign up with email
3. DON'T add your domain yet - we'll do that in Phase 6

---

### 2.5 GitHub Account (Free)

**What is it?** GitHub stores your code in the cloud and tracks all changes. Think of it as:
- Google Drive for code
- Time machine (can go back to any previous version)
- Collaboration tool (if you hire developers later)

**Why GitHub?**
- Industry standard
- Free for private repositories
- Easy deployment (pull code to server)
- Backup of all your work

**What to do:**
1. Go to [github.com](https://github.com)
2. Sign up with email
3. Verify email
4. DON'T create a repository yet - we'll do that in Phase 4

---

### Summary: Services Checklist

| Service | Cost | Sign Up Now? | Why |
|---------|------|--------------|-----|
| Domain (Namecheap/Cloudflare) | ~€12/year | Yes | Need for branding |
| Hetzner Cloud | ~€5/month | Yes (don't create server yet) | Will host your app |
| Stripe | Free + 2.9%/tx | Yes | Handle payments |
| Cloudflare | Free | Yes | DNS & security |
| GitHub | Free | Yes | Store your code |

**Total monthly cost to run:** ~€5-10/month (before you have customers)
**Break-even:** 1 customer at €49/month covers all costs

---

## 3. Software to Install on Your Computer

### 3.1 Determine Your Operating System

The instructions differ slightly based on your OS:
- **macOS** (Apple) - Easiest for web development
- **Windows** - Works great, some extra steps
- **Linux** - You probably don't need this guide :)

---

### 3.2 For macOS Users

#### Step 1: Install Homebrew (Package Manager)

**What is it?** Homebrew is like an "app store" for developer tools. Instead of downloading installers, you type one command.

Open **Terminal** (press Cmd+Space, type "Terminal", press Enter) and paste:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

This will:
- Download Homebrew
- Install it
- May ask for your password (normal, it's your Mac password)

After installation, **close and reopen Terminal**.

Verify it works:
```bash
brew --version
# Should show: Homebrew 4.x.x
```

#### Step 2: Install Laravel Herd (All-in-One PHP Setup)

**What is it?** Laravel Herd installs everything you need for PHP development in one click:
- PHP 8.3 (the programming language)
- Composer (PHP package manager)
- Node.js & npm (for frontend assets)
- Local domains (like `myproject.test`)

**What to do:**
1. Go to [herd.laravel.com](https://herd.laravel.com)
2. Download for macOS
3. Open the `.dmg` file
4. Drag Herd to Applications
5. Open Herd from Applications
6. Follow the setup wizard (click Next a few times)

Verify it works (in Terminal):
```bash
php --version
# Should show: PHP 8.3.x

composer --version
# Should show: Composer version 2.x.x

node --version
# Should show: v20.x.x or v22.x.x
```

#### Step 3: Install Git (Version Control)

**What is it?** Git tracks changes to your code. Every time you save a "checkpoint" (called a commit), you can go back to it later.

```bash
brew install git
```

Verify:
```bash
git --version
# Should show: git version 2.x.x
```

Configure Git with your identity:
```bash
git config --global user.name "Your Name"
git config --global user.email "your@email.com"
```

#### Step 4: Install Visual Studio Code (Code Editor)

**What is it?** VS Code is where you'll read and edit code. It's like Microsoft Word, but for programming.

1. Go to [code.visualstudio.com](https://code.visualstudio.com)
2. Download for macOS
3. Open the `.zip` file
4. Drag "Visual Studio Code" to Applications
5. Open VS Code

**Recommended Extensions** (install from VS Code):
- Press Cmd+Shift+X to open Extensions
- Search and install:
  - "Laravel Blade Snippets"
  - "PHP Intelephense"
  - "Tailwind CSS IntelliSense"

#### Step 5: Install TablePlus (Database Viewer)

**What is it?** A visual tool to see what's in your database. Instead of writing queries, you can browse tables like a spreadsheet.

1. Go to [tableplus.com](https://tableplus.com)
2. Download for macOS (free version is enough)
3. Install like any other app

---

### 3.3 For Windows Users

#### Step 1: Install Windows Terminal (Better Command Line)

**What is it?** Windows Terminal is a modern replacement for Command Prompt. It's prettier and supports tabs.

1. Open Microsoft Store
2. Search "Windows Terminal"
3. Install it

#### Step 2: Install Laravel Herd for Windows

**What is it?** Same as macOS - all-in-one PHP development setup.

1. Go to [herd.laravel.com](https://herd.laravel.com)
2. Download for Windows
3. Run the installer
4. Follow the setup wizard

Verify (in Windows Terminal):
```powershell
php --version
# Should show: PHP 8.3.x

composer --version
# Should show: Composer version 2.x.x

node --version
# Should show: v20.x.x
```

#### Step 3: Install Git for Windows

1. Go to [git-scm.com](https://git-scm.com)
2. Download for Windows
3. Run installer
4. **Important settings during install:**
   - Default editor: Choose "Use Visual Studio Code"
   - Line endings: Choose "Checkout as-is, commit Unix-style"
   - Accept defaults for everything else

Configure Git:
```powershell
git config --global user.name "Your Name"
git config --global user.email "your@email.com"
```

#### Step 4: Install Visual Studio Code

1. Go to [code.visualstudio.com](https://code.visualstudio.com)
2. Download for Windows
3. Run installer (check "Add to PATH" option)

Install extensions (same as macOS above).

#### Step 5: Install HeidiSQL (Database Viewer)

**What is it?** Free database viewer for Windows (alternative to TablePlus).

1. Go to [heidisql.com](https://heidisql.com)
2. Download installer
3. Install

---

### 3.4 Software Checklist

| Software | macOS | Windows | Purpose |
|----------|-------|---------|---------|
| Homebrew | ✅ Required | ❌ N/A | Package manager |
| Laravel Herd | ✅ Required | ✅ Required | PHP, Composer, Node |
| Git | ✅ Required | ✅ Required | Version control |
| VS Code | ✅ Required | ✅ Required | Code editor |
| TablePlus/HeidiSQL | ✅ Recommended | ✅ Recommended | View database |

---

## 4. Phase 1: Local Development Setup

Now we'll get the application running on your computer. This is called "local development" - you can see and test everything before putting it online.

### 4.1 Create Project Directory

Open Terminal (macOS) or Windows Terminal (Windows):

```bash
# Go to your home directory
cd ~

# Create a projects folder (if you don't have one)
mkdir -p Projects

# Go into it
cd Projects
```

### 4.2 Create a New Laravel Project

**What is Laravel?** Laravel is a PHP framework - a collection of pre-written code that handles common tasks (database, authentication, routing, etc.) so you don't have to write everything from scratch.

```bash
# Create new Laravel project called "situation-room"
composer create-project laravel/laravel situation-room

# Go into the project folder
cd situation-room
```

This creates a folder with ~15,000 files. Don't panic - most are framework files you'll never touch.

### 4.3 Copy the MVP Code

Now we copy the production code into your Laravel project.

**Option A: If you have the production folder locally**

```bash
# Assuming production folder is at ~/Projects/situationroom/production
# and you're in ~/Projects/situation-room

# Copy Controllers
cp -r ~/Projects/situationroom/production/app/Http/Controllers/* app/Http/Controllers/

# Copy Middleware
mkdir -p app/Http/Middleware
cp -r ~/Projects/situationroom/production/app/Http/Middleware/* app/Http/Middleware/

# Copy Models
cp -r ~/Projects/situationroom/production/app/Models/* app/Models/

# Copy Policies
mkdir -p app/Policies
cp -r ~/Projects/situationroom/production/app/Policies/* app/Policies/

# Copy Console Commands
mkdir -p app/Console/Commands
cp -r ~/Projects/situationroom/production/app/Console/Commands/* app/Console/Commands/

# Copy Migrations
cp -r ~/Projects/situationroom/production/database/migrations/* database/migrations/

# Copy Views
cp -r ~/Projects/situationroom/production/resources/views/* resources/views/

# Copy Routes
cp ~/Projects/situationroom/production/routes/web.php routes/web.php

# Copy Bootstrap (middleware registration)
cp ~/Projects/situationroom/production/bootstrap/app.php bootstrap/app.php

# Copy Config
cp ~/Projects/situationroom/production/config/services.php config/services.php
```

**Option B: Manually using Finder/File Explorer**

1. Open two windows: production folder and situation-room folder
2. Copy folders as listed above
3. Make sure to merge, not replace, when prompted

### 4.4 Install Required Packages

**What are packages?** Pre-written code by other developers that adds functionality. Instead of writing authentication from scratch, we install a package.

```bash
# Install Laravel Breeze (authentication system)
# --dev means it's for development tools
composer require laravel/breeze --dev

# Run Breeze installer (creates login/register pages)
php artisan breeze:install blade

# Install Laravel Cashier (Stripe integration)
composer require laravel/cashier

# Publish Cashier's database migrations
php artisan vendor:publish --tag="cashier-migrations"

# Install JavaScript dependencies
npm install

# Build CSS/JavaScript assets
npm run build
```

**What each package does:**
- **Breeze**: Adds login, register, password reset pages
- **Cashier**: Handles Stripe subscriptions, checkout, billing

### 4.5 Configure Environment Variables

**What is .env?** A file containing secret configuration (passwords, API keys). NEVER commit this to GitHub.

```bash
# Copy the example file
cp .env.example .env

# Generate application encryption key
php artisan key:generate
```

Now open `.env` in VS Code and update these values:

```bash
# Open VS Code in current directory
code .
```

In VS Code, open `.env` file (left sidebar) and change:

```env
APP_NAME="Situation Room"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://situationroom.test
APP_DOMAIN=situationroom.test
APP_MAIN_DOMAIN=situationroom.test

# Use SQLite for local development (simpler than PostgreSQL)
DB_CONNECTION=sqlite
# Comment out or remove these lines:
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

# Stripe TEST keys (we'll get these in Phase 4)
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_PRICE_ID=price_xxx
```

### 4.6 Create Database

**What is SQLite?** A simple database stored as a single file. Perfect for development. In production, we'll use PostgreSQL (more powerful).

```bash
# Create empty database file
touch database/database.sqlite
```

Update `.env` to use this file (already done if you followed 4.5).

### 4.7 Run Database Migrations

**What are migrations?** Files that describe your database structure. Instead of manually creating tables, you run migrations and Laravel creates them.

```bash
php artisan migrate
```

You'll see output like:
```
Creating migration table .............. 11ms DONE
Running migrations:
  2024_01_01_000001_create_workspaces_table ... DONE
  2024_01_01_000002_create_entries_table ...... DONE
  ...
```

### 4.8 Set Up Local Domain (Optional but Recommended)

Instead of `localhost:8000`, you can use `situationroom.test` and subdomains like `demo.situationroom.test`.

#### For macOS with Herd:

Herd does this automatically! Just:
1. Open Herd app
2. Click "Sites" → "Add Site"
3. Select your `situation-room` folder
4. It will be available at `http://situation-room.test`

To enable subdomains:
1. Open Herd
2. Click "Sites"
3. Find your site, click the "..." menu
4. Enable "Secure Site" (adds HTTPS)

#### For Windows with Herd:

Same as macOS - Herd handles it automatically.

#### Manual method (if not using Herd):

Edit your hosts file:

**macOS/Linux:** `/etc/hosts`
```bash
sudo nano /etc/hosts
```

**Windows:** `C:\Windows\System32\drivers\etc\hosts` (open as Administrator)

Add these lines:
```
127.0.0.1  situationroom.test
127.0.0.1  demo.situationroom.test
127.0.0.1  test.situationroom.test
```

### 4.9 Start the Development Server

If using Herd, your site is already running at `http://situation-room.test`

If not using Herd:
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

### 4.10 Create a Test Workspace

We need to create a workspace to test. Laravel has a tool called "Tinker" for this:

```bash
php artisan tinker
```

This opens an interactive PHP shell. Type these commands:

```php
// Create a workspace
$workspace = \App\Models\Workspace::create([
    'name' => 'Demo Workshop',
    'subdomain' => 'demo',
    'status' => 'active'
]);

// Create an admin user for this workspace
$user = \App\Models\User::create([
    'name' => 'Test Admin',
    'email' => 'admin@test.com',
    'password' => bcrypt('password'),
    'workspace_id' => $workspace->id,
    'role' => 'admin'
]);

// Verify creation
echo "Workspace ID: " . $workspace->id . "\n";
echo "User ID: " . $user->id . "\n";
echo "Login: admin@test.com / password\n";

// Exit tinker
exit
```

---

## 5. Phase 2: Understanding the Code

Before testing, let's understand what each part does. This will help you fix issues and customize later.

### 5.1 Folder Structure

```
situation-room/
├── app/                    # Your application code
│   ├── Http/
│   │   ├── Controllers/    # Handle requests (the "C" in MVC)
│   │   │   ├── AdminController.php      # Admin panel logic
│   │   │   ├── DashboardController.php  # Public dashboard
│   │   │   ├── SignupController.php     # Customer signup
│   │   │   └── SubmitController.php     # Entry submission
│   │   └── Middleware/
│   │       └── IdentifyWorkspace.php    # Detects subdomain
│   ├── Models/             # Data structures (the "M" in MVC)
│   │   ├── User.php        # User accounts
│   │   ├── Workspace.php   # Customer accounts
│   │   └── Entry.php       # Workshop submissions
│   └── Policies/
│       └── EntryPolicy.php # Authorization rules
│
├── database/
│   └── migrations/         # Database structure definitions
│
├── resources/
│   └── views/              # HTML templates (the "V" in MVC)
│       ├── dashboard.blade.php  # Live display
│       ├── submit.blade.php     # Entry form
│       ├── admin.blade.php      # Control panel
│       └── welcome.blade.php    # Landing page
│
├── routes/
│   └── web.php            # URL → Controller mapping
│
├── .env                   # Secret configuration (don't commit!)
└── .env.example           # Template for .env
```

### 5.2 How a Request Flows

When someone visits `demo.situationroom.eu`:

```
1. Browser requests: demo.situationroom.eu
        ↓
2. Web server (Caddy) receives request
        ↓
3. Caddy passes to PHP/Laravel
        ↓
4. Laravel checks routes/web.php for matching URL
        ↓
5. IdentifyWorkspace middleware runs:
   - Extracts "demo" from subdomain
   - Finds Workspace where subdomain = "demo"
   - Attaches workspace to request
        ↓
6. DashboardController@show runs:
   - Gets workspace from request
   - Fetches visible entries for this workspace
   - Returns dashboard.blade.php with data
        ↓
7. Blade template renders HTML
        ↓
8. HTML sent to browser
```

### 5.3 Key Files Explained

#### `routes/web.php` - URL Routing

```php
// Main domain (situationroom.eu) - landing page and signup
Route::domain(config('app.main_domain'))->group(function () {
    Route::get('/', function () {
        return view('welcome');  // Show landing page
    });
    Route::get('/signup', [SignupController::class, 'show']);
    Route::post('/signup', [SignupController::class, 'store']);
});

// Subdomain routes (*.situationroom.eu) - workspace features
Route::middleware(['workspace'])->group(function () {
    Route::get('/', [DashboardController::class, 'show']);  // Dashboard
    Route::get('/submit', [SubmitController::class, 'show']); // Form
    Route::middleware('auth')->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index']); // Admin panel
    });
});
```

**Translation:**
- Visit `situationroom.eu` → see landing page
- Visit `situationroom.eu/signup` → see signup form
- Visit `demo.situationroom.eu` → see demo workspace dashboard
- Visit `demo.situationroom.eu/admin` → see admin panel (must be logged in)

#### `app/Models/Workspace.php` - Data Model

```php
class Workspace extends Model
{
    use Billable;  // Adds Stripe subscription features

    protected $fillable = [
        'name',           // "Raiffeisen Bank"
        'subdomain',      // "raiffeisen"
        'stripe_customer_id',
        'stripe_subscription_id',
        'status',         // "active", "canceled", "past_due"
    ];

    // Relationships
    public function users() {
        return $this->hasMany(User::class);  // Workspace has many users
    }

    public function entries() {
        return $this->hasMany(Entry::class); // Workspace has many entries
    }
}
```

#### `app/Http/Middleware/IdentifyWorkspace.php` - Subdomain Detection

```php
public function handle(Request $request, Closure $next)
{
    // Get hostname: "demo.situationroom.eu"
    $host = $request->getHost();

    // Split by dots: ["demo", "situationroom", "eu"]
    $parts = explode('.', $host);

    // First part is subdomain: "demo"
    $subdomain = $parts[0];

    // Find workspace in database
    $workspace = Workspace::where('subdomain', $subdomain)->first();

    if (!$workspace) {
        abort(404, 'Workspace not found');
    }

    // Attach to request so controllers can use it
    $request->attributes->set('workspace', $workspace);

    return $next($request);
}
```

### 5.4 Database Tables

#### `workspaces` table
| Column | Type | Purpose |
|--------|------|---------|
| id | integer | Unique identifier |
| name | string | Company name ("Raiffeisen Bank") |
| subdomain | string | URL prefix ("raiffeisen") |
| stripe_customer_id | string | Stripe's customer ID |
| stripe_subscription_id | string | Stripe's subscription ID |
| status | enum | "active", "canceled", "past_due" |
| created_at | datetime | When created |

#### `entries` table
| Column | Type | Purpose |
|--------|------|---------|
| id | integer | Unique identifier |
| workspace_id | integer | Which workspace this belongs to |
| category | string | "bildung", "social", etc. |
| text | text | The actual submission |
| visible | boolean | Show on dashboard? |
| focused | boolean | Spotlight mode? |
| created_at | datetime | When submitted |

#### `users` table
| Column | Type | Purpose |
|--------|------|---------|
| id | integer | Unique identifier |
| workspace_id | integer | Which workspace they admin |
| name | string | User's name |
| email | string | Login email |
| password | string | Hashed password |
| role | string | "admin" or "moderator" |

---

## 6. Phase 3: Testing Locally

Now let's verify everything works before going to production.

### 6.1 Test the Dashboard

1. Open browser
2. Visit: `http://demo.situationroom.test` (with Herd) or `http://localhost:8000` (without)
3. You should see the dashboard with 5 empty columns

**If you see "Workspace not found":**
- Make sure you created the workspace in Tinker (step 4.10)
- Check your hosts file has the subdomain entry
- Check `.env` has correct `APP_DOMAIN`

### 6.2 Test the Submit Form

1. Visit: `http://demo.situationroom.test/submit`
2. Select a category
3. Enter some text
4. Click "Antwort Senden"
5. You should see success message

**Note:** Entries start as `visible = false`. You need to approve them in admin.

### 6.3 Test the Admin Panel

1. Visit: `http://demo.situationroom.test/admin`
2. You'll be redirected to login
3. Login with: `admin@test.com` / `password`
4. You should see the admin panel with the entry you just submitted
5. Click the eye icon to make it visible
6. Go back to dashboard - entry should appear

### 6.4 Test Checklist

| Test | URL | Expected Result |
|------|-----|-----------------|
| Landing page | `situationroom.test` | See marketing page |
| Signup form | `situationroom.test/signup` | See signup form |
| Dashboard | `demo.situationroom.test` | See 5-column layout |
| Submit form | `demo.situationroom.test/submit` | Can submit entry |
| Admin panel | `demo.situationroom.test/admin` | Login → see entries |
| Toggle visibility | Admin panel | Click eye → entry shows on dashboard |
| Focus mode | Admin panel | Click spotlight → entry shows in overlay |

---

## 7. Phase 4: Setting Up Stripe Payments

### 7.1 Get Your Stripe API Keys

1. Log into [dashboard.stripe.com](https://dashboard.stripe.com)
2. Make sure you're in **Test mode** (toggle in bottom-left)
3. Go to **Developers** → **API keys**
4. You'll see:
   - **Publishable key**: `pk_test_...` (safe to expose in frontend)
   - **Secret key**: `sk_test_...` (keep secret!)

Copy both keys.

### 7.2 Create a Product and Price

1. In Stripe Dashboard, go to **Products**
2. Click **+ Add product**
3. Fill in:
   - **Name**: "Situation Room Workspace"
   - **Description**: "Monthly subscription for interactive workshop tool"
4. Under **Pricing**, click **Add price**:
   - **Price**: 49.00
   - **Currency**: EUR
   - **Billing period**: Monthly
   - **Recurring**
5. Click **Save product**

6. Find your new product, click on it
7. Under Pricing, copy the **Price ID**: `price_1Abc123...`

### 7.3 Update Your .env File

Open `.env` in VS Code and update:

```env
STRIPE_KEY=pk_test_51Abc...your_publishable_key
STRIPE_SECRET=sk_test_51Abc...your_secret_key
STRIPE_PRICE_ID=price_1Abc...your_price_id
```

### 7.4 Test the Signup Flow

1. Visit `http://situationroom.test/signup`
2. Fill in the form:
   - Name: "Test Company"
   - Subdomain: "testcompany"
   - Email: "test@example.com"
   - Password: "password123"
3. Click Sign Up
4. You'll be redirected to Stripe Checkout
5. Use test card: `4242 4242 4242 4242`
   - Expiry: Any future date (e.g., 12/25)
   - CVC: Any 3 digits (e.g., 123)
6. Click Pay
7. You should be redirected to the admin panel of your new workspace!

### 7.5 Verify in Stripe Dashboard

1. Go to Stripe Dashboard → **Customers**
2. You should see a new customer
3. Go to **Subscriptions**
4. You should see an active subscription for €49/month

**Congratulations!** You've completed a full signup flow with payments.

---

## 8. Phase 5: Buying and Setting Up Your Server

Now let's put this on the internet.

### 8.1 Create Your Hetzner Server

1. Log into [console.hetzner.cloud](https://console.hetzner.cloud)
2. Click **+ Create Server**
3. Configure:

| Setting | Value | Why |
|---------|-------|-----|
| **Location** | Nuremberg or Falkenstein | Germany = GDPR compliant |
| **Image** | Ubuntu 22.04 | Stable, well-supported |
| **Type** | CX22 (€4.85/mo) | Enough for starting out |
| **Networking** | Public IPv4 | You need a public IP address |
| **SSH Key** | See below | Secure login without password |
| **Name** | situation-room | Just a label |

### 8.2 Create SSH Key (Secure Login)

**What is SSH?** A secure way to connect to your server. Instead of a password (can be guessed), you use a cryptographic key pair.

On your computer (Terminal/Windows Terminal):

```bash
# Generate SSH key pair
ssh-keygen -t ed25519 -C "your@email.com"
```

Press Enter 3 times (accept defaults, no passphrase for simplicity).

This creates two files:
- `~/.ssh/id_ed25519` - Private key (NEVER share this)
- `~/.ssh/id_ed25519.pub` - Public key (safe to share)

Copy your public key:

```bash
cat ~/.ssh/id_ed25519.pub
```

Output looks like: `ssh-ed25519 AAAAC3Nza... your@email.com`

In Hetzner:
1. Click **Add SSH Key**
2. Paste your public key
3. Give it a name

### 8.3 Create the Server

Click **Create & Buy Now**.

Wait ~30 seconds. Your server is now running!

Note your server's **IP address** (e.g., `157.180.xxx.xxx`)

### 8.4 Connect to Your Server

```bash
ssh root@YOUR_SERVER_IP
```

First time: Type `yes` when asked about fingerprint.

You're now connected! You should see:
```
root@situation-room:~#
```

### 8.5 Run Server Setup Script

Copy the setup script to your server. You have two options:

**Option A: Paste directly**

1. On your local computer, open `production/scripts/server-setup.sh`
2. Copy all contents
3. On server, create file:

```bash
nano setup.sh
```

4. Paste contents (right-click in terminal)
5. Press Ctrl+X, then Y, then Enter to save
6. Make executable and run:

```bash
chmod +x setup.sh
bash setup.sh
```

**Option B: SCP (secure copy)**

From your local computer:
```bash
scp ~/Projects/situationroom/production/scripts/server-setup.sh root@YOUR_SERVER_IP:~/setup.sh
ssh root@YOUR_SERVER_IP
chmod +x setup.sh
bash setup.sh
```

### 8.6 What the Setup Script Does

The script installs:
- **PHP 8.3**: The programming language
- **PostgreSQL**: Production database
- **Caddy**: Web server with automatic HTTPS
- **Redis**: Caching (makes things faster)
- **Node.js**: For building frontend assets
- **Composer**: PHP package manager

It will ask you to enter a database password. **Write this down!**

### 8.7 Verify Installation

After script completes:

```bash
php --version
# PHP 8.3.x

psql --version
# psql 14.x

caddy version
# v2.x.x

node --version
# v20.x.x
```

---

## 9. Phase 6: Deploying to Production

### 9.1 Push Your Code to GitHub

First, let's get your code on GitHub so we can pull it to the server.

#### Create GitHub Repository

1. Go to [github.com](https://github.com)
2. Click **+** → **New repository**
3. Name: `situation-room`
4. Visibility: **Private** (your code, your business)
5. Don't initialize with README (we have code already)
6. Click **Create repository**

#### Push Local Code to GitHub

On your local computer, in the project folder:

```bash
cd ~/Projects/situation-room

# Initialize git repository (if not already)
git init

# Create .gitignore to exclude sensitive files
# Laravel already has one, but verify .env is listed

# Add all files
git add .

# Create first commit
git commit -m "Initial MVP commit"

# Add GitHub as remote
git remote add origin https://github.com/YOUR_USERNAME/situation-room.git

# Push to GitHub
git branch -M main
git push -u origin main
```

### 9.2 Clone Code to Server

On your server (SSH):

```bash
# Go to web directory
cd /var/www

# Clone your repository
git clone https://github.com/YOUR_USERNAME/situation-room.git
cd situation-room
```

If repository is private, you'll need to authenticate. Use a Personal Access Token:
1. GitHub → Settings → Developer Settings → Personal Access Tokens → Generate
2. Use token as password when prompted

### 9.3 Configure Server Environment

```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies
npm install

# Build assets
npm run build

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit environment
nano .env
```

Update `.env` for production:

```env
APP_NAME="Situation Room"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://situationroom.eu
APP_DOMAIN=situationroom.eu
APP_MAIN_DOMAIN=situationroom.eu

# PostgreSQL (use password from setup script)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=situationroom
DB_USERNAME=situationroom
DB_PASSWORD=YOUR_DATABASE_PASSWORD_HERE

# Stripe LIVE keys (get from Stripe dashboard, toggle to Live mode)
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
STRIPE_PRICE_ID=price_live_...

# Session
SESSION_DRIVER=database

# Cache
CACHE_STORE=redis

# Mail (use a real email service)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your_mailgun_username
MAIL_PASSWORD=your_mailgun_password
MAIL_FROM_ADDRESS=hello@situationroom.eu
MAIL_FROM_NAME="Situation Room"
```

Save and exit (Ctrl+X, Y, Enter).

### 9.4 Run Migrations

```bash
php artisan migrate --force
```

The `--force` flag is required in production.

### 9.5 Set File Permissions

```bash
# Set owner to web server user
chown -R www-data:www-data /var/www/situation-room

# Set directory permissions
chmod -R 755 /var/www/situation-room/storage
chmod -R 755 /var/www/situation-room/bootstrap/cache
```

### 9.6 Configure Caddy Web Server

```bash
# Create Caddy configuration
nano /etc/caddy/Caddyfile
```

Paste this configuration:

```
# Main domain
situationroom.eu {
    root * /var/www/situation-room/public
    php_fastcgi unix//run/php/php8.3-fpm.sock
    file_server
    encode gzip

    # Security headers
    header {
        X-Frame-Options "SAMEORIGIN"
        X-Content-Type-Options "nosniff"
        X-XSS-Protection "1; mode=block"
        Referrer-Policy "strict-origin-when-cross-origin"
    }

    log {
        output file /var/log/caddy/situationroom-access.log
    }
}

# Wildcard for all subdomains
*.situationroom.eu {
    root * /var/www/situation-room/public
    php_fastcgi unix//run/php/php8.3-fpm.sock
    file_server
    encode gzip

    header {
        X-Frame-Options "SAMEORIGIN"
        X-Content-Type-Options "nosniff"
    }

    log {
        output file /var/log/caddy/situationroom-wildcard-access.log
    }

    tls {
        dns cloudflare {env.CLOUDFLARE_API_TOKEN}
    }
}
```

**Important:** Wildcard SSL certificates require DNS validation. We'll set up Cloudflare for this.

### 9.7 Configure Cloudflare DNS

1. Log into [cloudflare.com](https://cloudflare.com)
2. Click **Add site**
3. Enter: `situationroom.eu`
4. Select **Free** plan
5. Cloudflare will scan existing DNS records
6. **Change your domain's nameservers** at your registrar to Cloudflare's nameservers (they'll show you which ones)

Add these DNS records in Cloudflare:

| Type | Name | Content | Proxy status |
|------|------|---------|--------------|
| A | @ | YOUR_SERVER_IP | Proxied |
| A | * | YOUR_SERVER_IP | DNS only |

**Why "DNS only" for wildcard?** Caddy needs to handle SSL for subdomains directly.

### 9.8 Create Cloudflare API Token (for wildcard SSL)

1. Cloudflare Dashboard → Profile → API Tokens
2. Create Token → Use template "Edit zone DNS"
3. Zone Resources: Select `situationroom.eu`
4. Create Token
5. Copy the token

On server, add to environment:

```bash
# Add Cloudflare token to Caddy
nano /etc/systemd/system/caddy.service.d/override.conf
```

Add:
```
[Service]
Environment="CLOUDFLARE_API_TOKEN=your_token_here"
```

Reload systemd and restart Caddy:

```bash
systemctl daemon-reload
systemctl restart caddy
```

### 9.9 Verify Deployment

1. Wait 5-10 minutes for DNS propagation
2. Visit `https://situationroom.eu`
3. You should see your landing page with a green padlock (HTTPS)

---

## 10. Phase 7: Going Live

### 10.1 Switch Stripe to Live Mode

**Important:** Only do this when you're ready for real payments!

1. Stripe Dashboard → Toggle from "Test" to "Live" mode
2. Developers → API keys → Copy **live** keys
3. Products → Create same product with **live** pricing
4. Copy **live** price ID
5. Update server `.env` with live keys:

```bash
ssh root@YOUR_SERVER_IP
nano /var/www/situation-room/.env
```

Update:
```env
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
STRIPE_PRICE_ID=price_live_...
```

### 10.2 Create Your First Real Test

Before announcing:

1. Visit `https://situationroom.eu/signup`
2. Sign up with a **real** email you own
3. Pay with your **real** credit card (€49)
4. Test the full flow
5. Cancel the subscription if needed (Stripe Dashboard → Subscriptions → Cancel)

### 10.3 Set Up Monitoring

You need to know when things break.

#### Option 1: Free - UptimeRobot
1. Sign up at [uptimerobot.com](https://uptimerobot.com)
2. Add monitor: `https://situationroom.eu`
3. Set interval: 5 minutes
4. Add your email for alerts

#### Option 2: Free - Laravel Logs
Check logs regularly:
```bash
ssh root@YOUR_SERVER_IP
tail -f /var/www/situation-room/storage/logs/laravel.log
```

### 10.4 Set Up Automatic Backups

On server:

```bash
# Copy backup script
cp /var/www/situation-room/scripts/backup.sh /root/backup.sh
chmod +x /root/backup.sh

# Test it
bash /root/backup.sh

# Add to crontab (runs daily at 2 AM)
crontab -e
```

Add this line:
```
0 2 * * * /root/backup.sh >> /var/log/backup.log 2>&1
```

### 10.5 Final Checklist Before Launch

| Item | Status |
|------|--------|
| Domain points to server | ⬜ |
| HTTPS works on main domain | ⬜ |
| HTTPS works on subdomains | ⬜ |
| Landing page loads | ⬜ |
| Signup flow works | ⬜ |
| Stripe is in LIVE mode | ⬜ |
| Test payment succeeded | ⬜ |
| Workspace dashboard works | ⬜ |
| Submit form works | ⬜ |
| Admin panel works | ⬜ |
| Backups configured | ⬜ |
| Monitoring configured | ⬜ |
| Privacy policy page exists | ⬜ |
| Terms of service page exists | ⬜ |

---

## 11. Troubleshooting Guide

### "Workspace not found"

**Cause:** Subdomain doesn't exist in database or middleware isn't running.

**Fix:**
```bash
php artisan tinker
> Workspace::all();
# Check if subdomain exists

# If not, create it:
> Workspace::create(['name' => 'Test', 'subdomain' => 'test', 'status' => 'active']);
```

### "Class not found" error

**Cause:** Autoloader not updated after adding files.

**Fix:**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### 500 Internal Server Error

**Cause:** PHP error. Check logs.

**Fix:**
```bash
# On server
tail -50 /var/www/situation-room/storage/logs/laravel.log
```

Common issues:
- Missing `.env` values
- Database connection failed
- Permission denied on storage/

### CSS/Styles broken

**Cause:** Assets not built or cached.

**Fix:**
```bash
npm run build
php artisan view:clear
php artisan cache:clear
```

### Stripe checkout redirects to error

**Cause:** Invalid price ID or API keys.

**Fix:**
1. Check `.env` has correct `STRIPE_PRICE_ID`
2. Verify price exists in Stripe Dashboard
3. Make sure you're using correct mode (test vs live)

### Can't connect to server via SSH

**Cause:** Firewall, wrong IP, or SSH key issues.

**Fix:**
```bash
# Check you're using correct IP
ping YOUR_SERVER_IP

# If using Hetzner Console, you can access via web
# Hetzner Dashboard → Server → Console

# Check firewall
ufw status
# Should show: 22, 80, 443 allowed
```

### Database migration fails

**Cause:** Database doesn't exist or wrong credentials.

**Fix:**
```bash
# On server
sudo -u postgres psql
# Check database exists:
\l
# Check user exists:
\du
# Exit: \q

# If database doesn't exist:
sudo -u postgres psql -c "CREATE DATABASE situationroom;"
```

---

## 12. Glossary of Terms

| Term | Definition |
|------|------------|
| **API** | Application Programming Interface - a way for programs to talk to each other |
| **Backend** | The server-side code that users don't see |
| **Cache** | Temporary storage to make things faster |
| **CLI** | Command Line Interface - text-based way to control a computer |
| **Commit** | A saved checkpoint in Git |
| **Controller** | Laravel component that handles requests |
| **CORS** | Cross-Origin Resource Sharing - security feature for web browsers |
| **CSRF** | Cross-Site Request Forgery - a type of attack Laravel protects against |
| **DNS** | Domain Name System - translates domains to IP addresses |
| **Eloquent** | Laravel's database system |
| **ENV** | Environment variables - configuration that changes per environment |
| **Framework** | Pre-written code that provides structure |
| **Frontend** | What users see in their browser |
| **Git** | Version control system that tracks code changes |
| **HTTPS** | Secure version of HTTP (encrypted) |
| **IP Address** | Numerical address of a computer on the internet |
| **Laravel** | PHP framework for web applications |
| **Middleware** | Code that runs before/after requests |
| **Migration** | Code that describes database structure |
| **Model** | Laravel component representing a database table |
| **MVC** | Model-View-Controller - architecture pattern |
| **npm** | Node Package Manager - installs JavaScript packages |
| **ORM** | Object-Relational Mapping - database abstraction |
| **PHP** | Programming language for web servers |
| **PostgreSQL** | Database management system |
| **Repository** | A Git project folder |
| **Route** | Maps URLs to code |
| **SaaS** | Software as a Service |
| **SSH** | Secure Shell - encrypted remote access |
| **SSL/TLS** | Security protocols for HTTPS |
| **Subdomain** | Prefix before main domain (demo.situationroom.eu) |
| **Subscription** | Recurring payment |
| **Terminal** | Text-based interface to computer |
| **View** | Laravel component that renders HTML |
| **Webhook** | HTTP callback - server notifying another server |

---

## You're Ready!

If you've followed this guide, you now have:

- ✅ All necessary services set up
- ✅ Development environment on your computer
- ✅ Understanding of how the code works
- ✅ Working local version
- ✅ Live production server
- ✅ Payment processing
- ✅ Knowledge to troubleshoot issues

**Next step:** Get your first customer!

The code is ready. The infrastructure is ready. Now it's time for sales.

Refer to `../roadmap.md` for go-to-market strategy.

---

*Document created: 2025-12-11*
*Version: 1.0*
*For: Situation Room MVP Launch*
