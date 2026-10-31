<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class DbBackup extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'Crea un respaldo SQL completo en storage/app/private/backups';

    public function handle(): int
    {
        $c = config('database.connections.'.config('database.default'));
        if (($c['driver'] ?? '') !== 'mysql') {
            $this->error('Se requiere MySQL/MariaDB.');

            return self::FAILURE;
        }
        $dir = storage_path('app/private/backups');
        if (! is_dir($dir)) {
            mkdir($dir, 0700, true);
        }
        $file = $dir.'/'.date('Y-m-d_His').'_'.bin2hex(random_bytes(3)).'.sql';
        touch($file);
        chmod($file, 0600);
        $args = ['mysqldump', '--host='.$c['host'], '--port='.($c['port'] ?? 3306), '--user='.$c['username'], '--single-transaction', '--no-tablespaces', '--default-character-set=utf8mb4', '--result-file='.$file, $c['database']];
        $p = new Process($args, null, ['MYSQL_PWD' => $c['password'] ?? '']);
        $p->setTimeout(300);
        try {
            $p->mustRun();
            chmod($file, 0600);
            file_put_contents($file.'.sha256', hash_file('sha256', $file));
            chmod($file.'.sha256', 0600);
        } catch (\Throwable $e) {
            if (is_file($file)) {
                unlink($file);
            } $this->error('No se pudo crear el respaldo. Verifica mysqldump y el acceso a la base.');

            return self::FAILURE;
        }
        $this->info('Respaldo creado: '.$file);

        return self::SUCCESS;
    }
}
