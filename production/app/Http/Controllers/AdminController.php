<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show admin panel.
     */
    public function index()
    {
        $workspace = Auth::user()->workspace;

        if (!$workspace) {
            abort(403, 'No workspace assigned');
        }

        $entries = Entry::where('workspace_id', $workspace->id)
            ->latest()
            ->get();

        return view('admin', compact('workspace', 'entries'));
    }

    /**
     * Toggle visibility of an entry.
     */
    public function toggleVisible(Entry $entry)
    {
        $this->authorize('update', $entry);

        $entry->update(['visible' => !$entry->visible]);

        return response()->json(['success' => true]);
    }

    /**
     * Toggle focus on an entry (only one can be focused at a time).
     */
    public function toggleFocus(Entry $entry)
    {
        $this->authorize('update', $entry);

        // Remove focus from all other entries in this workspace
        Entry::where('workspace_id', $entry->workspace_id)
            ->where('id', '!=', $entry->id)
            ->update(['focused' => false]);

        // Toggle focus on this entry
        $entry->update(['focused' => !$entry->focused]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete an entry.
     */
    public function destroy(Entry $entry)
    {
        $this->authorize('delete', $entry);

        $entry->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Move entry to different category.
     */
    public function move(Request $request, Entry $entry)
    {
        $this->authorize('update', $entry);

        $validated = $request->validate([
            'category' => 'required|string|in:bildung,social,individuell,politik,kreativ',
        ]);

        $entry->update(['category' => $validated['category']]);

        return response()->json(['success' => true]);
    }

    /**
     * Bulk action: Show all in category.
     */
    public function showAllInCategory(Request $request)
    {
        $workspace = Auth::user()->workspace;

        $validated = $request->validate([
            'category' => 'required|string|in:bildung,social,individuell,politik,kreativ',
        ]);

        Entry::where('workspace_id', $workspace->id)
            ->where('category', $validated['category'])
            ->update(['visible' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Bulk action: Hide all in category.
     */
    public function hideAllInCategory(Request $request)
    {
        $workspace = Auth::user()->workspace;

        $validated = $request->validate([
            'category' => 'required|string|in:bildung,social,individuell,politik,kreativ',
        ]);

        Entry::where('workspace_id', $workspace->id)
            ->where('category', $validated['category'])
            ->update(['visible' => false]);

        return response()->json(['success' => true]);
    }

    /**
     * Bulk action: Show all entries.
     */
    public function showAll()
    {
        $workspace = Auth::user()->workspace;

        Entry::where('workspace_id', $workspace->id)
            ->update(['visible' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Bulk action: Hide all entries.
     */
    public function hideAll()
    {
        $workspace = Auth::user()->workspace;

        Entry::where('workspace_id', $workspace->id)
            ->update(['visible' => false]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete all entries (purge).
     */
    public function purgeAll()
    {
        $workspace = Auth::user()->workspace;

        Entry::where('workspace_id', $workspace->id)->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Export entries as PDF (simple view for printing).
     */
    public function exportPdf()
    {
        $workspace = Auth::user()->workspace;

        $categories = [
            'bildung' => 'Bildung & Forschung',
            'social' => 'Soziale Medien',
            'individuell' => 'Individuelle Verantwortung',
            'politik' => 'Politik & Recht',
            'kreativ' => 'Innovative Ansätze',
        ];

        $entries = Entry::where('workspace_id', $workspace->id)
            ->orderBy('category')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('category');

        return view('pdf-export', compact('workspace', 'entries', 'categories'));
    }
}
