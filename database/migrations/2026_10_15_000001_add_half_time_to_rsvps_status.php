<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE rsvps MODIFY COLUMN status ENUM('PENDING','REJECTED','APPROVED','ABSENT','HALF_TIME') DEFAULT 'PENDING'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE rsvps MODIFY COLUMN status ENUM('PENDING','REJECTED','APPROVED','ABSENT') DEFAULT 'PENDING'");
    }
};
