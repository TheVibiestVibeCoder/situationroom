<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard view.
     */
    public function show(Request $request)
    {
        $workspace = $request->get('workspace');

        $entries = Entry::where('workspace_id', $workspace->id)
            ->where('visible', true)
            ->latest()
            ->get()
            ->groupBy('category');

        return view('dashboard', compact('workspace', 'entries'));
    }

    /**
     * Get entries data as JSON (for AJAX polling).
     */
    public function data(Request $request)
    {
        $workspace = $request->get('workspace');

        $entries = Entry::where('workspace_id', $workspace->id)
            ->where('visible', true)
            ->latest()
            ->get()
            ->groupBy('category');

        return response()->json($entries);
    }

    /**
     * Get focused entry (for focus mode).
     */
    public function focusedEntry(Request $request)
    {
        $workspace = $request->get('workspace');

        $focused = Entry::where('workspace_id', $workspace->id)
            ->where('focused', true)
            ->first();

        return response()->json($focused);
    }
}
