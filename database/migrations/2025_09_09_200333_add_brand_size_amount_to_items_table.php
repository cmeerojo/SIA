<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'brand')) {
                $table->string('brand')->nullable();
            }
            if (!Schema::hasColumn('items', 'size')) {
                $table->string('size')->nullable();
            }
            if (!Schema::hasColumn('items', 'amount')) {
                $table->integer('amount')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'brand')) {
                $table->dropColumn('brand');
            }
            if (Schema::hasColumn('items', 'size')) {
                $table->dropColumn('size');
            }
            if (Schema::hasColumn('items', 'amount')) {
                $table->dropColumn('amount');
            }
        });
    }
};
