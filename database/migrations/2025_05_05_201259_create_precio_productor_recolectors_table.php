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
        Schema::create('precio_productores_recolector', function (Blueprint $table) {
            $table->id('id_precio_venta_productores');
            $table->unsignedBigInteger('id_productor');
            $table->unsignedBigInteger('id_recolector');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('precio_litro');
            $table->timestamps();

            $table->foreign('id_productor')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_recolector')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precio_productores_recolector');
    }
};
