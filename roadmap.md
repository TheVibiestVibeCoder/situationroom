# 🚀 Situation Room SaaS - MVP Launch Roadmap

**Mission**: Get 10 paying customers in 8 weeks to validate the market

**Current State**: 3 PHP files (index.php, eingabe.php, admin.php) + daten.json
**Goal**: Multi-customer SaaS with login & payments - as SIMPLE as possible

---

## 🎯 SUCCESS DEFINITION

You'll know this is working when:
- ✅ **Week 4**: First paying customer using their own workspace
- ✅ **Week 8**: 5-10 paying customers, €250-500/month revenue
- ✅ **Week 12**: Decision point - scale up or shut down

**Not going well?** Stop at Week 8, you've only spent €50 and 80 hours. That's a cheap lesson.

---

## 📋 MVP FEATURES (What we're building)

### What Customers Get:
1. **Their own subdomain**: `raiffeisen.situationroom.eu`
2. **Their own workspace**: Only they see their data
3. **Three pages** (exactly what you have now):
   - Public entry form (`/submit`)
   - Live dashboard view (`/`)
   - Admin panel with login (`/admin`)
4. **All your current features**: Categories, visibility toggle, focus mode, PDF export

### What Customers DON'T Get (Yet):
- ❌ Custom branding/logos (all workspaces look the same)
- ❌ Real-time WebSockets (we use simple auto-refresh)
- ❌ Advanced analytics
- ❌ Team collaboration features
- ❌ API access

**Rule**: If it's not in the "What Customers Get" list, we're NOT building it for MVP.

---

## 💰 PRICING (Keep It Simple)

**One Plan, One Price**: €49/month
- Unlimited workshop sessions
- Up to 200 concurrent participants
- 30 days data retention
- Email support

**Why one plan?**
- Easier to explain
- Easier to build
- Easier to sell
- You can add tiers later after 10 customers

**Payment**: Stripe subscription - they pay first, then get access (no free trials for MVP)

---

## 🗓️ THE PLAN (8 Weeks to First Revenue)

### WEEK 0: Pre-Work & Validation (Before coding anything!)

**⏱ Time needed: 5-10 hours**

#### Step 0.1: Customer Discovery (2-3 hours)

**You need to talk to 5-10 potential customers BEFORE building.**

Questions to ask them:
1. "How often do you run workshops like this?" (establishes frequency)
2. "What do you currently use for live audience input?" (competition research)
3. "What's annoying about your current solution?" (pain points)
4. "Would you pay €49/month for your own instance of this tool?" (pricing validation)
5. "Can I send you a link when it's ready in 4 weeks?" (pipeline building)

**Target contacts**:
- Past workshop clients (Raiffeisen, MSF, KAS if you worked with them)
- Event agencies in Austria/Germany
- Corporate training departments
- Universities (faculty development offices)
- NGOs that run stakeholder workshops

**Success criteria**: At least 5 people say "yes, send me the link"

#### Step 0.2: Setup Accounts (1 hour)

Create these accounts (all free to start):
- [ ] **GitHub**: https://github.com/signup (for code storage)
- [ ] **Stripe**: https://stripe.com (for payments - use test mode)
- [ ] **Hetzner Cloud**: https://console.hetzner.cloud/register (DON'T buy server yet!)

#### Step 0.3: Check Your Domain (30 min)

Do you own a domain? Options:
- **Have one**: situationroom.eu or similar → Perfect, use that
- **Don't have one**:
  - Buy at Cloudflare ($10/year): https://www.cloudflare.com/products/registrar/
  - Or Hetzner ($8/year): https://www.hetzner.com/domainregistration
  - Tip: `.eu`, `.com`, or country domain (.at, .de)

**You need**: A domain that supports wildcard subdomains (any domain works)

#### Step 0.4: Local Development Setup (2-3 hours)

**Install on your computer**:

**A) Laravel Herd (Easiest option - Mac/Windows)**
1. Download: https://herd.laravel.com
2. Install (it's a normal installer, click next-next-done)
3. Open terminal/command prompt, type: `php --version`
   - Should see: `PHP 8.3.x`
4. Type: `composer --version`
   - Should see: `Composer version 2.x`

✅ **Checkpoint**: Can you see PHP and Composer versions? If yes, continue.

**B) Create Test Laravel Project**
```bash
# Open Terminal (Mac) or Command Prompt (Windows)
cd ~/Documents  # or wherever you keep projects
composer create-project laravel/laravel situation-room-test
cd situation-room-test
php artisan serve
```

Open browser: http://localhost:8000

✅ **Checkpoint**: Do you see Laravel welcome page? Screenshot it! If yes, you're ready.

---

### WEEK 1-2: Laravel Foundation (Build Core Features)

**⏱ Time needed: 20-30 hours**
**Goal**: Your 3 PHP files working in Laravel with a database

#### Step 1.1: Create Real Project (30 min)

```bash
cd ~/Documents  # or your projects folder
composer create-project laravel/laravel situation-room
cd situation-room
git init
git add .
git commit -m "Initial Laravel setup"
```

Create GitHub repository:
1. Go to https://github.com/new
2. Name: `situation-room`
3. DON'T initialize with README
4. Copy the commands they show

```bash
git remote add origin https://github.com/YOUR-USERNAME/situation-room.git
git branch -M main
git push -u origin main
```

✅ **Checkpoint**: Your code is on GitHub?

#### Step 1.2: Database Setup (1 hour)

**Open `.env` file** in VS Code (in your situation-room folder):

