<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use App\Models\Month;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{
    /**
     * Display the landing page with categories.
     * Show filetracker for authenticated users.
     */
    public function index()
    {
        // DEBUG: Log when controller is called
        \Log::info('LandingPageController::index() called');
        \Log::info('User authenticated: ' . (auth()->check() ? 'YES' : 'NO'));
        
        // If user is authenticated, show filetracker dashboard
        if (auth()->check()) {
            \Log::info('Processing authenticated user');
            
            // Get filter parameters
            $filters = request()->only(['category_id', 'year', 'month', 'search']);
            
            // Start with documents query
            $documentsQuery = Document::with('category')->with('month')
                ->orderBy('document_year', 'desc')
                ->orderBy('document_month_id', 'desc')
                ->orderBy('created_at', 'desc');
            
            // Apply filters
            if (!empty($filters['category_id'])) {
                $documentsQuery->where('category_id', $filters['category_id']);
            }
            
            if (!empty($filters['year'])) {
                $documentsQuery->where('document_year', $filters['year']);
            }
            
            if (!empty($filters['month'])) {
                $documentsQuery->where('document_month_id', $filters['month']);
            }
            
            if (!empty($filters['search'])) {
                $searchTerm = $filters['search'];
                $documentsQuery->where(function($query) use ($searchTerm) {
                    $query->where('document_name', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('url', 'LIKE', "%{$searchTerm}%");
                });
            }
            
            // Get documents
            $documents = $documentsQuery->get();
            
            // Get categories
            $categories = Category::withCount('documents')
                                 ->ordered()
                                 ->get();
            
            // Get months
            $months = Month::orderBy('month_number')->get();
            
            // Get available years
            $availableYears = Document::select('document_year')
                                     ->whereNotNull('document_year')
                                     ->distinct()
                                     ->orderBy('document_year', 'desc')
                                     ->pluck('document_year');
            
            // Structure the data
            $categoriesWithDocuments = [];
            foreach ($categories as $category) {
                $categoryDocuments = $documents->filter(function($document) use ($category) {
                    return $document->category_id === $category->id;
                });
                
                $categoriesWithDocuments[] = [
                    'id' => $category->id,
                    'category_name' => $category->category_name,
                    'description' => $category->description,
                    'color' => $category->color,
                    'icon' => $category->icon,
                    'documents_count' => $category->documents_count,
                    'documents' => $categoryDocuments->values()->all()
                ];
            }
            
            // Get uncategorized documents
            $uncategorizedDocuments = $documents->filter(function($document) {
                return is_null($document->category_id);
            });
            
            // DEBUG: Log what we're about to pass to view
            \Log::info('About to return view with:');
            
            // Return filetracker view with all data
            return view('filetracker.index', [
                'categories' => $categories,
                'documents' => $documents,
                'categoriesWithDocuments' => $categoriesWithDocuments,
                'uncategorizedDocuments' => $uncategorizedDocuments,
                'availableYears' => $availableYears,
                'months' => $months,
                'filters' => $filters
            ]);
        }
        
        // Show landing page for guests
        try {
            $categories = Category::withCount('documents')
                                 ->ordered()
                                 ->get();
            
            return view('landing', compact('categories'));
        } catch (\Exception $e) {
            \Log::error('Error loading landing page: ' . $e->getMessage());
            return view('landing')->with('categories', collect([]));
        }
    }
}
