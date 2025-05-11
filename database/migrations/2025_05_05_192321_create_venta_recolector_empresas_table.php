<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('venta_recolector_empresas', function (Blueprint $table) {
            $table->id('id_venta_recolector_empresa');
            $table->unsignedBigInteger('id_empresa');
            $table->unsignedBigInteger('id_recolector');
            $table->date('fecha');
            $table->integer('cantidad_litros');
            $table->integer('precio_litro');
            $table->timestamps();

            $table->foreign('id_empresa')->references('id_empresa')->on('empresas')->onDelete('cascade');
            $table->foreign('id_recolector')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_recolector_empresas');
    }
};
