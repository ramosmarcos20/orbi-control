<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DatabaseConnectionService
{
    public function changeDataBaseConnection($empresaId)
    {
        $empresa = Company::find($empresaId);
        
        if (!$empresa) {
            throw new \Exception('Empresa no encontrada');
        }
    
        // Configuración completa para la conexión temporal
        Config::set('database.connections.empresa_temp', [
            'driver'    => 'mysql', // Especifica el driver
            'host'      => $empresa->host,
            'port'      => '3306', // O el puerto que necesites
            'database'  => $empresa->base,
            'username'  => $empresa->bdusuario,
            'password'  => $empresa->bdclave,
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
        ]);
    
        // Purge and reconnect
        DB::purge('empresa_temp');
        DB::reconnect('empresa_temp');
    }
    
    
}
