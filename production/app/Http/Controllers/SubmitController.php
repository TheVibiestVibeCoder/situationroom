<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;

class SubmitController extends Controller
{
    /**
     * Show the submission form.
     */
    public function show(Request $request)
    {
        $workspace = $request->get('workspace');

        return view('submit', compact('workspace'));
    }

    /**
     * Store a new entry.
     */
    public function store(Request $request)
    {
        $workspace = $request->get('workspace');

        $validated = $request->validate([
            'category' => 'required|string|in:bildung,social,individuell,politik,kreativ',
            'text' => 'required|string|max:500',
        ]);

        Entry::create([
            'workspace_id' => $workspace->id,
            'category' => $validated['category'],
            'text' => strip_tags($validated['text']),
            'visible' => false, // Starts hidden, admin must approve
            'focused' => false,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', '✅ ANTWORT ERFOLGREICH ÜBERMITTELT. (KEEP GOING!)');
    }
}
