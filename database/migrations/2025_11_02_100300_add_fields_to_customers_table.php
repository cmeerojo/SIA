<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'address')) {
                $table->string('address')->nullable()->after('name');
            }
            if (!Schema::hasColumn('customers', 'contact_number')) {
                $table->string('contact_number')->nullable()->after('email');
            }
            if (!Schema::hasColumn('customers', 'reorder_point')) {
                $table->integer('reorder_point')->default(0)->after('contact_number');
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['address', 'contact_number', 'reorder_point']);
        });
    }
};
