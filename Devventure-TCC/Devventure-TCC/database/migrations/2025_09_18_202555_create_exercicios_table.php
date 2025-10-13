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
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('exercicios', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->text('descricao')->nullable();
        $table->integer('pontos')->default(10); // <-- CAMPO ADICIONADO

        $table->dateTime('data_publicacao');
        $table->dateTime('data_fechamento');
        
        $table->string('arquivo_path')->nullable();
        $table->string('imagem_apoio_path')->nullable(); 

        $table->foreignId('turma_id')->constrained('turmas')->onDelete('cascade');
        
        $table->foreignId('professor_id')->constrained('professor')->onDelete('cascade');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exercicios');
    }
};