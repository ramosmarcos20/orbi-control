<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasDatosTable extends Migration
{
    public function up()
    {
        Schema::create('empresas_datos', function (Blueprint $table) {
            $table->id(); // Clave primaria típica de Laravel (BIGINT)
            $table->string('empresa', 200);
            $table->text('host');
            $table->string('base', 200);
            $table->string('bdusuario', 200);
            $table->string('bdclave', 200);
            $table->string('tipo_empresa', 11)->nullable();
            $table->string('passFirma', 250);
            $table->string('servidorCorreo', 250);
            $table->string('puertoCorreo', 10);
            $table->string('correoRemitente', 250);
            $table->string('correoPass', 250);
            $table->string('correoAsunto', 250);
            $table->string('correoSiAutorizado', 250);
            $table->text('dirLogo')->nullable();
            $table->text('dirFirma')->nullable();
            $table->integer('estado');
            $table->string('ruc', 20);
            $table->string('correoEmp', 200)->default('comprobantes@orbi.ec');
            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('empresas_datos');
    }
}
