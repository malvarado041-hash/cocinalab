<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class DbRestore extends Command
{
    protected $signature = 'db:restore {archivo} {--confirm-database= : Nombre exacto de la base que se reemplazará}';

    protected $description = 'Restaura un respaldo SQL con verificación SHA-256 y confirmación explícita del destino';

    public function handle(): int
    {
        $c = config('database.connections.'.config('database.default'));
        $file = realpath($this->argument('archivo'));
        if (($c['driver'] ?? '') !== 'mysql' || $this->option('confirm-database') !== $c['database']) {
            $this->error('Especifica --confirm-database con el nombre exacto de la base destino.');

            return self::FAILURE;
        }
        if (! $file || ! is_readable($file) || ! is_file($file.'.sha256') || ! hash_equals(trim(file_get_contents($file.'.sha256')), hash_file('sha256', $file))) {
            $this->error('Archivo inexistente o checksum inválido. Conserva el archivo .sha256 junto al respaldo.');

            return self::FAILURE;
        }
        // El operador debe poner el servicio en mantenimiento antes de restaurar.
        if (app()->environment('production') && ! app()->isDownForMaintenance()) {
            $this->error('Activa mantenimiento con php artisan down antes de restaurar producción.');

            return self::FAILURE;
        }
        $stream = fopen($file, 'r');
        $p = new Process(['mysql', '--host='.$c['host'], '--port='.($c['port'] ?? 3306), '--user='.$c['username'], $c['database']], null, ['MYSQL_PWD' => $c['password'] ?? '']);
        $p->setInput($stream);
        $p->setTimeout(300);
        try {
            $p->mustRun();
        } catch (\Throwable $e) {
            $this->error('Falló la restauración. Revisa conectividad y compatibilidad del respaldo antes de reabrir el servicio.');

            return self::FAILURE;
        } finally {
            fclose($stream);
        }
        $this->info('Restauración terminada en '.$c['database'].'. Ejecuta php artisan migrate antes de reabrir.');

        return self::SUCCESS;
    }
}
