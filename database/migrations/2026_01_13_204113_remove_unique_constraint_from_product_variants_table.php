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
        Schema::table('product_variants', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['product_id']);
            
            // Drop the unique constraint on product_id
            $table->dropUnique(['product_id']);
        });
        
        Schema::table('product_variants', function (Blueprint $table) {
            // Re-add the foreign key constraint without unique
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            // Add a composite unique constraint to prevent duplicate color/capacity combinations for the same product
            $table->unique(['product_id', 'color_id', 'capacity_id'], 'product_variants_unique_combination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // Drop the composite unique constraint
            $table->dropUnique('product_variants_unique_combination');
            
            // Drop the foreign key constraint
            $table->dropForeign(['product_id']);
        });
        
        Schema::table('product_variants', function (Blueprint $table) {
            // Re-add the unique constraint on product_id and foreign key (this will cause issues, but it's for rollback)
            $table->foreignId('product_id')->unique()->constrained('products')->onDelete('cascade');
        });
    }
};