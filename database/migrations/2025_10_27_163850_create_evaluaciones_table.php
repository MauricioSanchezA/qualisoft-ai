<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // usuario que hizo la evaluación
            $table->text('codigo');                             // código evaluado
            $table->longText('respuesta');                      // respuesta del modelo IA
            $table->string('modelo')->default('gpt-4o-mini');   // modelo usado
            $table->timestamps();

            // Clave foránea
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};
