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
    Schema::table('arquivos_resposta', function (Blueprint $table) {
        $table->string('nome_original')->after('arquivo_path');
    });
}

public function down(): void
{
    Schema::table('arquivos_resposta', function (Blueprint $table) {
        $table->dropColumn('nome_original');
    });
}
};
