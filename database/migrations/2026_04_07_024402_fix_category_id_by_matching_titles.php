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
        // Update archives by matching document titles with documents
        \DB::statement('
            UPDATE archives 
            SET category_id = (
                SELECT documents.category_id 
                FROM documents 
                WHERE TRIM(documents.document_name) = TRIM(archives.document_title)
                LIMIT 1
            )
            WHERE category_id IS NULL
            AND EXISTS (
                SELECT 1 FROM documents 
                WHERE TRIM(documents.document_name) = TRIM(archives.document_title)
                AND documents.category_id IS NOT NULL
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set category_id back to null for archives that were updated by this migration
        \DB::statement('
            UPDATE archives 
            SET category_id = NULL 
            WHERE category_id IS NOT NULL
            AND document_id IS NULL
            AND EXISTS (
                SELECT 1 FROM documents 
                WHERE TRIM(documents.document_name) = TRIM(archives.document_title)
                AND documents.category_id = archives.category_id
            )
        ');
    }
};
