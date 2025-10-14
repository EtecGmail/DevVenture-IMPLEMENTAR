<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExercicioImagemApoio extends Model
{
    use HasFactory;
    protected $table = 'exercicio_imagens_apoio';
    protected $fillable = ['exercicio_id', 'imagem_path'];
}