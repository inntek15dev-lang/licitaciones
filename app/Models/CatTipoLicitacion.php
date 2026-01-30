<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatTipoLicitacion extends Model
{
    protected $table = 'cat_tipos_licitacion';
    protected $fillable = ['id', 'nombre'];
}
