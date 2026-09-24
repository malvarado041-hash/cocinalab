<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    protected $table = 'recetas';
    public $timestamps = false;
    protected $guarded = [];

    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class, 'receta_ingrediente', 'receta_id', 'ingrediente_id')
            ->withPivot('cantidad');
    }
}
