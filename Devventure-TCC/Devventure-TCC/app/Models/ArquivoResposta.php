<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArquivoResposta extends Model
{
    use HasFactory;
   
    protected $table = 'arquivos_resposta';
    protected $fillable = ['resposta_exercicio_id', 'arquivo_path', 'nome_original']; // <-- CAMPO ADICIONADO


    public function respostaExercicio()
    {
        return $this->belongsTo(RespostaExercicio::class);
    }
}