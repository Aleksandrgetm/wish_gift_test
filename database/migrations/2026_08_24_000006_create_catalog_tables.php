<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('type', 32)->index();
            $table->string('name_lv');
            $table->string('name_ru')->nullable();
            $table->string('name_en')->nullable();
            $table->string('slug');
            $table->string('image_path')->nullable();
            $table->string('disk', 32)->nullable();
            $table->string('icon')->nullable();
            $table->string('tone', 32)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['type', 'slug']);
            $table->index(['type', 'sort_order']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name_lv');
            $table->string('name_ru')->nullable();
            $table->string('name_en')->nullable();
            $table->text('description_lv')->nullable();
            $table->text('description_ru')->nullable();
            $table->text('description_en')->nullable();
            $table->string('detail_line_lv')->nullable();
            $table->string('detail_line_ru')->nullable();
            $table->string('detail_line_en')->nullable();
            $table->string('collection')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->string('price_label')->nullable();
            $table->unsignedTinyInteger('capacity')->default(1);
            $table->string('color')->nullable();
            $table->json('palette')->nullable();
            $table->json('sizes')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_new')->default(false)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('catalog_category_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalog_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['catalog_category_id', 'product_id']);
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->string('disk', 32)->default('public');
            $table->string('alt')->nullable();
            $table->boolean('is_primary')->default(false)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('catalog_category_product');
        Schema::dropIfExists('products');
        Schema::dropIfExists('catalog_categories');
    }
};
