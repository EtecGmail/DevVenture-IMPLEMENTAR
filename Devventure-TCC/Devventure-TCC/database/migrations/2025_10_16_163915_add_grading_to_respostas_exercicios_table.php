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
    Schema::table('respostas_exercicios', function (Blueprint $table) {
        $table->integer('nota')->nullable()->after('data_envio');
        $table->string('conceito', 2)->nullable()->after('nota'); // MB, B, R, I
        $table->text('feedback')->nullable()->after('conceito');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('respostas_exercicios', function (Blueprint $table) {
            //
        });
    }
};
