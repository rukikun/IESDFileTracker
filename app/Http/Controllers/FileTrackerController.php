<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use App\Models\Month;
use Illuminate\Http\Request;

class FileTrackerController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $filters = $request->only(['category_id', 'year', 'month', 'search']);
        
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
            $documentsQuery->whereHas('month', function($query) use ($filters) {
                $query->where('month_number', $filters['month']);
            });
        }
        
        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $documentsQuery->where(function($query) use ($searchTerm) {
                $query->where('document_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('category_name', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Get filtered documents
        $documents = $documentsQuery->get();
        
        // Get all categories for filter dropdown
        $categories = Category::withCount('documents')
                             ->ordered()
                             ->get();
        
        // Get all months for the frontend
        $months = Month::orderBy('month_number')->get();
        
        // Get available years for filter dropdown
        $availableYears = Document::select('document_year')
                                 ->whereNotNull('document_year')
                                 ->distinct()
                                 ->orderBy('document_year', 'desc')
                                 ->pluck('document_year');
        
        // Structure the data to ensure proper category association
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
        
        // Also include documents without category (if any)
        $uncategorizedDocuments = $documents->filter(function($document) {
            return is_null($document->category_id);
        });
        
        // Handle AJAX requests for filtering
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            // Get total documents count (without filters)
            $totalDocuments = Document::count();
            
            return response()->json([
                'documents' => $documents,
                'categoriesWithDocuments' => $categoriesWithDocuments,
                'uncategorizedDocuments' => $uncategorizedDocuments,
                'totalDocuments' => $totalDocuments
            ]);
        }
        
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
}
