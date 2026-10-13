<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comanda extends Model
{
    use HasFactory, SoftDeletes;

    public const ESTADOS = [
        'abierta' => 'Abierta',
        'en_cocina' => 'En cocina',
        'lista' => 'Lista',
        'servida' => 'Servida',
        'cancelada' => 'Cancelada',
    ];

    public const TIPOS = [
        'mesa' => 'Mesa',
        'llevar' => 'Para llevar',
    ];

    protected $fillable = [
        'mesa',
        'tipo',
        'mesero_id',
        'estado',
        'notas',
    ];

    public function mesero()
    {
        return $this->belongsTo(User::class, 'mesero_id');
    }

    public function items()
    {
        return $this->hasMany(ComandaItem::class);
    }

    public function itemsActivos()
    {
        return $this->hasMany(ComandaItem::class)->where('estado', '!=', 'cancelado');
    }

    /** Total calculado: suma cantidad × precio foto (sin cancelados). */
    public function getTotalAttribute(): float
    {
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->get();

        return round($items->where('estado', '!=', 'cancelado')
            ->sum(fn ($i) => (int) $i->cantidad * (float) $i->precio_unitario), 2);
    }

    public function getEstadoLabelAttribute(): string
    {
        return self::ESTADOS[$this->estado] ?? ucfirst($this->estado);
    }

    public function getTipoLabelAttribute(): string
    {
        return self::TIPOS[$this->tipo] ?? ucfirst($this->tipo);
    }

    public function esAbierta(): bool
    {
        return $this->estado === 'abierta';
    }

    public function esCancelada(): bool
    {
        return $this->estado === 'cancelada';
    }

    /**
     * Recalcula el estado desde los items (tras avanzar cocina):
     * - hay items en preparación → en_cocina
     * - todos los activos están listos/servidos → lista
     * - ningún item avanzó → abierta
     */
    public function actualizarEstadoDesdeItems(): void
    {
        if (in_array($this->estado, ['servida', 'cancelada'], true)) {
            return;
        }

        $estados = $this->items()->where('estado', '!=', 'cancelado')->pluck('estado')->all();

        if (empty($estados)) {
            $this->estado = 'abierta';
        } elseif (in_array('en_preparacion', $estados, true) || in_array('pendiente', $estados, true)) {
            $this->estado = in_array('en_preparacion', $estados, true) || in_array('listo', $estados, true) || in_array('servido', $estados, true)
                ? 'en_cocina'
                : 'abierta';
        } else {
            $this->estado = 'lista';
        }

        $this->save();
    }

    public function scopePorEstado($query, ?string $estado)
    {
        if ($estado) {
            $query->where('estado', $estado);
        }

        return $query;
    }

    public function scopeAbiertas($query)
    {
        return $query->whereNotIn('estado', ['servida', 'cancelada']);
    }
}
