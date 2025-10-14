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
        Schema::create('exercicio_arquivos_apoio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercicio_id')->constrained('exercicios')->onDelete('cascade');
            $table->string('arquivo_path');
             $table->string('nome_original');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercicio_arquivos_apoio');
    }
};
