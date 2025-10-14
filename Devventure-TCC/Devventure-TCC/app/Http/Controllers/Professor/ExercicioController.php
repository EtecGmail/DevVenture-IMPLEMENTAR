<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exercicio;
use App\Models\Turma;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; // <-- ADICIONE ESTE IMPORT

class ExercicioController extends Controller
{
    public function exercicios(Request $request) 
    { 
        $professorId = Auth::guard('professor')->id();
        $status = $request->input('status', 'disponiveis');
        $searchTerm = $request->input('search');
        $agora = Carbon::now();

        $query = Exercicio::with(['turma', 'arquivosApoio', 'imagensApoio'])
                          ->where('professor_id', $professorId);

        if ($status == 'disponiveis') {
            $query->where('data_fechamento', '>', $agora);
        } else {
            $query->where('data_fechamento', '<=', $agora);
        }

        if ($searchTerm) {
            $query->where('nome', 'like', '%' . $searchTerm . '%');
        }

        $exercicios = $query->get();
        
        $turmas = Turma::where('professor_id', $professorId)->get();
        
        return view('Professor/Exercicio', [
            'exercicios' => $exercicios,
            'status' => $status,
            'turmas' => $turmas 
        ]);
    }

    public function CriarExercicios(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'turma_id' => 'required|exists:turmas,id',
            'pontos' => 'required|integer|min:0', 
            'data_publicacao' => 'required|date',
            'data_fechamento' => 'required|date|after_or_equal:data_publicacao',
            'arquivos_apoio' => 'nullable|array',
            'arquivos_apoio.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,zip,txt,java|max:5120',
            'imagens_apoio' => 'nullable|array',
            'imagens_apoio.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);

        
        if ($validator->fails()) {
            dd($validator->errors());
        }

        
        try {
            DB::transaction(function () use ($request) {
                $exercicio = Exercicio::create([
                    'nome' => $request->nome,
                    'descricao' => $request->descricao,
                    'pontos' => $request->pontos, 
                    'data_publicacao' => $request->data_publicacao,
                    'data_fechamento' => $request->data_fechamento,
                    'turma_id' => $request->turma_id,
                    'professor_id' => Auth::guard('professor')->id()
                ]);

                if ($request->hasFile('arquivos_apoio')) {
                    foreach ($request->file('arquivos_apoio') as $arquivo) {
                        $path = $arquivo->store('exercicios/arquivos_apoio', 'public');
                        $nomeOriginal = $arquivo->getClientOriginalName();
                        $exercicio->arquivosApoio()->create([
                            'arquivo_path' => $path,
                            'nome_original' => $nomeOriginal 
                        ]);
                    }
                }

                if ($request->hasFile('imagens_apoio')) {
                    foreach ($request->file('imagens_apoio') as $imagem) {
                        $path = $imagem->store('exercicios/imagens_apoio', 'public');
                        $exercicio->imagensApoio()->create(['imagem_path' => $path]);
                    }
                }
            });

        } catch (\Exception $e) {
            Log::error('Erro ao criar exercício: ' . $e->getMessage());
           
        }

        return redirect()->back()->with('sweet_success', 'Exercício criado com sucesso!');
    }
}

