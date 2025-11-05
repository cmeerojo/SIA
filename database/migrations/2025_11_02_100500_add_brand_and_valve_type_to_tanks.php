<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tanks', function (Blueprint $table) {
            if (!Schema::hasColumn('tanks', 'brand')) {
                $table->string('brand')->nullable()->after('serial_code');
            }
            if (!Schema::hasColumn('tanks', 'valve_type')) {
                $table->string('valve_type')->nullable()->after('brand');
            }
        });
    }

    public function down()
    {
        Schema::table('tanks', function (Blueprint $table) {
            if (Schema::hasColumn('tanks', 'valve_type')) {
                $table->dropColumn('valve_type');
            }
            if (Schema::hasColumn('tanks', 'brand')) {
                $table->dropColumn('brand');
            }
        });
    }
};
