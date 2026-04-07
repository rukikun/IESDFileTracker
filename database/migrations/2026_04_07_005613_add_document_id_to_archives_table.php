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
        Schema::table('archives', function (Blueprint $table) {
            // Add document_id foreign key for better tracking
            $table->foreignId('document_id')->nullable()->after('user_id')->constrained('documents')->onDelete('set null');
            
            // Add indexes for better performance
            $table->index(['document_type', 'action']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->dropColumn('document_id');
            $table->dropIndex(['document_type', 'action']);
            $table->dropIndex('created_at');
        });
    }
};
