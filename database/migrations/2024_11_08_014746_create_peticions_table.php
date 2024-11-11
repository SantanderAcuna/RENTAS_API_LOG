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
        Schema::create('peticions', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_peticion');
            $table->date('fecha_asignacion');
            $table->unsignedBigInteger('funcionario_id')->dafault(0)->nullable();
            $table->foreign('funcionario_id')->references('id')->on('funcionarios');
            $table->unsignedBigInteger('contribuyente_id');
            $table->foreign('contribuyente_id')->references('id')->on('contribuyentes');
            $table->date('fecha_vencimiento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peticions');
    }
};