Find these lines:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Change to:
```
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/your/situation-room/database/database.sqlite
# For Mac: /Users/yourname/Documents/situation-room/database/database.sqlite
# For Windows: C:\Users\yourname\Documents\situation-room\database\database.sqlite
```

**Why SQLite?** Simpler for local development. We'll use PostgreSQL in production.

Create the database file:
```bash
touch database/database.sqlite  # Mac/Linux
# Windows: New-Item database/database.sqlite -ItemType File (in PowerShell)
```

Test it:
```bash
php artisan migrate
```

Should see: "Migration table created successfully" + several migrations.

✅ **Checkpoint**: Migrations ran without errors?

#### Step 1.3: Design Database Schema (2 hours)

We need 3 tables. Create migrations:

```bash
php artisan make:migration create_workspaces_table
php artisan make:migration create_entries_table
php artisan make:migration add_workspace_columns_to_users_table
```

**Now edit the migration files** (in `database/migrations/` folder):

**File 1: `xxxx_create_workspaces_table.php`**
```php
public function up()
{
    Schema::create('workspaces', function (Blueprint $table) {
        $table->id();
        $table->string('name');                    // "Raiffeisen Bank"
        $table->string('subdomain')->unique();     // "raiffeisen"
        $table->string('stripe_subscription_id')->nullable();
        $table->string('stripe_customer_id')->nullable();
        $table->enum('status', ['active', 'canceled', 'past_due'])->default('active');
        $table->timestamp('trial_ends_at')->nullable();
        $table->timestamps();
    });
}
```

**File 2: `xxxx_create_entries_table.php`**
```php
public function up()
{
    Schema::create('entries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('workspace_id')->constrained()->onDelete('cascade');
        $table->string('category');      // 'bildung', 'social', 'digital', etc.
        $table->text('text');
        $table->boolean('visible')->default(true);
        $table->boolean('focused')->default(false);
        $table->timestamps();
    });
}
```

**File 3: `xxxx_add_workspace_columns_to_users_table.php`**
```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->foreignId('workspace_id')->nullable()->constrained()->onDelete('cascade');
        $table->enum('role', ['admin', 'moderator'])->default('admin');
    });
}
```

Run migrations:
```bash
php artisan migrate
```

✅ **Checkpoint**: Three new tables in your database? Check with TablePlus or `php artisan tinker` → `DB::table('workspaces')->count()`

#### Step 1.4: Create Models (30 min)

Models = Laravel's way to interact with database tables.

```bash
php artisan make:model Workspace
php artisan make:model Entry
```

**Edit `app/Models/Workspace.php`**:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    protected $fillable = ['name', 'subdomain', 'stripe_customer_id', 'stripe_subscription_id', 'status'];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }
}
```

**Edit `app/Models/Entry.php`**:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = ['workspace_id', 'category', 'text', 'visible', 'focused'];

    protected $casts = [
        'visible' => 'boolean',
        'focused' => 'boolean',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
```

**Edit `app/Models/User.php`** (add to existing file):
```php
// Add to the $fillable array:
protected $fillable = [
    'name',
    'email',
    'password',
    'workspace_id',  // ADD THIS
    'role',          // ADD THIS
];

// Add to the bottom of the class (before the closing }):
public function workspace()
{
    return $this->belongsTo(Workspace::class);
}
```

✅ **Checkpoint**: Files saved without syntax errors?

#### Step 1.5: Import Your Existing Data (1-2 hours)

Create a command to import your current `daten.json`:

```bash
php artisan make:command ImportLegacyData
```

**Edit `app/Console/Commands/ImportLegacyData.php`**:
```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Workspace;
use App\Models\Entry;

class ImportLegacyData extends Command
{
    protected $signature = 'import:legacy {json-file}';
    protected $description = 'Import data from old daten.json file';

    public function handle()
    {
        $file = $this->argument('json-file');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $data = json_decode(file_get_contents($file), true);

        // Create a "Legacy" workspace for old data
        $workspace = Workspace::create([
            'name' => 'Legacy Workshop Data',
            'subdomain' => 'legacy',
            'status' => 'active',
        ]);

        $this->info("Created workspace: {$workspace->name}");

        // Import entries (adjust based on your JSON structure)
        foreach ($data as $entry) {
            Entry::create([
                'workspace_id' => $workspace->id,
                'category' => $entry['category'] ?? 'other',
                'text' => $entry['text'] ?? $entry['content'] ?? '',
                'visible' => $entry['visible'] ?? true,
                'focused' => false,
            ]);
        }

        $this->info("Imported " . count($data) . " entries!");
        return 0;
    }
}
```

**Copy your daten.json** to the project folder, then:
```bash
php artisan import:legacy daten.json
```

✅ **Checkpoint**: Data imported? Check: `php artisan tinker` then `Entry::count()`

#### Step 1.6: Authentication Setup (1 hour)

Laravel has a quick auth system called Breeze:

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run build
php artisan migrate
```

This creates login/register pages automatically!

Test it:
```bash
php artisan serve
```

Visit: http://localhost:8000/register
Create a test account.

✅ **Checkpoint**: Can you register and login?

#### Step 1.7: Build the Three Views (8-12 hours)

This is the meat of the work. We're rebuilding your 3 pages.

**A) Public Entry Form** (`/submit`)

Create controller:
```bash
php artisan make:controller SubmitController
```

**Edit `app/Http/Controllers/SubmitController.php`**:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;

class SubmitController extends Controller
{
    public function show()
    {
        // Get workspace from subdomain (we'll add this logic soon)
        $workspace = request()->get('workspace');
        return view('submit', compact('workspace'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'text' => 'required|string|max:500',
        ]);

        $workspace = request()->get('workspace');

        Entry::create([
            'workspace_id' => $workspace->id,
            'category' => $validated['category'],
            'text' => $validated['text'],
            'visible' => true,
            'focused' => false,
        ]);

        return response()->json(['success' => true]);
    }
}
```

