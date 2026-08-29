<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->string('disk')->default('public')->after('image_path');
        });

        Schema::table('home_category_images', function (Blueprint $table) {
            $table->string('disk')->nullable()->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropColumn('disk');
        });

        Schema::table('home_category_images', function (Blueprint $table) {
            $table->dropColumn('disk');
        });
    }
};
