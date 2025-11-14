<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_name');
        });

        // Backfill first/middle/last from existing name
        DB::table('customers')->orderBy('id')->chunkById(100, function ($customers) {
            foreach ($customers as $c) {
                $full = trim($c->name ?? '');
                if ($full === '') continue;
                $parts = preg_split('/\s+/', $full);
                $first = $parts[0] ?? null;
                $last = count($parts) > 1 ? array_pop($parts) : null;
                $middle = count($parts) > 1 ? implode(' ', array_slice($parts, 1, count($parts)-2)) : null;

                // If only two parts, middle should be null
                if (count($parts) === 2) {
                    $middle = null;
                }

                DB::table('customers')->where('id', $c->id)->update([
                    'first_name' => $first,
                    'middle_name' => $middle,
                    'last_name' => $last,
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'middle_name', 'last_name']);
        });
    }
};
