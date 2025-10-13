<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('arquivos_resposta', function (Blueprint $table) {
            $table->id();
            // Link para a "entrega" principal
            $table->foreignId('resposta_exercicio_id')->constrained('respostas_exercicios')->onDelete('cascade');
            $table->string('arquivo_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arquivos_resposta');
    }
};
