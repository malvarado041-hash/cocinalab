<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class DbExport extends Command
{
    protected $signature = 'db:export
        {--path= : Ruta de salida (default: database/seeders/data/cocinalab_data.sql)}
        {--data-only : Exporta solo los datos, sin DROP/CREATE TABLE}';

    protected $description = 'Exporta la base de datos a un archivo SQL compartible con el equipo';

    public function handle(): int
    {
        $connName = config('database.default');
        $conn = config("database.connections.{$connName}");

        if (($conn['driver'] ?? null) !== 'mysql') {
            $this->error("db:export solo soporta MySQL (conexión actual: {$conn['driver']}).");
            return self::FAILURE;
        }

        $path = $this->option('path') ?: database_path('seeders/data/cocinalab_data.sql');

        $cmd = [
            'mysqldump',
            '--host=' . $conn['host'],
            '--port=' . ($conn['port'] ?? 3306),
            '--user=' . $conn['username'],
            '--single-transaction',
            '--no-tablespaces',
            '--skip-extended-insert',
            '--complete-insert',
            '--routines=false',
            '--triggers=false',
            '--default-character-set=utf8mb4',
        ];

        if ($this->option('data-only')) {
            $cmd[] = '--no-create-info';
        }

        foreach (['sessions', 'cache', 'jobs', 'failed_jobs'] as $volatile) {
            $cmd[] = "--ignore-table={$conn['database']}.{$volatile}";
        }

        $cmd[] = $conn['database'];

        if (! self::binaryExists('mysqldump')) {
            $this->error('No se encontró `mysqldump` en el PATH. Instala mysql-client/mariadb-client.');
            return self::FAILURE;
        }

        $process = new Process($cmd, null, ['MYSQL_PWD' => $conn['password'] ?? '']);
        $process->setTimeout(null);

        try {
            $process->mustRun();
        } catch (\Throwable $e) {
            $this->error('mysqldump falló:');
            $this->error(trim($process->getErrorOutput() ?: $e->getMessage()));
            return self::FAILURE;
        }

        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($path, $process->getOutput());

        $this->info("Exportado: {$path}");
        $this->line('Tamaño: ' . round(filesize($path) / 1024, 1) . ' KB');
        $this->newLine();
        $this->line('Para compartirlo:');
        $this->line("  git add {$path} && git commit -m \"actualiza datos de la BD\"");
        $this->line('El compañero restaura con:');
        $this->line('  git pull && php artisan migrate:fresh --seed');

        return self::SUCCESS;
    }

    private static function binaryExists(string $binary): bool
    {
        $process = new Process(['which', $binary]);
        $process->run();

        return $process->isSuccessful();
    }
}
