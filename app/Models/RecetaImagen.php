<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecetaImagen extends Model
{
    protected $table = 'receta_imagenes';

    protected $fillable = ['receta_id', 'path', 'orden'];

    public function receta()
    {
        return $this->belongsTo(Receta::class, 'receta_id');
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
