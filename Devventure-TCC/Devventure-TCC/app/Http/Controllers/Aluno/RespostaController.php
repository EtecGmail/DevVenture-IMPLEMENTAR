<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aula;
use App\Models\Opcao;
use App\Models\Resposta;
use Illuminate\Support\Facades\DB;
use Psy\Readline\Hoa\_Protocol;
use is_correct;
class RespostaController extends Controller
{
    public function store(Request $request, Aula $aula)
    {
        
        $dadosValidados = $request->validate([
            'respostas' => 'required|array',
            'respostas.*' => 'required|integer|exists:opcoes,id',
        ]);

        $aluno = auth('aluno')->user();
        $totalPerguntas = $aula->formulario->perguntas->count();
        $acertos = 0;

        DB::transaction(function () use ($dadosValidados, $aluno, $aula, &$acertos) {

            
            foreach ($dadosValidados['respostas'] as $perguntaId => $opcaoId) {
                
                // Busca a opção que o aluno escolheu
                $opcaoEscolhida = Opcao::find($opcaoId);

                // Verifica se a opção é a correta e incrementa os acertos
                if ($opcaoEscolhida && $opcaoEscolhida->is_correct) {
                    $acertos++;
                }
                
                // Salva a resposta do aluno no banco (opcional, mas recomendado)
                // Garanta que sua tabela 'respostas' tem uma coluna 'opcao_id'
                Resposta::create([
                    'aluno_id' => $aluno->id,
                    'pergunta_id' => $perguntaId,
                    'opcao_id' => $opcaoId, 
                ]);
            }

            //  Atualiza o status da aula para 'concluido'
            $aula->alunos()->syncWithoutDetaching([
                $aluno->id => [
                    'status' => 'concluido',
                    'segundos_assistidos' => $aula->duracao_segundos ?? 0,
                    'concluido_em' => now(),
                ]
            ]);

        });

        //  Redireciona com uma mensagem de sucesso que inclui a pontuação
        $mensagem = "Aula concluída! Você acertou $acertos de $totalPerguntas perguntas.";

        return redirect()
            ->route('turmas.especifica', $aula->turma_id)
            ->with('sweet_success', $mensagem);
    }
}