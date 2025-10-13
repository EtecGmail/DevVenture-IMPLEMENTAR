<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercicio extends Model
{
    use HasFactory;
    protected $table = 'exercicios';
    protected $fillable = [
    'nome',
    'descricao',
    'pontos',
    'data_publicacao',
    'data_fechamento',
    'arquivo_path',
    'imagem_apoio_path',
    'turma_id',
    'professor_id',
];

    protected $casts = [
        'data_fechamento' => 'datetime',
    ];

    // Define a relação com o modelo Turma
    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }
    
    public function respostas()
    {
        return $this->hasMany(RespostaExercicio::class);
    }

}