**Create view** `resources/views/submit.blade.php`:
```blade
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eingabe - {{ $workspace->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-bold mb-8 text-center">{{ $workspace->name }}</h1>

        <form id="entry-form" class="bg-gray-800 p-6 rounded-lg shadow-lg">
            @csrf
            <div class="mb-4">
                <label class="block mb-2">Kategorie:</label>
                <select name="category" required class="w-full p-2 bg-gray-700 rounded">
                    <option value="bildung">Bildung</option>
                    <option value="social">Social Media</option>
                    <option value="digital">Digitalisierung</option>
                    <option value="other">Sonstiges</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Ihr Beitrag:</label>
                <textarea name="text" required maxlength="500"
                          class="w-full p-2 bg-gray-700 rounded h-32"></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 p-3 rounded font-bold">
                Absenden
            </button>
        </form>

        <div id="success-message" class="hidden mt-4 p-4 bg-green-600 rounded">
            Vielen Dank für Ihren Beitrag!
        </div>
    </div>

    <script>
        document.getElementById('entry-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);

            const response = await fetch('/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            if (response.ok) {
                e.target.reset();
                document.getElementById('success-message').classList.remove('hidden');
                setTimeout(() => {
                    document.getElementById('success-message').classList.add('hidden');
                }, 3000);
            }
        });
    </script>
</body>
</html>
```

**B) Dashboard View** (`/`)

Create controller:
```bash
php artisan make:controller DashboardController
```

**Edit `app/Http/Controllers/DashboardController.php`**:
```php
<?php

namespace App\Http\Controllers;

use App\Models\Entry;

class DashboardController extends Controller
{
    public function show()
    {
        $workspace = request()->get('workspace');
        $entries = Entry::where('workspace_id', $workspace->id)
            ->where('visible', true)
            ->latest()
            ->get()
            ->groupBy('category');

        return view('dashboard', compact('workspace', 'entries'));
    }

    public function data()
    {
        $workspace = request()->get('workspace');
        $entries = Entry::where('workspace_id', $workspace->id)
            ->where('visible', true)
            ->latest()
            ->get()
            ->groupBy('category');

        return response()->json($entries);
    }
}
```

**Create view** `resources/views/dashboard.blade.php`:
```blade
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $workspace->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8 text-center">{{ $workspace->name }}</h1>

        <div id="entries-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($entries as $category => $categoryEntries)
                <div class="bg-gray-800 p-6 rounded-lg">
                    <h2 class="text-2xl font-bold mb-4 capitalize">{{ $category }}</h2>
                    <div class="space-y-3">
                        @foreach($categoryEntries as $entry)
                            <div class="bg-gray-700 p-4 rounded {{ $entry->focused ? 'ring-4 ring-yellow-400' : '' }}">
                                {{ $entry->text }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        // Auto-refresh every 5 seconds (simple polling, not WebSockets!)
        setInterval(async () => {
            const response = await fetch('/data');
            const data = await response.json();
            // Update UI with new data (you can improve this)
            location.reload(); // Simple but works for MVP!
        }, 5000);
    </script>
</body>
</html>
```

**C) Admin Panel** (`/admin`)

Create controller:
```bash
php artisan make:controller AdminController
```

