<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Display the landing page with categories.
     * Redirect authenticated users to dashboard.
     */
    public function index()
    {
        // Redirect authenticated users to dashboard
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        
        try {
            $categories = Category::withCount('documents')
                                 ->ordered()
                                 ->get();
            
            // Debug: Log the categories count
            \Log::info('Categories count: ' . $categories->count());
            
            return view('landing', compact('categories'));
        } catch (\Exception $e) {
            \Log::error('Error fetching categories: ' . $e->getMessage());
            // Return view without categories if there's an error
            return view('landing', ['categories' => collect()]);
        }
    }
}
