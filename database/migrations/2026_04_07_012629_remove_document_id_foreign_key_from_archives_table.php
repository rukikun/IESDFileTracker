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
            // Drop the foreign key constraint
            $table->dropForeign(['document_id']);
            // Keep the document_id column but make it just an integer without foreign key
            $table->unsignedBigInteger('document_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            // Re-add the foreign key constraint
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('set null');
        });
    }
};