**Edit `app/Http/Controllers/AdminController.php`**:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $workspace = auth()->user()->workspace;
        $entries = Entry::where('workspace_id', $workspace->id)
            ->latest()
            ->get();

        return view('admin', compact('workspace', 'entries'));
    }

    public function toggleVisible(Entry $entry)
    {
        $entry->update(['visible' => !$entry->visible]);
        return response()->json(['success' => true]);
    }

    public function toggleFocus(Entry $entry)
    {
        // Remove focus from all other entries first
        Entry::where('workspace_id', $entry->workspace_id)->update(['focused' => false]);
        $entry->update(['focused' => !$entry->focused]);
        return response()->json(['success' => true]);
    }

    public function destroy(Entry $entry)
    {
        $entry->delete();
        return response()->json(['success' => true]);
    }
}
```

**Create view** `resources/views/admin.blade.php`:
```blade
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - {{ $workspace->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Admin Panel - {{ $workspace->name }}</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="bg-red-600 px-4 py-2 rounded">Logout</button>
            </form>
        </div>

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="p-4 text-left">Text</th>
                        <th class="p-4 text-left">Kategorie</th>
                        <th class="p-4 text-center">Sichtbar</th>
                        <th class="p-4 text-center">Focus</th>
                        <th class="p-4 text-center">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                        <tr class="border-t border-gray-700">
                            <td class="p-4">{{ $entry->text }}</td>
                            <td class="p-4">{{ $entry->category }}</td>
                            <td class="p-4 text-center">
                                <button onclick="toggleVisible({{ $entry->id }})"
                                        class="px-3 py-1 rounded {{ $entry->visible ? 'bg-green-600' : 'bg-gray-600' }}">
                                    {{ $entry->visible ? 'Ja' : 'Nein' }}
                                </button>
                            </td>
                            <td class="p-4 text-center">
                                <button onclick="toggleFocus({{ $entry->id }})"
                                        class="px-3 py-1 rounded {{ $entry->focused ? 'bg-yellow-600' : 'bg-gray-600' }}">
                                    {{ $entry->focused ? '★' : '☆' }}
                                </button>
                            </td>
                            <td class="p-4 text-center">
                                <button onclick="deleteEntry({{ $entry->id }})" class="bg-red-600 px-3 py-1 rounded">
                                    Löschen
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        async function toggleVisible(id) {
            await fetch(`/admin/entries/${id}/visible`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }});
            location.reload();
        }

        async function toggleFocus(id) {
            await fetch(`/admin/entries/${id}/focus`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }});
            location.reload();
        }

        async function deleteEntry(id) {
            if (confirm('Wirklich löschen?')) {
                await fetch(`/admin/entries/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }});
                location.reload();
            }
        }
    </script>
</body>
</html>
```

#### Step 1.8: Simple Multi-Workspace Routing (2 hours)

We need to detect which workspace from the subdomain.

**Create middleware**:
```bash
php artisan make:middleware IdentifyWorkspace
```

**Edit `app/Http/Middleware/IdentifyWorkspace.php`**:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Workspace;

class IdentifyWorkspace
{
    public function handle($request, Closure $next)
    {
        // Get subdomain from request
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];

        // Find workspace by subdomain
        $workspace = Workspace::where('subdomain', $subdomain)->first();

        if (!$workspace) {
            abort(404, 'Workspace not found');
        }

        // Make workspace available throughout the request
        $request->merge(['workspace' => $workspace]);
        $request->attributes->set('workspace', $workspace);

        return $next($request);
    }
}
```

**Register middleware** in `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(\App\Http\Middleware\IdentifyWorkspace::class);
})
```

#### Step 1.9: Setup Routes (30 min)

**Edit `routes/web.php`**:
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubmitController;
use App\Http\Controllers\AdminController;

// Public routes
Route::get('/', [DashboardController::class, 'show'])->name('dashboard');
Route::get('/data', [DashboardController::class, 'data'])->name('dashboard.data');
Route::get('/submit', [SubmitController::class, 'show'])->name('submit');
Route::post('/submit', [SubmitController::class, 'store']);

// Admin routes (require login)
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::post('/entries/{entry}/visible', [AdminController::class, 'toggleVisible']);
    Route::post('/entries/{entry}/focus', [AdminController::class, 'toggleFocus']);
    Route::delete('/entries/{entry}', [AdminController::class, 'destroy']);
});

require __DIR__.'/auth.php';
```

#### Step 1.10: Local Testing with Subdomains (1 hour)

To test subdomains locally, edit your hosts file:

**Mac/Linux**: `/etc/hosts`
**Windows**: `C:\Windows\System32\drivers\etc\hosts`

Add these lines:
```
127.0.0.1  test.situationroom.local
127.0.0.1  demo.situationroom.local
```

Create a test workspace:
```bash
php artisan tinker
```

In tinker:
```php
$workspace = \App\Models\Workspace::create([
    'name' => 'Test Workshop',
    'subdomain' => 'test',
    'status' => 'active'
]);

$user = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@test.com',
    'password' => bcrypt('password'),
    'workspace_id' => $workspace->id,
    'role' => 'admin'
]);
```

Now start server and test:
```bash
php artisan serve --host=situationroom.local --port=8000
```

Visit:
- http://test.situationroom.local:8000 (dashboard)
- http://test.situationroom.local:8000/submit (entry form)
- http://test.situationroom.local:8000/admin (admin panel - login with admin@test.com / password)

✅ **CHECKPOINT**: All 3 pages work with different subdomains?

---

### WEEK 3: Stripe Payment Integration

**⏱ Time needed: 10-15 hours**
**Goal**: Customers can sign up and pay before getting access

#### Step 3.1: Install Laravel Cashier (30 min)

```bash
composer require laravel/cashier
php artisan vendor:publish --tag="cashier-migrations"
php artisan migrate
```

Add Stripe keys to `.env`:
```
STRIPE_KEY=pk_test_xxx  # Get from Stripe Dashboard
STRIPE_SECRET=sk_test_xxx
```

**Update Workspace model** (`app/Models/Workspace.php`):
```php
use Laravel\Cashier\Billable;

class Workspace extends Model
{
    use Billable;  // Add this trait

    // ... rest of your code
}
```

#### Step 3.2: Create Pricing in Stripe (1 hour)

Go to Stripe Dashboard → Products:

1. Create product: "Situation Room Workspace"
2. Add price: €49.00 EUR / month
3. Copy the Price ID (starts with `price_xxx`)

Add to `.env`:
```
STRIPE_PRICE_ID=price_xxx  # Your price ID from Stripe
```

#### Step 3.3: Build Sign-Up Flow (4-6 hours)

Create controller:
```bash
php artisan make:controller SignupController
```

**Edit `app/Http/Controllers/SignupController.php`**:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workspace;
use App\Models\User;
use Illuminate\Support\Str;

class SignupController extends Controller
{
    public function show()
    {
        return view('signup');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'subdomain' => 'required|string|alpha_dash|unique:workspaces,subdomain',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Store in session for after payment
        session([
            'signup_data' => $validated
        ]);

        // Create Stripe checkout session
        return redirect()->route('signup.checkout');
    }

    public function checkout()
    {
        $data = session('signup_data');

        if (!$data) {
            return redirect()->route('signup');
        }

        // Create temporary workspace for Stripe checkout
        $workspace = Workspace::create([
            'name' => $data['name'],
            'subdomain' => $data['subdomain'],
            'status' => 'inactive',
        ]);

        return $workspace->newSubscription('default', config('services.stripe.price_id'))
            ->checkout([
                'success_url' => route('signup.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('signup'),
            ]);
    }

    public function success(Request $request)
    {
        $data = session('signup_data');
        $workspace = Workspace::where('subdomain', $data['subdomain'])->first();

        // Activate workspace
        $workspace->update(['status' => 'active']);

        // Create admin user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'workspace_id' => $workspace->id,
            'role' => 'admin',
        ]);

