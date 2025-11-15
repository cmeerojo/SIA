<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tanks', function (Blueprint $table) {
            if (!Schema::hasColumn('tanks', 'is_unmarked')) {
                $table->boolean('is_unmarked')->default(false)->after('is_hidden');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tanks', function (Blueprint $table) {
            if (Schema::hasColumn('tanks', 'is_unmarked')) {
                $table->dropColumn('is_unmarked');
            }
        });
    }
};
