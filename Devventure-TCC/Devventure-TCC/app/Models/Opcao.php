<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opcao extends Model
{
    use HasFactory;

    /**
     *
     *
     * @var string
     */
    protected $table = 'opcoes';

    /**
     * 
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pergunta_id',
        'texto_opcao',
        'is_correct',
    ];

    
    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class);
    }
}