        // Login user
        auth()->login($user);

        // Clear session
        session()->forget('signup_data');

        return redirect()->to("http://{$workspace->subdomain}." . config('app.domain') . "/admin");
    }
}
```

**Create signup view** `resources/views/signup.blade.php`:
```blade
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Situation Room</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto px-4 py-16 max-w-md">
        <h1 class="text-4xl font-bold mb-8 text-center">Start Your Workspace</h1>

        <div class="bg-gray-800 p-8 rounded-lg">
            <div class="bg-blue-900 p-4 rounded mb-6">
                <p class="text-xl font-bold">€49 / Monat</p>
                <p class="text-sm">Jederzeit kündbar</p>
            </div>

            <form method="POST" action="{{ route('signup.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block mb-2">Organisation / Name:</label>
                    <input type="text" name="name" required class="w-full p-2 bg-gray-700 rounded">
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Ihre Subdomain:</label>
                    <div class="flex items-center">
                        <input type="text" name="subdomain" required
                               class="flex-1 p-2 bg-gray-700 rounded-l"
                               pattern="[a-z0-9-]+"
                               placeholder="meinefirma">
                        <span class="bg-gray-600 p-2 rounded-r">.situationroom.eu</span>
                    </div>
                    <p class="text-sm text-gray-400 mt-1">Nur Kleinbuchstaben, Zahlen und Bindestriche</p>
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Email:</label>
                    <input type="email" name="email" required class="w-full p-2 bg-gray-700 rounded">
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Passwort:</label>
                    <input type="password" name="password" required minlength="8" class="w-full p-2 bg-gray-700 rounded">
                </div>

                <div class="mb-6">
                    <label class="block mb-2">Passwort bestätigen:</label>
                    <input type="password" name="password_confirmation" required class="w-full p-2 bg-gray-700 rounded">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 p-3 rounded font-bold text-lg">
                    Weiter zur Zahlung →
                </button>
            </form>
        </div>
    </div>
</body>
</html>
```

**Add routes** to `routes/web.php`:
```php
Route::get('/signup', [SignupController::class, 'show'])->name('signup');
Route::post('/signup', [SignupController::class, 'store'])->name('signup.store');
Route::get('/signup/checkout', [SignupController::class, 'checkout'])->name('signup.checkout');
Route::get('/signup/success', [SignupController::class, 'success'])->name('signup.success');
```

**Add to `config/services.php`**:
```php
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'price_id' => env('STRIPE_PRICE_ID'),
],
```

✅ **CHECKPOINT**: Can you sign up, pay (test mode), and get redirected to admin panel?

---

### WEEK 4: Production Deployment

**⏱ Time needed: 8-12 hours**
**Goal**: Live on the internet, ready for first customer

#### Step 4.1: Buy Server & Configure (2-3 hours)

**Buy Hetzner Server:**
1. Go to https://console.hetzner.cloud
2. Create new project: "Situation Room"
3. Add server: CX21 (€5.83/month)
4. Location: Nürnberg (closest to Austria)
5. Image: Ubuntu 22.04
6. SSH key: Create on your computer first

**Create SSH key (on your computer)**:
```bash
ssh-keygen -t ed25519 -C "your_email@example.com"
# Press enter 3 times (default location, no passphrase for now)
cat ~/.ssh/id_ed25519.pub  # Copy this output
```

Paste the public key into Hetzner when creating server.

**Connect to server**:
```bash
ssh root@YOUR_SERVER_IP
```

#### Step 4.2: Server Setup Script (1 hour)

**On the server**, run this script (copy-paste all at once):

```bash
# Update system
apt update && apt upgrade -y

# Install required packages
apt install -y php8.3 php8.3-fpm php8.3-cli php8.3-common php8.3-mysql \
  php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-gd \
  php8.3-sqlite3 php8.3-pgsql nginx postgresql postgresql-contrib \
  git curl unzip

# Install Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Install Node.js (for npm)
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Setup PostgreSQL
sudo -u postgres psql -c "CREATE USER situationroom WITH PASSWORD 'CHANGE_THIS_PASSWORD';"
sudo -u postgres psql -c "CREATE DATABASE situationroom OWNER situationroom;"

# Setup firewall
ufw allow 22
ufw allow 80
ufw allow 443
ufw --force enable

echo "✅ Server setup complete!"
```

#### Step 4.3: Install Caddy for SSL (30 min)

Caddy automatically handles SSL certificates for all subdomains:

```bash
apt install -y debian-keyring debian-archive-keyring apt-transport-https
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' | gpg --dearmor -o /usr/share/keyrings/caddy-stable-archive-keyring.gpg
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' | tee /etc/apt/sources.list.d/caddy-stable.list
apt update
apt install caddy
```

**Create Caddyfile** (`/etc/caddy/Caddyfile`):
```
*.situationroom.eu, situationroom.eu {
    root * /var/www/situation-room/public
    php_fastcgi unix//run/php/php8.3-fpm.sock
    encode gzip
    file_server

    @notStatic {
        not path *.css *.js *.png *.jpg *.jpeg *.gif *.ico *.svg *.woff *.woff2
    }

    try_files {path} /index.php?{query}
}
```

Restart Caddy:
```bash
systemctl restart caddy
```

#### Step 4.4: Deploy Application (2-3 hours)

**On server:**

```bash
# Create web directory
mkdir -p /var/www
cd /var/www

