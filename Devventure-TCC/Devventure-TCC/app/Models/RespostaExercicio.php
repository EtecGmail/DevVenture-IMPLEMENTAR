<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespostaExercicio extends Model
{
    use HasFactory;

    /**
     * 
     * 
     *
     *
     * @var string
     */
    protected $table = 'respostas_exercicios'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
   protected $fillable = [
    'exercicio_id',
    'aluno_id',
    'data_envio',
    'nota',
    'conceito',
    'feedback',
];
    
    public function arquivos()
    {
        return $this->hasMany(ArquivoResposta::class);
    }
        public function exercicio()
    {
        return $this->belongsTo(Exercicio::class);
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
}