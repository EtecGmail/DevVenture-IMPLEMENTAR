<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;

    protected $fillable = [
    'turma_id',
    'titulo',
    'pontos', 
    'video_url',
    'duracao_segundos'
];

    
    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }


    public function formulario()
    {
        return $this->hasOne(Formulario::class);
    }

    
    public function alunos()
{
   return $this->belongsToMany(Aluno::class, 'aula_aluno')
                ->withPivot('segundos_assistidos', 'status', 'concluido_em')
                ->withTimestamps();
}
}