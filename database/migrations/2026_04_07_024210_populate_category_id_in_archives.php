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
        // Update existing archives to populate category_id from related documents
        \DB::statement('
            UPDATE archives 
            SET category_id = (
                SELECT documents.category_id 
                FROM documents 
                WHERE documents.id = archives.document_id 
            )
            WHERE document_id IS NOT NULL 
            AND category_id IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set category_id back to null for archives that were updated
        \DB::statement('
            UPDATE archives 
            SET category_id = NULL 
            WHERE document_id IS NOT NULL 
            AND category_id IS NOT NULL
        ');
    }
};
