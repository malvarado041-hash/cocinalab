<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table = 'mesas';

    protected $guarded = [];

    protected $casts = ['cerrada_at' => 'datetime'];
}
