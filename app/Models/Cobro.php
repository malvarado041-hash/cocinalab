<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cobro extends Model
{
    protected $table = 'cobros';

    protected $guarded = [];

    protected $casts = ['cerrada_at' => 'datetime'];
}
