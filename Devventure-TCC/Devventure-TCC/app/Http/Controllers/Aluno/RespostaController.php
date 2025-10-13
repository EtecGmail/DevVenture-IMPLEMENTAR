<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aula;
use App\Models\Opcao;
use App\Models\Resposta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RespostaController extends Controller
{
    public function store(Request $request, Aula $aula)
    {
        $dadosValidados = $request->validate([
            'respostas' => 'required|array',
            'respostas.*' => 'required|integer|exists:opcoes,id',
        ]);

        $aluno = Auth::guard('aluno')->user();
        $totalPerguntas = $aula->formulario->perguntas->count();
        $acertos = 0;

        $aulaJaConcluida = DB::table('aula_aluno')
                            ->where('aluno_id', $aluno->id)
                            ->where('aula_id', $aula->id)
                            ->where('status', 'concluido')
                            ->exists();

        
        DB::transaction(function () use ($dadosValidados, $aluno, $aula, &$acertos, $totalPerguntas, $aulaJaConcluida) {

            foreach ($dadosValidados['respostas'] as $perguntaId => $opcaoId) {
                $opcaoEscolhida = Opcao::find($opcaoId);

                if ($opcaoEscolhida && $opcaoEscolhida->is_correct) {
                    $acertos++;
                }
                
                Resposta::updateOrCreate(
                    ['aluno_id' => $aluno->id, 'pergunta_id' => $perguntaId],
                    ['opcao_id' => $opcaoId]
                );
            }

            $percentualAcerto = ($totalPerguntas > 0) ? ($acertos / $totalPerguntas) * 100 : 0;

            if ($percentualAcerto >= 70 && !$aulaJaConcluida) {
                
                
                $aluno->increment('total_pontos', $aula->pontos);

                
                $aula->alunos()->syncWithoutDetaching([
                    $aluno->id => [
                        'status' => 'concluido',
                        'segundos_assistidos' => $aula->duracao_segundos ?? 0,
                        'concluido_em' => now(),
                    ]
                ]);
            }
        });

        $mensagem = "Formulário enviado! Você acertou $acertos de $totalPerguntas perguntas.";
        
        
        $percentualAcerto = ($totalPerguntas > 0) ? ($acertos / $totalPerguntas) * 100 : 0;
        if ($percentualAcerto >= 70 && !$aulaJaConcluida) {
            
            $mensagem .= " Você ganhou {$aula->pontos} pontos!";
        }

        return redirect()
            ->route('turmas.especifica', $aula->turma_id)
            ->with('sweet_success', $mensagem);
    }
}