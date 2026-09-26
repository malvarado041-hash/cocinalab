<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CocinaLabSeeder extends Seeder
{
    /** Tablas de datos que se restauran completas desde el dump. */
    private array $dataTables = ['ingredientes', 'recetas', 'receta_ingrediente', 'pago'];

    public function run(): void
    {
        $sqlFile = database_path('seeders/data/cocinalab_data.sql');
        if (! file_exists($sqlFile)) {
            $this->command->warn('Sin archivo de datos semilla, se omite el seed.');
            return;
        }

        $byTable = [];
        foreach ($this->extractInserts(file_get_contents($sqlFile)) as $stmt) {
            $byTable[$stmt['table']][] = $stmt['sql'];
        }

        if ($byTable === []) {
            $this->command->warn('El archivo semilla no contiene INSERTs, se omite el seed.');
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($this->dataTables as $table) {
            if (! isset($byTable[$table]) || ! Schema::hasTable($table)) {
                continue;
            }

            DB::table($table)->delete();

            $imported = 0;
            foreach ($byTable[$table] as $sql) {
                try {
                    DB::unprepared($sql);
                    $imported++;
                } catch (\Throwable $e) {
                    $this->command->warn("Seed {$table} falló: " . $e->getMessage());
                }
            }
            $this->command->info("Seed {$table} OK: {$imported} filas");
        }

        // users: se importan sin borrar antes (para no perder cuentas locales)
        if (isset($byTable['users']) && Schema::hasTable('users')) {
            $imported = 0;
            $skipped = 0;
            foreach ($byTable['users'] as $sql) {
                try {
                    DB::unprepared($sql);
                    $imported++;
                } catch (\Throwable $e) {
                    $skipped++;
                }
            }
            $this->command->info("Seed users OK: {$imported} filas importadas, {$skipped} omitidas (ya existían)");
        }

        // Registros legacy -> users (dumps antiguos)
        if (isset($byTable['registros'])) {
            foreach ($byTable['registros'] as $sql) {
                if (! preg_match('/VALUES\s*(.*?);/is', $sql, $m)) {
                    continue;
                }
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

    /**
     * Extrae los INSERT respetando comillas: un ';' dentro de un texto
     * (ej. procedimientos con saltos de línea) no corta la sentencia.
     *
     * @return array<int, array{table: string, sql: string}>
     */
    private function extractInserts(string $sql): array
    {
        $statements = [];
        $offset = 0;
        $len = strlen($sql);

        while (($pos = strpos($sql, 'INSERT INTO ', $offset)) !== false) {
            $i = $pos + 12; // strlen('INSERT INTO ')

            if (($sql[$i] ?? '') !== '`') {
                $offset = $i;
                continue;
            }

            $nameEnd = strpos($sql, '`', $i + 1);
            if ($nameEnd === false) {
                break;
            }
            $table = substr($sql, $i + 1, $nameEnd - $i - 1);

            $inString = false;
            $j = $nameEnd + 1;
            for (; $j < $len; $j++) {
                $c = $sql[$j];
                if ($inString) {
                    if ($c === '\\') {
                        $j++;
                        continue;
                    }
                    if ($c === "'") {
                        $inString = false;
                    }
                } elseif ($c === "'") {
                    $inString = true;
                } elseif ($c === ';') {
                    break;
                }
            }

            if ($j >= $len) {
                break;
            }

            $statements[] = [
                'table' => $table,
                'sql' => rtrim(substr($sql, $pos, $j - $pos)),
            ];
            $offset = $j + 1;
        }

        return $statements;
    }
}
