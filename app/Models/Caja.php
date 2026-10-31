<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'cajas';

    protected $guarded = [];

    protected $casts = ['cerrada_at' => 'datetime'];
}