# Clone your repository
git clone https://github.com/YOUR-USERNAME/situation-room.git
cd situation-room

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Setup environment
cp .env.example .env
php artisan key:generate
```

**Edit `.env` on server**:
```bash
nano .env
```

Change these values:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://situationroom.eu

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=situationroom
DB_USERNAME=situationroom
DB_PASSWORD=CHANGE_THIS_PASSWORD  # Use what you set earlier

STRIPE_KEY=pk_live_xxx  # Your LIVE Stripe keys now!
STRIPE_SECRET=sk_live_xxx
STRIPE_PRICE_ID=price_xxx  # Create production price in Stripe
```

**Run migrations**:
```bash
php artisan migrate --force
```

**Set permissions**:
```bash
chown -R www-data:www-data /var/www/situation-room
chmod -R 755 /var/www/situation-room/storage
chmod -R 755 /var/www/situation-room/bootstrap/cache
```

#### Step 4.5: Configure Domain (1 hour)

**In Cloudflare (or your DNS provider):**

Add these DNS records:
```
Type: A     Name: @     Content: YOUR_SERVER_IP
Type: A     Name: *     Content: YOUR_SERVER_IP
```

Wait 5-10 minutes for DNS to propagate.

Test: Visit https://situationroom.eu (should show Laravel)

✅ **CHECKPOINT**: Can you visit https://test.situationroom.eu and see your site?

#### Step 4.6: Setup Deployment Script (1 hour)

**On server**, create `/var/www/deploy.sh`:
```bash
#!/bin/bash

cd /var/www/situation-room

git pull origin main

composer install --no-dev --optimize-autoloader
npm install
npm run build

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R www-data:www-data /var/www/situation-room

echo "✅ Deployment complete!"
```

Make it executable:
```bash
chmod +x /var/www/deploy.sh
```

Now you can deploy with: `./var/www/deploy.sh`

#### Step 4.7: Setup Automatic Backups (1 hour)

**Create backup script** `/root/backup.sh`:
```bash
#!/bin/bash

DATE=$(date +%Y-%m-%d-%H%M)
BACKUP_DIR="/root/backups"

mkdir -p $BACKUP_DIR

# Backup database
sudo -u postgres pg_dump situationroom > $BACKUP_DIR/db-$DATE.sql

# Backup uploaded files (if any)
tar -czf $BACKUP_DIR/files-$DATE.tar.gz /var/www/situation-room/storage

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "✅ Backup complete: $DATE"
```

Make executable and add to cron:
```bash
chmod +x /root/backup.sh
crontab -e
```

Add this line (runs daily at 2am):
```
0 2 * * * /root/backup.sh
```

✅ **CHECKPOINT**: System is live, SSL works, backups configured?

---

### WEEK 5-6: Landing Page & First Customers

**⏱ Time needed: 15-20 hours**
**Goal**: Get 3-5 paying customers

#### Step 5.1: Build Simple Landing Page (4-6 hours)

**Create `resources/views/welcome.blade.php`** (the homepage):

```blade
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
            <h1 class="text-5xl font-bold mb-6">
                Live Feedback für Ihre Workshops
            </h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Sammeln Sie Ideen, Fragen und Feedback von Ihren Teilnehmern in Echtzeit.
                Perfekt für Konferenzen, Schulungen und Team-Events.
            </p>
            <a href="/signup" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-bold hover:bg-gray-100">
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
                <div class="text-5xl font-bold mb-6">
                    €49<span class="text-2xl text-gray-600">/Monat</span>
                </div>

                <ul class="text-left mb-8 space-y-2">
                    <li>✅ Eigene Subdomain</li>
                    <li>✅ Unbegrenzte Workshops</li>
                    <li>✅ Bis zu 200 Teilnehmer gleichzeitig</li>
                    <li>✅ Admin-Panel mit Moderation</li>
                    <li>✅ PDF Export</li>
                    <li>✅ Email Support</li>
                    <li>✅ Jederzeit kündbar</li>
                </ul>

                <a href="/signup" class="block bg-blue-600 text-white px-8 py-4 rounded-lg font-bold hover:bg-blue-700">
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
            <a href="/signup" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-bold hover:bg-gray-100">
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
```

**Update route** in `routes/web.php`:
```php
// Add this at the top, before other routes
Route::domain(config('app.main_domain'))->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/signup', [SignupController::class, 'show'])->name('signup');
    Route::post('/signup', [SignupController::class, 'store'])->name('signup.store');
    // ... other main domain routes
});

// All workspace routes (with IdentifyWorkspace middleware)
Route::middleware('workspace')->group(function () {
    // Your dashboard, submit, admin routes here
});
```

#### Step 5.2: Create Marketing Materials (2-3 hours)

**A) One-Page PDF Flyer**
- Use Canva (free): https://canva.com
- Template: "Business Flyer"
- Content:
  - Headline: "Live Workshop Feedback - Einfach & DSGVO-konform"
  - 3 key benefits
  - QR code to landing page
  - Price: €49/month

**B) Email Template for Outreach**

```
Betreff: Live-Feedback Tool für [their company/event]

Hallo [Name],

ich habe gesehen, dass Sie [Workshops/Trainings/Events] durchführen.

Ich habe ein Tool entwickelt, mit dem Teilnehmer live während der Veranstaltung
Feedback, Fragen und Ideen einreichen können - ohne App-Download.

Beispiel: [Screenshot your demo workspace]

Die Teilnehmer scannen einen QR-Code, geben Input ab, und Sie sehen alles
in Echtzeit auf Ihrer Moderations-Ansicht.

Das Tool ist:
✅ DSGVO-konform (Server in Deutschland)
✅ Sofort einsatzbereit (Setup: 5 Minuten)
✅ Fair bepreist (€49/Monat, jederzeit kündbar)

Würde mich freuen, wenn Sie sich das mal anschauen:
https://situationroom.eu

Gerne zeige ich es Ihnen auch kurz in einem 15-min Call.

Beste Grüße,
[Your Name]
```

