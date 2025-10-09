<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa.
     * A coluna 'opcoes' foi removida daqui.
     */
    protected $fillable = [
        'formulario_id',
        'texto_pergunta',
        'tipo_pergunta',
    ];

   
    public function formulario()
    {
        return $this->belongsTo(Formulario::class);
    }

    
    public function respostas()
    {
        return $this->hasMany(Resposta::class); 
    }

    
    public function opcoes()
    {
        return $this->hasMany(Opcao::class);
    }
}