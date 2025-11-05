<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tanks', function (Blueprint $table) {
            $table->string('size')->nullable()->after('brand');
            $table->integer('amount')->default(0)->after('size');
            $table->boolean('is_hidden')->default(false)->after('amount');
        });
    }

    public function down()
    {
        Schema::table('tanks', function (Blueprint $table) {
            $table->dropColumn(['size', 'amount', 'is_hidden']);
        });
    }
};
