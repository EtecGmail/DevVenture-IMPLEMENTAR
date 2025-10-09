<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aula;
use App\Models\Formulario;

class FormularioController extends Controller
{
    public function create(Aula $aula)
    {
        
        return view('Professor.formValidacaoAula', compact('aula'));
    }

    
public function store(Request $request, Aula $aula)
{
    // Validação dos dados recebidos
    $dadosValidados = $request->validate([
        'titulo' => 'required|string|max:255',
        'perguntas' => 'required|array|min:1',
        'perguntas.*.texto' => 'required|string|max:1000', // Valida o texto de cada pergunta
        'perguntas.*.opcoes' => 'required|array|min:2',   // Garante que cada pergunta tenha pelo menos 2 opções
        'perguntas.*.opcoes.*' => 'required|string|max:255', // Valida o texto de cada opção
        'perguntas.*.correta' => 'required|integer',      // Valida que um índice de resposta correta foi enviado
    ]);

    // Cria o formulário principal
    $formulario = $aula->formulario()->create([
        'titulo' => $dadosValidados['titulo'],
    ]);

    // Itera sobre cada bloco de pergunta enviado
    foreach ($dadosValidados['perguntas'] as $perguntaData) {
        
        // Cria a pergunta no banco, associada ao formulário
        // Supondo que seu model se chame 'Pergunta' e a coluna 'texto_pergunta'
        $novaPergunta = $formulario->perguntas()->create([
            'texto_pergunta' => $perguntaData['texto'],
            'tipo_pergunta' => 'multipla_escolha', // Atualizamos o tipo
        ]);

        // Itera sobre as opções da pergunta atual
        foreach ($perguntaData['opcoes'] as $index => $textoDaOpcao) {
            
            // Verifica se o índice desta opção é o que foi marcado como correto
            $isCorrect = ($index == $perguntaData['correta']);

            // Cria a opção no banco, associada à nova pergunta
            // Supondo que seu model se chame 'Opcao' e a coluna 'texto_opcao'
            $novaPergunta->opcoes()->create([
                'texto_opcao' => $textoDaOpcao,
                'is_correct' => $isCorrect,
            ]);
        }
    }

    return redirect()->route('turmas.especificaID', $aula->turma_id)
                     ->with('formulario_criado_success', 'Formulário de múltipla escolha criado com sucesso!');
}



}
