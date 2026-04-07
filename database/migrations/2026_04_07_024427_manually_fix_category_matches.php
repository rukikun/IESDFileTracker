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
        // Manual fixes for specific matches
        DB::table('archives')
            ->where('document_title', 'Meeting Minutes March 2024')
            ->whereNull('category_id')
            ->update(['category_id' => 1]); // Meeting Minutes March 2024 has category_id 1
            
        // For the rest that don't have exact matches, let's use LIKE matching
        DB::statement('
            UPDATE archives 
            SET category_id = (
                SELECT documents.category_id 
                FROM documents 
                WHERE documents.document_name LIKE CONCAT(\'%\', archives.document_title, \'%\')
                OR archives.document_title LIKE CONCAT(\'%\', documents.document_name, \'%\')
                LIMIT 1
            )
            WHERE category_id IS NULL
            AND EXISTS (
                SELECT 1 FROM documents 
                WHERE documents.document_name LIKE CONCAT(\'%\', archives.document_title, \'%\')
                OR archives.document_title LIKE CONCAT(\'%\', documents.document_name, \'%\')
                AND documents.category_id IS NOT NULL
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the manual fix
        DB::table('archives')
            ->where('document_title', 'Meeting Minutes March 2024')
            ->where('category_id', 1)
            ->whereNull('document_id')
            ->update(['category_id' => null]);
            
        // Note: The LIKE matching updates are harder to revert precisely,
        // but this should handle the main cases
    }
};
