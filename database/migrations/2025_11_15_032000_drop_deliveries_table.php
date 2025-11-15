<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('deliveries')) {
            Schema::drop('deliveries');
        }
    }

    public function down(): void
    {
        // Intentionally left blank; restoring the table is not supported here.
    }
};
