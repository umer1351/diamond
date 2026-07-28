<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('finish_products', function (Blueprint $table) {
            if (! Schema::hasColumn('finish_products', 'product_name')) {
                $table->string('product_name')->nullable()->after('product_id');
            }

            if (! Schema::hasColumn('finish_products', 'short_description')) {
                $table->text('short_description')->nullable()->after('picture');
            }

            if (! Schema::hasColumn('finish_products', 'long_description')) {
                $table->longText('long_description')->nullable()->after('short_description');
            }

            if (! Schema::hasColumn('finish_products', 'tags')) {
                $table->text('tags')->nullable()->after('long_description');
            }
        });
    }

    public function down()
    {
        Schema::table('finish_products', function (Blueprint $table) {
            if (Schema::hasColumn('finish_products', 'tags')) {
                $table->dropColumn('tags');
            }

            if (Schema::hasColumn('finish_products', 'long_description')) {
                $table->dropColumn('long_description');
            }

            if (Schema::hasColumn('finish_products', 'short_description')) {
                $table->dropColumn('short_description');
            }

            if (Schema::hasColumn('finish_products', 'product_name')) {
                $table->dropColumn('product_name');
            }
        });
    }
};
