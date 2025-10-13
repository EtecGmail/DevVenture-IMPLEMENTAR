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
   public function up()
{
    // Cuidado com o nome da tabela, o seu está 'aluno' (singular)
    // O padrão do Laravel é 'alunos' (plural)
    Schema::create('aluno', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('ra')->unique();
        $table->string('semestre');
        $table->string('email')->unique();
        $table->integer('total_pontos')->default(0); // <-- CAMPO ADICIONADO
        $table->timestamp('email_verified_at')->nullable();
        $table->string('avatar')->nullable();
        $table->string('telefone')->nullable();
        $table->string('password');

        // CAMPOS PARA VERIFICAÇÃO DE DUAS ETAPAS (2FA)
        $table->string('two_factor_code')->nullable();
        $table->dateTime('two_factor_expires_at')->nullable();

        // ++ NOVOS CAMPOS PARA REDEFINIÇÃO DE SENHA ++
        $table->string('reset_password_code')->nullable();
        $table->timestamp('reset_password_expires_at')->nullable();

        $table->rememberToken();
        $table->timestamps();
        $table->string('status')->default('ativo');
    });
}

/**
 * Reverse the migrations.
 *
 * @return void
 */
public function down()
{
    Schema::dropIfExists('aluno');
}
};