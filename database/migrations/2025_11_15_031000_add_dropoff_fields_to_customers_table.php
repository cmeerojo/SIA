<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('dropoff_street')->nullable()->after('address');
            $table->string('dropoff_city')->nullable()->after('dropoff_street');
            $table->string('dropoff_landmark')->nullable()->after('dropoff_city');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['dropoff_street', 'dropoff_city', 'dropoff_landmark']);
        });
    }
};
