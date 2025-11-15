<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tank_deliveries', function (Blueprint $table) {
            $table->string('start_location')->nullable()->after('driver_id');
            $table->string('dropoff_location')->nullable()->after('start_location');
        });
    }

    public function down(): void
    {
        Schema::table('tank_deliveries', function (Blueprint $table) {
            $table->dropColumn(['start_location', 'dropoff_location']);
        });
    }
};
