<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Aluno extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasFactory, Notifiable, CanResetPasswordTrait;
    
    /**
     * O nome da tabela associada ao model.
     * @var string
     */
    protected $table = 'aluno';

    /**
     * Os atributos que podem ser atribuídos em massa.
     * @var array<int, string>
     */
   protected $fillable = [
    'nome',
    'ra',
    'semestre',
    'email',
    'total_pontos', 
    'telefone',
    'password',
    'avatar',
    'status',
];

    /**
     * Os atributos que devem ser ocultados para serialização.
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code', // Oculta o código de segurança
    ];

    /**
     * Os atributos que devem ter seu tipo de dado alterado.
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_expires_at' => 'datetime', // Informa ao Laravel que este campo é uma data
    ];

    public function turmas()
    {
        return $this->belongsToMany(Turma::class, 'aluno_turma');
    }
    
    public function aulas()
    {
        return $this->belongsToMany(Aula::class, 'aula_aluno')
                    ->withPivot('segundos_assistidos', 'status', 'concluido_em')
                    ->withTimestamps();
    }
    
    public function respostas()
    {
        return $this->hasMany(RespostaAluno::class);
    }

    
public function respostasExercicios()
{
    return $this->hasMany(RespostaExercicio::class, 'aluno_id');
}

    
}