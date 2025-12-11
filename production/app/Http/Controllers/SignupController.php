<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SignupController extends Controller
{
    /**
     * Show the signup form.
     */
    public function show()
    {
        return view('signup');
    }

    /**
     * Store signup data in session and redirect to payment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => [
                'required',
                'string',
                'alpha_dash',
                'max:63',
                'unique:workspaces,subdomain',
                'not_in:www,admin,api,app,mail,ftp,localhost',
            ],
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Store in session for after payment
        session(['signup_data' => $validated]);

        return redirect()->route('signup.checkout');
    }

    /**
     * Create Stripe checkout session.
     */
    public function checkout(Request $request)
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

        try {
            return $workspace->newSubscription('default', config('services.stripe.price_id'))
                ->checkout([
                    'success_url' => route('signup.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('signup'),
                ]);
        } catch (\Exception $e) {
            // If checkout fails, delete the workspace
            $workspace->delete();
            return redirect()->route('signup')->withErrors(['error' => 'Payment setup failed. Please try again.']);
        }
    }

    /**
     * Handle successful payment and complete signup.
     */
    public function success(Request $request)
    {
        $data = session('signup_data');

        if (!$data) {
            return redirect()->route('signup');
        }

        $workspace = Workspace::where('subdomain', $data['subdomain'])->first();

        if (!$workspace) {
            return redirect()->route('signup')->withErrors(['error' => 'Workspace not found']);
        }

        // Activate workspace
        $workspace->update(['status' => 'active']);

        // Create admin user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'workspace_id' => $workspace->id,
            'role' => 'admin',
        ]);

        // Login user
        Auth::login($user);

        // Clear session
        session()->forget('signup_data');

        // Redirect to their workspace admin panel
        $domain = config('app.domain', 'situationroom.eu');
        $url = "https://{$workspace->subdomain}.{$domain}/admin";

        return redirect()->away($url);
    }

    /**
     * Handle cancelled payment.
     */
    public function cancel()
    {
        $data = session('signup_data');

        if ($data) {
            // Delete the temporary workspace
            Workspace::where('subdomain', $data['subdomain'])->delete();
            session()->forget('signup_data');
        }

        return redirect()->route('signup')->with('info', 'Payment cancelled. Your data has not been saved.');
    }
}
