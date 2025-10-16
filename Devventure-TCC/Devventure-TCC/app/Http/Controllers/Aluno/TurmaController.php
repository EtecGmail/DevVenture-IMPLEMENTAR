<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Turma;
use Illuminate\Support\Facades\Auth;
use App\Models\Exercicio; 
use Illuminate\Support\Facades\DB;


class TurmaController extends Controller
{
    public function minhasTurmas()
    {
        $aluno = Auth::guard('aluno')->user();
        $turmas = $aluno->turmas()->get();
        return view('Aluno/turma', ['turmas' => $turmas]);
    }

 public function mostrarTurmaEspecifica(Turma $turma, Request $request)
{
    $alunoLogado = Auth::guard('aluno')->user();

    // PAGINAÇÃO: Colegas de Turma (10 por página, na sidebar)
    $alunosDaTurma = $turma->alunos()
        ->orderBy('nome')
        ->paginate(10, ['*'], 'colegasPage');

    // PAGINAÇÃO: Aulas (6 por página)
    $aulasDaTurma = $turma->aulas()
        ->orderBy('created_at', 'desc')
        ->paginate(6, ['*'], 'aulasPage');
    
    // PAGINAÇÃO: Avisos (5 por página)
    $avisosDaTurma = $turma->avisos()
        ->with('professor')
        ->orderBy('created_at', 'desc')
        ->paginate(5, ['*'], 'avisosPage');

    // PAGINAÇÃO: Exercícios (6 por página)
    $exerciciosDaTurma = Exercicio::where('turma_id', $turma->id)
        ->where('data_publicacao', '<=', now()) 
        ->with(['respostas' => function ($query) use ($alunoLogado) {
            $query->where('aluno_id', $alunoLogado->id);
        }])
        ->orderBy('data_fechamento', 'asc') 
        ->paginate(6, ['*'], 'exerciciosPage');

    return view('Aluno/turmaEspecifica', [
        'turma' => $turma,
        'alunos' => $alunosDaTurma,
        'exercicios' => $exerciciosDaTurma, 
        'aulas' => $aulasDaTurma,
        'avisos' => $avisosDaTurma 
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