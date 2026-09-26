<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('cocinero', 'mesero', 'capitan', 'almacen', 'cajero', 'admin', 'sistemas') NULL DEFAULT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('cocinero', 'mesero', 'capitan', 'almacen', 'cajero', 'admin') NULL DEFAULT NULL");
    }
};
