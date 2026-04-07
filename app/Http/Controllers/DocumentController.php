<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Category;
use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with('category', 'month')
            ->orderBy('document_year', 'desc')
            ->orderBy('document_month_id', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('document_name', 'like', "%{$search}%")
                  ->orWhere('category_name', 'like', "%{$search}%");
            });
        }

        $documents = $query->get();
        return response()->json($documents);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'document_name' => 'required|string|max:255',
            'url' => 'required|url',
            'document_year' => 'nullable|integer|min:2000|max:2100',
            'document_month_id' => 'nullable|exists:months,id',
            'is_admin' => 'boolean'
        ]);

        $category = Category::find($request->category_id);

        $document = Document::create([
            'category_id' => $request->category_id,
            'category_name' => $category->category_name,
            'document_name' => $request->document_name,
            'url' => $request->url,
            'document_year' => $request->document_year,
            'document_month_id' => $request->document_month_id,
            'is_admin' => $request->is_admin ?? false
        ]);

        // Log document creation to archive - IMMEDIATE
        if (Auth::check()) {
            Archive::logAction(
                userId: Auth::id(),
                action: 'created',
                documentTitle: $document->document_name,
                documentType: 'document',
                newData: $document->toArray(),
                description: "Document '{$document->document_name}' added to {$category->category_name}",
                documentId: $document->id,
                categoryId: $document->category_id
            );
        }

        return response()->json($document->load('category', 'month'), 201);
    }

    public function show(Document $document)
    {
        return response()->json($document->load('category'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'category_id' => 'sometimes|required|exists:categories,id',
            'document_name' => 'sometimes|required|string|max:255',
            'url' => 'sometimes|required|url',
            'document_year' => 'nullable|integer|min:2000|max:2100',
            'document_month_id' => 'nullable|exists:months,id'
        ]);

        // Store old data before update
        $oldData = $document->toArray();

        if ($request->has('category_id')) {
            $category = Category::find($request->category_id);
            $document->category_name = $category->category_name;
        }

        $document->update($request->all());

        // Log document update to archive - IMMEDIATE
        if (Auth::check()) {
            $categoryName = $document->category->category_name ?? 'Unknown';
            Archive::logAction(
                userId: Auth::id(),
                action: 'edited',
                documentTitle: $document->document_name,
                documentType: 'document',
                oldData: $oldData,
                newData: $document->toArray(),
                description: "Document '{$document->document_name}' updated in {$categoryName}",
                documentId: $document->id,
                categoryId: $document->category_id
            );
        }

        return response()->json($document->load('category', 'month'));
    }

    public function destroy(Document $document)
    {
        $documentName = $document->document_name;
        $categoryName = $document->category->category_name ?? 'Unknown';
        
        // Store document data before deletion
        $oldData = $document->toArray();
        
        $document->delete();
        
        // Log document deletion to archive - IMMEDIATE
        if (Auth::check()) {
            Archive::logAction(
                userId: Auth::id(),
                action: 'deleted',
                documentTitle: $documentName,
                documentType: 'document',
                oldData: $oldData,
                description: "Document '{$documentName}' deleted from {$categoryName}",
                documentId: $document->id,
                categoryId: $document->category_id
            );
        }
        
        return response()->json(null, 204);
    }
}
