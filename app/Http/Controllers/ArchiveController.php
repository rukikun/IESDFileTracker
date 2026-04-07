<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArchiveController extends Controller
{
    /**
     * Display a listing of all archives
     */
    public function index(Request $request)
    {
        // Get only document archives for super admin
        $query = Archive::with('user', 'category')
            ->where('document_type', 'document')
            ->orderBy('created_at', 'desc');

        // Filter by action
        if ($request->filled('action')) {
            $query->byAction($request->action);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search by document title or user name
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('document_title', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        $archives = $query->paginate(50);

        // Format the archives data for frontend
        $formattedArchives = $archives->getCollection()->map(function ($archive) {
            return [
                'id' => $archive->id,
                'document_title' => $archive->document_title,
                'document_type' => $archive->document_type,
                'action' => $archive->action,
                'action_color' => $archive->action_color,
                'action_icon' => $archive->action_icon,
                'description' => $archive->description,
                'formatted_date' => $archive->created_at->format('M j, Y g:i A'),
                'formatted_time' => $archive->formatted_time,
                'formatted_date_only' => $archive->created_at->format('F j, Y'),
                'category' => $archive->category ? [
                    'id' => $archive->category->id,
                    'name' => $archive->category->category_name,
                    'color' => $archive->category->color,
                    'icon' => $archive->category->icon,
                ] : null,
                'user' => [
                    'name' => $archive->user->name,
                    'email' => $archive->user->email,
                ]
            ];
        });

        // Get unique values for filters
        $actions = Archive::where('document_type', 'document')->distinct()->pluck('action');
        $categories = \App\Models\Category::orderBy('category_name')->get();

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            $archivesArray = $archives->toArray();
            $archivesArray['data'] = $formattedArchives;
            
            return response()->json([
                'archives' => $archivesArray,
                'actions' => $actions,
                'categories' => $categories
            ]);
        }

        return view('archives.index', compact('archives', 'actions', 'categories'));
    }

    /**
     * Display a specific archive entry
     */
    public function show(Archive $archive)
    {
        $archive->load('user');
        return view('archives.show', compact('archive'));
    }

    /**
     * Delete a specific archive entry
     */
    public function destroy(Archive $archive)
    {
        $archive->delete();
        
        return redirect()
            ->route('archives.index')
            ->with('success', 'Archive entry deleted successfully.');
    }
}
