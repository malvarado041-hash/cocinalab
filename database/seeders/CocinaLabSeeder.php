<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CocinaLabSeeder extends Seeder
{
    public function run(): void
    {
        $sqlFile = database_path('seeders/data/cocinalab_data.sql');
        if (! file_exists($sqlFile)) {
            $this->command->warn('Sin archivo de datos semilla, se omite el seed.');
            return;
        }

        $sql = file_get_contents($sqlFile);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach (['ingredientes', 'recetas', 'receta_ingrediente'] as $tabla) {
            if (preg_match('/INSERT INTO `' . $tabla . '`.*?;/s', $sql, $m)) {
                DB::unprepared($m[0]);
                $this->command->info("Seed {$tabla} OK");
            }
        }

        // registros -> users
        if (preg_match('/INSERT INTO `registros`.*?VALUES(.*?);/s', $sql, $m)) {
            preg_match_all("/\\((\\d+),\\s*'((?:[^']|'')*)',\\s*'((?:[^']|'')*)',\\s*'((?:[^']|'')*)'\\)/", $m[1], $rows, PREG_SET_ORDER);
            foreach ($rows as $r) {
                DB::table('users')->updateOrInsert(
                    ['email' => str_replace("''", "'", $r[3])],
                    [
                        'name' => str_replace("''", "'", $r[2]),
                        'password' => str_replace("''", "'", $r[4]),
                        'role' => 'cocinero',
                        'status' => 'activo',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
            $this->command->info('Seed users (desde registros) OK: ' . count($rows));
        }

        // Admin por defecto
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@cocinalab.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $this->command->info('Admin user created: admin@cocinalab.com / admin123');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
