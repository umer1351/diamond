<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Optional embedded reel/video URL per social platform, shown inside each
     * "Our Socials" tile so the section is not static (client feedback).
     */
    public function up(): void
    {
        Schema::table('cms_settings', function (Blueprint $table) {
            foreach (['instagram_reel_url', 'facebook_reel_url', 'tiktok_reel_url', 'snapchat_reel_url'] as $column) {
                if (! Schema::hasColumn('cms_settings', $column)) {
                    $table->string($column)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('cms_settings', function (Blueprint $table) {
            foreach (['instagram_reel_url', 'facebook_reel_url', 'tiktok_reel_url', 'snapchat_reel_url'] as $column) {
                if (Schema::hasColumn('cms_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
