<?php

use App\Models\Category;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class)->constrained();
            $table->string('pro_name',100)->unique();
            $table->string('pro_sku')->unique();
            $table->text('pro_description')->nullable();
            $table->string('pro_img_thumbnail')->nullable();
            $table->decimal('pro_price_regular',10,2);
            $table->decimal('pro_price_sale',10,2)->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('pro_featured')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
