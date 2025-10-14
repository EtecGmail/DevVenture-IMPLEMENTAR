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
        Schema::create('respostas_exercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercicio_id')->constrained('exercicios')->onDelete('cascade');
            $table->foreignId('aluno_id')->constrained('aluno')->onDelete('cascade'); 
            $table->timestamp('data_envio');
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respostas_exercicios');
    }
};
