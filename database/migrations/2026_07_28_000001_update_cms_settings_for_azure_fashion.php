<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('cms_settings', 'tiktok_url')) {
                $table->string('tiktok_url')->nullable()->after('twitter_url');
            }
            if (! Schema::hasColumn('cms_settings', 'snapchat_url')) {
                $table->string('snapchat_url')->nullable()->after('tiktok_url');
            }
        });

        // Refresh the storefront contact block to the Azure Fashion details.
        // Only overwrite values that still match the old seeded defaults so an
        // admin who has already edited them in the panel is not clobbered.
        $row = DB::table('cms_settings')->where('id', 1)->first();

        if ($row) {
            $updates = [];

            if (in_array($row->footer_email, [null, '', 'info@azureluxury.com'], true)) {
                $updates['footer_email'] = 'info@azure-fashion.com';
            }
            if (in_array($row->footer_phone, [null, '', '+97450903133'], true)) {
                $updates['footer_phone'] = '+974 72 23 23 24';
            }
            if (in_array($row->copyright_text, [null, '', '2026 Azure Luxury - Qatar'], true)) {
                $updates['copyright_text'] = '2026 Azure Fashion - Qatar';
            }
            if (empty($row->contact_whatsapp)) {
                $updates['contact_whatsapp'] = '+974 72 23 23 24';
            }
            if (empty($row->footer_address)) {
                $updates['footer_address'] = 'Qatar';
            }

            if ($updates !== []) {
                $updates['updated_at'] = now();
                DB::table('cms_settings')->where('id', 1)->update($updates);
            }
        }
    }

    public function down(): void
    {
        Schema::table('cms_settings', function (Blueprint $table) {
            if (Schema::hasColumn('cms_settings', 'snapchat_url')) {
                $table->dropColumn('snapchat_url');
            }
            if (Schema::hasColumn('cms_settings', 'tiktok_url')) {
                $table->dropColumn('tiktok_url');
            }
        });
    }
};
