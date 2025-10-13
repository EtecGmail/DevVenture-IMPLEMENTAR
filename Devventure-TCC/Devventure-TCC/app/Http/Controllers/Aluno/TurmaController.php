<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Turma;
use Illuminate\Support\Facades\Auth;
use App\Models\Exercicio; // ADIÇÃO 1: Precisamos importar o Model Exercicio

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
        // ADIÇÃO 2: Pegamos o aluno que está logado para usar na consulta
        $alunoLogado = Auth::guard('aluno')->user();

        $alunosDaTurma = $turma->alunos()->orderBy('nome')->get();
        $aulasDaTurma = $turma->aulas()->orderBy('created_at', 'asc')->get();

        // ALTERAÇÃO PRINCIPAL: Substituímos a busca de exercícios pela nova consulta
        $exerciciosDaTurma = Exercicio::where('turma_id', $turma->id)
            ->where('data_publicacao', '<=', now()) // Apenas exercícios já publicados
            ->with(['respostas' => function ($query) use ($alunoLogado) {
                // Anexa a resposta específica do aluno logado
                $query->where('aluno_id', $alunoLogado->id);
            }])
            ->orderBy('data_fechamento', 'asc') // Ordena pelo mais próximo de vencer
            ->get();

        return view('Aluno/turmaEspecifica', [
            'turma' => $turma,
            'alunos' => $alunosDaTurma,
            'exercicios' => $exerciciosDaTurma, // Agora esta variável contém os exercícios com o status de entrega
            'aulas' => $aulasDaTurma
        ]);
    }
}