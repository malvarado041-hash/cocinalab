<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    protected $table = 'ingredientes';
    public $timestamps = false;
    protected $guarded = [];

    public function recetas()
    {
        return $this->belongsToMany(Receta::class, 'receta_ingrediente', 'ingrediente_id', 'receta_id')
            ->withPivot('cantidad');
    }
}
