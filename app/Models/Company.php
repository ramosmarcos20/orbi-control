<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'empresas_datos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ruc', 
        'host', 
        'base', 
        'estado',
        'empresa', 
        'bdclave', 
        'bdusuario',
        'own_db'
    ];
}
