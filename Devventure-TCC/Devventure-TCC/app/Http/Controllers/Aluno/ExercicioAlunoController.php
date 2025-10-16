<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exercicio;
use App\Models\RespostaExercicio;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Storage;

class ExercicioAlunoController extends Controller
{
   
    public function mostrar(Exercicio $exercicio)
    {
        $aluno = Auth::guard('aluno')->user();

        
    if (! $aluno->turmas->contains($exercicio->turma_id)) {
        abort(403, 'Acesso não autorizado a este exercício.');
    }

       
        $respostaAnterior = RespostaExercicio::where('exercicio_id', $exercicio->id)
                                             ->where('aluno_id', $aluno->id)
                                             ->first();

        
        return view('Aluno.exercicioDetalhe', compact('exercicio', 'respostaAnterior'));
    }

    
    


 public function responder(Request $request, Exercicio $exercicio)
    {
        $aluno = Auth::guard('aluno')->user();

        
        if (! $aluno->turmas->contains($exercicio->turma_id)) {
            abort(403, 'Acesso não autorizado a este exercício.');
        }
        if (Carbon::now()->isAfter($exercicio->data_fechamento)) {
            return redirect()->back()->with('sweet_error', 'O prazo para este exercício já encerrou.');
        }

        
        $request->validate([
            'arquivos_resposta'   => 'required|array|min:1',
            'arquivos_resposta.*' => 'file|max:5120',
        ]);

        // Verificação manual da extensão
        $allowedExtensions = ['pdf', 'doc', 'docx', 'zip', 'png', 'jpg', 'jpeg', 'java', 'txt', 'ppt', 'pptx'];
        foreach ($request->file('arquivos_resposta') as $arquivo) {
            $extension = strtolower($arquivo->getClientOriginalExtension());
            if (!in_array($extension, $allowedExtensions)) {
                return redirect()->back()->withErrors([
                    'arquivos_resposta' => 'O formato de ficheiro "' . $extension . '" não é permitido...'
                ])->withInput();
            }
        }

        try {
            DB::transaction(function () use ($request, $exercicio, $aluno) {
                
                $resposta = RespostaExercicio::firstOrCreate(
                    ['exercicio_id' => $exercicio->id, 'aluno_id' => $aluno->id]
                );

                if ($resposta->wasRecentlyCreated) {
                    $aluno->increment('total_pontos', $exercicio->pontos);
                }
                
                $resposta->data_envio = now();
                $resposta->save();

                // Lógica para apagar arquivos antigos
                foreach ($resposta->arquivos as $arquivoAntigo) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($arquivoAntigo->arquivo_path);
                }
                $resposta->arquivos()->delete();

               
                foreach ($request->file('arquivos_resposta') as $arquivo) {
                    // Salva o ficheiro e pega o caminho gerado
                    $path = $arquivo->store('respostas_alunos/' . $aluno->id, 'public');
                    
                    // Pega o nome original do arquivo enviado pelo aluno
                    $nomeOriginal = $arquivo->getClientOriginalName();

                    
                    $resposta->arquivos()->create([
                        'arquivo_path' => $path,
                        'nome_original' => $nomeOriginal
                    ]);
                }
            });

        } catch (\Exception $e) {
            Log::error('Erro ao submeter exercício: ' . $e->getMessage());
            return redirect()->back()->with('sweet_error', 'Ocorreu um erro inesperado ao enviar os seus ficheiros. Por favor, tente novamente.');
        }

        
        return redirect()->route('turmas.especifica', $exercicio->turma_id)
                         ->with('sweet_success', 'Exercício enviado com sucesso!');
    }
}