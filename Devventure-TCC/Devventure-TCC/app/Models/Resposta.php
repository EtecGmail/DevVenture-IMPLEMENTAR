<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resposta extends Model
{
    use HasFactory;

    
    
    protected $fillable = [
    'aluno_id',
    'pergunta_id',
    'opcao_id', 
];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class);
    }
}