<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('prefix');
            }
            if (! Schema::hasColumn('products', 'tags')) {
                $table->text('tags')->nullable()->after('description');
            }
            if (! Schema::hasColumn('products', 'attributes')) {
                $table->text('attributes')->nullable()->after('tags');
            }
            if (! Schema::hasColumn('products', 'stock')) {
                $table->decimal('stock', 18, 3)->nullable()->after('attributes');
            }
        });

        Schema::table('cms_home_sections', function (Blueprint $table) {
            if (! Schema::hasColumn('cms_home_sections', 'media_type')) {
                $table->string('media_type', 20)->nullable()->after('image_path');
            }
            if (! Schema::hasColumn('cms_home_sections', 'media_url')) {
                $table->text('media_url')->nullable()->after('media_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            foreach (['description', 'tags', 'attributes', 'stock'] as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('cms_home_sections', function (Blueprint $table) {
            foreach (['media_type', 'media_url'] as $column) {
                if (Schema::hasColumn('cms_home_sections', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
