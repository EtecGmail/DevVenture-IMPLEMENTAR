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
    Schema::create('aulas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('turma_id')->constrained()->onDelete('cascade');
        $table->string('titulo');
        $table->integer('pontos')->default(5);
        $table->string('video_url');
        $table->integer('duracao_segundos')->comment('Duração total do vídeo em segundos');
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
        Schema::dropIfExists('aulas');
    }
};
