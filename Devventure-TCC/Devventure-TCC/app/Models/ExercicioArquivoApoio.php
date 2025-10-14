<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExercicioArquivoApoio extends Model
{
    use HasFactory;
    protected $table = 'exercicio_arquivos_apoio';
    
    // CORREÇÃO AQUI:
    protected $fillable = ['exercicio_id', 'arquivo_path', 'nome_original'];
}