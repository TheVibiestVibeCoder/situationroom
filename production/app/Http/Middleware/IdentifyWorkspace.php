<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyWorkspace
{
    /**
     * Handle an incoming request to identify the workspace from subdomain.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get subdomain from request
        $host = $request->getHost();
        $parts = explode('.', $host);

        // If we're in local development (no subdomain)
        if (count($parts) < 3 && !str_contains($host, '.local')) {
            // This is the main domain - skip workspace identification
            return $next($request);
        }

        $subdomain = $parts[0];

        // Skip main domain and common subdomains
        if (in_array($subdomain, ['www', ''])) {
            return $next($request);
        }

        // Find workspace by subdomain
        $workspace = Workspace::where('subdomain', $subdomain)->first();

        if (!$workspace) {
            abort(404, 'Workspace not found');
        }

        // Check if workspace is active
        if (!$workspace->isActive()) {
            abort(403, 'This workspace is not active. Please contact support.');
        }

        // Make workspace available throughout the request
        $request->merge(['workspace' => $workspace]);
        $request->attributes->set('workspace', $workspace);

        // Share with views
        view()->share('workspace', $workspace);

        return $next($request);
    }
}
