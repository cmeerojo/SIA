<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Make the email column nullable to allow individual customers without email
        DB::statement('ALTER TABLE customers MODIFY email VARCHAR(255) NULL');
    }

    public function down(): void
    {
        // Revert to NOT NULL; set any NULL emails to empty string before altering to avoid failure
        DB::statement("UPDATE customers SET email = '' WHERE email IS NULL");
        DB::statement('ALTER TABLE customers MODIFY email VARCHAR(255) NOT NULL');
    }
};