#### Step 5.3: Customer Outreach (8-10 hours over 2 weeks)

**Week 5-6 Daily Routine:**

**Day 1-3**: Contact past clients
- Email everyone you did workshops for
- Offer first 3 customers: 50% off first 3 months (€24.50)
- Goal: 1-2 sign-ups

**Day 4-7**: Cold outreach (10 emails/day)
- Target: Event agencies, corporate training departments
- Use email template above
- Goal: 5 demo calls scheduled

**Day 8-10**: Demo calls
- Show live demo (use your test workspace)
- Walk through: QR code → participants submit → admin moderates
- Close: "You can start using it today, takes 5 minutes"
- Goal: 2-3 sign-ups

**Day 11-14**: LinkedIn/Social
- Post about launch: "I built a tool for workshop facilitators..."
- Share in relevant groups (Event Management, Training & Development)
- Goal: 1-2 organic sign-ups

**Where to find prospects:**
- LinkedIn: Search "workshop facilitator", "training manager", "event planner"
- Xing (in DACH region)
- Facebook groups: "Event Professionals Austria", "Corporate Training DACH"
- Directly contact: Universities, corporate academies, event agencies

**Success Metric**: 5 paying customers = €245/month = Infrastructure covered + validation ✅

---

### WEEK 7-8: Polish & Iterate

**⏱ Time needed: 10-15 hours**
**Goal**: Fix issues based on customer feedback

#### Step 7.1: Add Basic Analytics (2 hours)

Track important metrics without violating GDPR:

**Install Plausible (self-hosted, free)**:
```bash
# On your server
docker run -d --name plausible \
  -p 8000:8000 \
  plausible/analytics:latest
```

Or use Plausible Cloud (€9/month, easier): https://plausible.io

Add to your views:
```html
<script defer data-domain="situationroom.eu" src="https://plausible.io/js/script.js"></script>
```

Track: Sign-ups, active workspaces, entries submitted.

#### Step 7.2: Customer Feedback Loop (2-3 hours)

**Add feedback widget to admin panel**:

In `resources/views/admin.blade.php`, add:
```html
<div class="fixed bottom-4 right-4">
    <button onclick="openFeedback()" class="bg-blue-600 px-4 py-2 rounded-lg shadow-lg">
        💬 Feedback
    </button>
</div>

<script>
function openFeedback() {
    window.open('mailto:hi@situationroom.eu?subject=Feedback%20zu%20Situation%20Room', '_blank');
}
</script>
```

**Setup Typeform or Google Form** for structured feedback:
- What features are you missing?
- What's confusing?
- Would you recommend us? (NPS score)

Send to all customers after their first workshop.

#### Step 7.3: Common Improvements Based on Feedback (6-10 hours)

**These are TYPICAL requests you'll get - build them based on demand:**

**A) QR Code Generator** (1 hour)
```bash
composer require simplesoftwareio/simple-qrcode
```

Add to admin panel - auto-generate QR for `workspace.situationroom.eu/submit`

**B) Custom Categories** (2 hours)
Let workspaces define their own categories instead of hardcoded ones.

**C) Export to CSV** (1 hour)
In addition to PDF, add CSV export for Excel.

**D) Dark/Light Mode Toggle** (2 hours)
Some customers will want light theme for projection.

**E) Better Mobile Layout** (2 hours)
Test on phones, improve touch targets.

**DON'T BUILD**: Anything that only 1 customer asks for. Wait for 3+ requests.

---

## 📊 SUCCESS METRICS (Track Weekly)

**Week 4**:
- [ ] System live and stable
- [ ] 0 customers, €0 MRR

**Week 6**:
- [ ] 3-5 customers
- [ ] €150-250 MRR
- [ ] <2 support hours/week

**Week 8**:
- [ ] 5-10 customers
- [ ] €250-500 MRR
- [ ] 1-2 feature requests implemented
- [ ] Decision point: Continue or pivot?

**Week 12** (If continuing):
- [ ] 10-15 customers
- [ ] €500-750 MRR
- [ ] Automated onboarding (no manual setup)
- [ ] <3 hours maintenance/week

---

## 💰 REAL COST BREAKDOWN

### Time Investment:
- Week 1-2: 30 hours (coding)
- Week 3: 15 hours (Stripe integration)
- Week 4: 12 hours (deployment)
- Week 5-6: 20 hours (sales/marketing)
- Week 7-8: 15 hours (polish)
- **Total: ~90 hours over 8 weeks**

### Money Investment:
- Domain: €10/year = €1/month
- Hetzner server: €5.83/month
- Stripe fees: 1.5% + €0.25 per transaction
- (Optional) Plausible: €9/month
- **Total: €7-17/month**

### Break-Even:
- **2 customers** = €98/month = Covers costs + €80/month profit
- **5 customers** = €245/month = €230/month profit
- **10 customers** = €490/month = €475/month profit

At 10 customers with 90 hours invested:
→ €475/month ongoing
→ €5,700/year
→ Hourly rate: €63/hour (if you count first 90 hours)
→ After Year 1: All profit (minimal maintenance)

---

## 🚨 COMMON MISTAKES TO AVOID

