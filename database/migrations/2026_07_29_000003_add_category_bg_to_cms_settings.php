<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('cms_settings', 'category_bg_path')) {
                $table->string('category_bg_path')->nullable()->after('snapchat_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cms_settings', function (Blueprint $table) {
            if (Schema::hasColumn('cms_settings', 'category_bg_path')) {
                $table->dropColumn('category_bg_path');
            }
        });
    }
};
