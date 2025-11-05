<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tanks', function (Blueprint $table) {
            $table->id();
            $table->string('serial_code')->unique();
            $table->string('status')->default('filled'); // filled, empty, with_customer
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tanks');
    }
};
