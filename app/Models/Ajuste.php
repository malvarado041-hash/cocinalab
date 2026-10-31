<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ajuste extends Model
{
    protected $table = 'ajustes';

    protected $primaryKey = 'clave';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $guarded = [];

    public static function valor(string $clave, string $default = ''): string
    {
        return (string) (static::find($clave)?->valor ?? $default);
    }

    public static function hoy(): string
    {
        return now(static::valor('zona_horaria', 'America/Tijuana'))->toDateString();
    }

    public static function fecha($fecha, string $formato = 'd/m/Y H:i'): string
    {
        return $fecha ? $fecha->copy()->timezone(static::valor('zona_horaria', 'America/Tijuana'))->format($formato) : '—';
    }
}
