<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatEstado extends Model
{
    protected $table = 'cat_estados';
    protected $fillable = ['id', 'nombre_estado'];
}
