<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ALuno;
use App\Models\Exercicio;
class Turma extends Model
{
    use HasFactory;
    protected $table = 'turmas';
    protected $fillable = [ 
        'nome_turma',
        'turno',
        'ano_turma',
        'data_inicio',
        'data_fim',
        'professor_id'
    ];

     public function exercicios()
    {
       
        return $this->hasMany(Exercicio::class, 'turma_id');
    }

  public function alunos()
{
    
    return $this->belongsToMany(Aluno::class, 'aluno_turma', 'turma_id', 'aluno_id');
}

public function professor() {
    return $this->belongsTo(Professor::class);
}

public function aulas()
{
    
    return $this->hasMany(Aula::class, 'turma_id');
}

public function avisos()
{
    return $this->belongsToMany(Aviso::class, 'aviso_turma')->orderBy('created_at', 'desc');
}

}
