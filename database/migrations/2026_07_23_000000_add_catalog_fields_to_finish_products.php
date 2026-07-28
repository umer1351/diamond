<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('finish_products', function (Blueprint $table) {
            if (! Schema::hasColumn('finish_products', 'sizes')) {
                $table->string('sizes')->nullable()->after('tags');
            }
            if (! Schema::hasColumn('finish_products', 'stock_quantity')) {
                $table->integer('stock_quantity')->nullable()->default(0)->after('sizes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('finish_products', function (Blueprint $table) {
            if (Schema::hasColumn('finish_products', 'stock_quantity')) {
                $table->dropColumn('stock_quantity');
            }
            if (Schema::hasColumn('finish_products', 'sizes')) {
                $table->dropColumn('sizes');
            }
        });
    }
};