### ❌ DON'T:
1. **Add features before customers ask** - Every feature adds maintenance burden
2. **Offer free trials** - For MVP, paid-only validates willingness to pay
3. **Build custom features for single customers** - Wait for patterns
4. **Optimize prematurely** - Your server can handle 50+ workspaces easily
5. **Spend time on branding** - A working product > pretty logo
6. **Automate too early** - Manual onboarding helps you learn customer needs
7. **Add team features** - Single admin per workspace is fine for MVP
8. **Build mobile apps** - Responsive web is enough
9. **Worry about scale** - 100 concurrent users? Your stack handles it fine
10. **Give discounts without reason** - €49 is already cheap for business tool

### ✅ DO:
1. **Talk to customers weekly** - They'll tell you what to build
2. **Ship broken features** - Fix based on complaints, not imagination
3. **Charge from day 1** - Free users aren't customers
4. **Manual onboarding first** - Automate when it takes >10 hours/month
5. **Say no to custom requests** - Unless 3+ customers ask
6. **Keep it simple** - Every line of code is future maintenance
7. **Track churn** - Why do customers cancel? Fix that first
8. **Respond fast** - Reply to support emails within 4 hours
9. **Use customer words** - Copy their language for marketing
10. **Set boundaries** - No 24/7 support for €49/month product

---

## 🆘 WHEN YOU GET STUCK

### Technical Issues:

**Laravel Errors:**
1. Google the exact error message
2. Check Laravel docs: https://laravel.com/docs
3. Ask in Laravel Discord: https://discord.gg/laravel
4. StackOverflow with `[laravel]` tag

**Deployment Issues:**
1. Check server logs: `tail -f /var/log/nginx/error.log`
2. Laravel logs: `tail -f /var/www/situation-room/storage/logs/laravel.log`
3. Restart services: `systemctl restart php8.3-fpm nginx caddy`

**Stripe Issues:**
1. Check Stripe Dashboard → Logs
2. Use test mode first (keys start with `pk_test_` and `sk_test_`)
3. Stripe docs are excellent: https://stripe.com/docs

### Business Issues:

**No one is signing up:**
- Are you talking to 10 people/day?
- Are you showing a live demo?
- Is pricing clear on landing page?
- Try: "First month €25" offer

**Customers cancel after first month:**
- WHY? Ask them directly (email or call)
- Common reason: "Only needed it once" → Add annual plan (€399/year = 2 months free)
- Common reason: "Missing feature X" → Build it if 3+ ask

**Too much support work:**
- Write FAQs based on common questions
- Add onboarding video (5-min Loom recording)
- Create templates for common responses

---

## 🎯 DECISION POINT (End of Week 8)

### ✅ Continue if:
- You have 5+ paying customers
- Customers are actually using it (not just trialing)
- Support takes <5 hours/week
- You're excited about building more

**Next steps:**
- Hire VA for customer support (€10/hour, 5 hours/week)
- Build most-requested features
- Expand marketing (paid ads, partnerships)
- Goal: 50 customers by Month 6

### ⚠️ Pivot if:
- <3 customers after 8 weeks of outreach
- Customers sign up but don't use it
- Constant feature requests for different use cases
- You're dreading working on it

**Options:**
- Niche down (e.g., only for universities)
- Change target market (e.g., only event agencies)
- Shut down, keep learnings, try different product

### ❌ Shut down if:
- 0-1 customers after 8 weeks
- No one responds to outreach
- Tech stack is too complex for you
- You found better opportunity

**Shut down checklist:**
- Export all customer data
- Cancel their subscriptions in Stripe (prorated refunds)
- Send "We're shutting down" email 30 days in advance
- Offer alternatives (Slido, Mentimeter)
- Total loss: ~€50 + 90 hours = cheap lesson!

---

## 📚 LEARNING RESOURCES

### Laravel (Critical):
- **Laracasts**: https://laracasts.com (~€15/month)
  - Watch: "Laravel From Scratch" series
  - You need ~20 hours of videos for Foundation
- **Laravel Bootcamp**: https://bootcamp.laravel.com (free)
  - Official tutorial, very good

### Stripe:
- Stripe Docs: https://stripe.com/docs/billing/subscriptions/overview
- Laravel Cashier Docs: https://laravel.com/docs/billing

### General SaaS:
- **"The Mom Test"** (book) - How to talk to customers
- **"Obviously Awesome"** (book) - Positioning
- **IndieHackers**: https://indiehackers.com - Stories from founders
- **MicroConf** (YouTube) - Talks about bootstrapping SaaS

---

## ✅ YOUR ACTION PLAN (Start TODAY)

### This Week (Week 0):
- [ ] Talk to 5 potential customers (validate €49/month price)
- [ ] Create GitHub account
- [ ] Create Stripe account (test mode)
- [ ] Check domain availability / buy if needed
- [ ] Install Laravel Herd
- [ ] Create test Laravel project and see welcome screen

### Next Week (Week 1):
- [ ] Real Laravel project setup
- [ ] Database design & migrations
- [ ] Basic authentication
- [ ] Import your existing data

**After each checkpoint, message me (or GitHub issue):**
- "✅ Checkpoint X passed!"
- "❌ Stuck at step Y, error: [paste error]"
- "? Question about Z"

---

## 🎉 FINAL WORD

You're not building the next Salesforce.
You're building a €500/month side business that pays for itself.

**8 weeks. 10 customers. €500/month.**

That's the goal. Everything else is distraction.

Simple beats complex.
Done beats perfect.
Paid beats free.

You're not alone - I'm here for every step.

**Ready? Start with Week 0, Step 0.1. Talk to 5 people. Go!** 🚀

---

**Last updated**: 2025-12-11
**Version**: 2.0 (MVP-First)
**Next update**: After first paying customer
