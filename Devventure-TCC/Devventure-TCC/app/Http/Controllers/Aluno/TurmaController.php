<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Turma;
use Illuminate\Support\Facades\Auth;
use App\Models\Exercicio; 

class TurmaController extends Controller
{
    public function minhasTurmas()
    {
        $aluno = Auth::guard('aluno')->user();
        $turmas = $aluno->turmas()->get();
        return view('Aluno/turma', ['turmas' => $turmas]);
    }

    public function mostrarTurmaEspecifica(Turma $turma)
    {
        
        $alunoLogado = Auth::guard('aluno')->user();

        $alunosDaTurma = $turma->alunos()->orderBy('nome')->get();
        $aulasDaTurma = $turma->aulas()->orderBy('created_at', 'asc')->get();

        
        $exerciciosDaTurma = Exercicio::where('turma_id', $turma->id)
            ->where('data_publicacao', '<=', now()) 
            ->with(['respostas' => function ($query) use ($alunoLogado) {
                // Anexa a resposta específica do aluno logado
                $query->where('aluno_id', $alunoLogado->id);
            }])
            ->orderBy('data_fechamento', 'asc') 
            ->get();

        return view('Aluno/turmaEspecifica', [
            'turma' => $turma,
            'alunos' => $alunosDaTurma,
            'exercicios' => $exerciciosDaTurma, 
            'aulas' => $aulasDaTurma
        ]);
    }

    public function mostrarRanking(Turma $turma)
{
    
    if (!Auth::guard('aluno')->user()->turmas->contains($turma->id)) {
        abort(403, 'Acesso não autorizado a este ranking.');
    }

  
    $alunosRanking = $turma->alunos()
                           ->orderBy('total_pontos', 'desc') // Ordena por pontos (mais alto primeiro)
                           ->orderBy('updated_at', 'asc')    // Desempate: quem chegou lá primeiro
                           ->get();

    return view('Aluno.ranking', [
    'turma' => $turma,
    'alunosRanking' => $alunosRanking,
    'backRoute' => route('turmas.especifica', $turma->id) 
]);
}
}