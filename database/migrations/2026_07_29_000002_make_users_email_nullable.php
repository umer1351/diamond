<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Allow phone-only registration: email may be null when a user signs
        // up with a mobile number instead. Raw SQL keeps this working without
        // requiring doctrine/dbal for the column change.
        if (Schema::hasColumn('users', 'email')) {
            DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'email')) {
            DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(255) NOT NULL');
        }
    }
};
