<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Note: this migration uses the `change()` method which requires the doctrine/dbal package.
        // Use raw SQL to avoid requiring doctrine/dbal for column changes.
        // This makes `tank_id` nullable on MySQL.
        DB::statement('ALTER TABLE `tank_deliveries` MODIFY `tank_id` BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `tank_deliveries` MODIFY `tank_id` BIGINT UNSIGNED NOT NULL');
    }
};
