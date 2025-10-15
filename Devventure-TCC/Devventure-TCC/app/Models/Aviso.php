<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aviso extends Model
{
    use HasFactory;

    protected $fillable = [
        'professor_id',
        'titulo',
        'conteudo',
    ];

    // Um aviso pertence a um professor
    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id'); 
    }

    // Um aviso pode ser para muitas turmas
    public function turmas()
    {
        return $this->belongsToMany(Turma::class, 'aviso_turma');
    }
}