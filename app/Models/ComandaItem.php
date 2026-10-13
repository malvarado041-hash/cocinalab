<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComandaItem extends Model
{
    use HasFactory;

    public const ESTADOS = [
        'pendiente' => 'Pendiente',
        'en_preparacion' => 'En preparación',
        'listo' => 'Listo',
        'servido' => 'Servido',
        'cancelado' => 'Cancelado',
    ];

    /** Siguiente estado en el flujo de cocina (null si es final). */
    public const SIGUIENTE = [
        'pendiente' => 'en_preparacion',
        'en_preparacion' => 'listo',
    ];

    protected $table = 'comanda_items';

    protected $fillable = [
        'comanda_id',
        'receta_id',
        'cantidad',
        'precio_unitario',
        'notas',
        'estado',
    ];

    public function comanda()
    {
        return $this->belongsTo(Comanda::class);
    }

    public function receta()
    {
        return $this->belongsTo(Receta::class);
    }

    public function getSubtotalAttribute(): float
    {
        return round((int) $this->cantidad * (float) $this->precio_unitario, 2);
    }

    public function getEstadoLabelAttribute(): string
    {
        return self::ESTADOS[$this->estado] ?? ucfirst($this->estado);
    }

    public function puedeAvanzar(): bool
    {
        return isset(self::SIGUIENTE[$this->estado]);
    }
}
