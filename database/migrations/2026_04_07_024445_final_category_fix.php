<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Manual fixes based on the data analysis
        // "Meeting Minutes March 2024" should match document ID 29 with category_id 1
        DB::table('archives')
            ->where('document_title', 'Meeting Minutes March 2024')
            ->whereNull('category_id')
            ->update(['category_id' => 1]);
            
        // For other archives, assign them to reasonable categories based on their content
        // "Annual Report 2024" and similar -> assign to category_id 1 (seems to be general/business)
        DB::table('archives')
            ->where('document_title', 'like', '%Annual Report%')
            ->whereNull('category_id')
            ->update(['category_id' => 1]);
            
        // "Draft Proposal" -> assign to category_id 2 (seems to be reports)
        DB::table('archives')
            ->where('document_title', 'like', '%Draft Proposal%')
            ->whereNull('category_id')
            ->update(['category_id' => 2]);
            
        // "Test Category" -> assign to category_id 1 (general)
        DB::table('archives')
            ->where('document_title', 'Test Category')
            ->whereNull('category_id')
            ->update(['category_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert all updates
        DB::table('archives')
            ->whereIn('document_title', [
                'Meeting Minutes March 2024',
                'Annual Report 2024',
                'Annual Report 2024 - Updated', 
                'Draft Proposal Q1 2024',
                'Test Category'
            ])
            ->whereNull('document_id')
            ->update(['category_id' => null]);
    }
};
