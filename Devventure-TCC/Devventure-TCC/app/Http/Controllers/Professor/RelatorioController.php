<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Exercicio;
use App\Models\RespostaExercicio;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelatorioController extends Controller
{
    /**
     * Mostra o dashboard de relatórios geral da turma.
     */
    public function index(Turma $turma)
    {
        if ($turma->professor_id !== Auth::guard('professor')->id()) {
            abort(403);
        }

        $mediaGeral = RespostaExercicio::whereHas('exercicio', function ($query) use ($turma) {
            $query->where('turma_id', $turma->id);
        })->avg('nota');

        $alunosDestaque = $turma->alunos()->orderBy('total_pontos', 'desc')->take(3)->get();

        $ultimoExercicio = $turma->exercicios()->latest('data_publicacao')->first();
        $alunosAtencao = collect();
        $taxaEngajamento = 0;

        if ($ultimoExercicio) {
            $totalAlunos = $turma->alunos()->count();
            $respostasUltimoExercicio = $ultimoExercicio->respostas()->count();
            if ($totalAlunos > 0) {
                $taxaEngajamento = round(($respostasUltimoExercicio / $totalAlunos) * 100);
            }
            $alunosQueEntregaramIds = $ultimoExercicio->respostas()->pluck('aluno_id');
            $alunosAtencao = $turma->alunos()->whereNotIn('id', $alunosQueEntregaramIds)->take(5)->get();
        }

        $desempenhoPorExercicio = Exercicio::where('turma_id', $turma->id)
            ->whereHas('respostas')
            ->withAvg('respostas', 'nota')
            ->get();

        return view('Professor.relatorios.index', compact(
            'turma', 'mediaGeral', 'alunosDestaque', 'alunosAtencao',
            'desempenhoPorExercicio', 'taxaEngajamento', 'ultimoExercicio'
        ));
    }

    /**
     * Mostra o relatório individual de um aluno específico.
     */
    public function relatorioAluno(Turma $turma, Aluno $aluno)
    {
        if ($turma->professor_id !== Auth::guard('professor')->id()) {
            abort(403);
        }

        $aluno->load([
            'respostasExercicios.exercicio',
            'aulas' => function ($query) {
                // ===== A CORREÇÃO ESTÁ AQUI =====
                // Trocamos 'pivot.status' pelo nome real da tabela 'aula_aluno.status'
                $query->where('aula_aluno.status', 'concluido');
            }
        ]);

        return view('Professor.relatorios.aluno', compact('turma', 'aluno'));
    }
}

