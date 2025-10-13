<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exercicio;
use App\Models\Turma;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class ExercicioController extends Controller
{
    public function exercicios(Request $request) 
 { 
    
    $professorId = Auth::guard('professor')->id();
    $status = $request->input('status', 'disponiveis');
    $searchTerm = $request->input('search');
    //Pega a data e hora atual
    $agora = Carbon::now();

   
    $query = Exercicio::with('turma')
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
        
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'turma_id' => 'required|exists:turmas,id',
            'pontos' => 'required|integer|min:0', 
            'data_publicacao' => 'required|date',
            'data_fechamento' => 'required|date|after_or_equal:data_publicacao',
            'arquivo' => 'nullable|file|mimes:pdf,doc,docx,zip|max:2048',
            'imagem_apoio' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);

        $path = null;
        if ($request->hasFile('arquivo')) {
            $path = $request->file('arquivo')->store('exercicios/arquivos', 'public');
        }

        $imagemPath = null;
        if ($request->hasFile('imagem_apoio')) {
            $imagemPath = $request->file('imagem_apoio')->store('exercicios/imagens_apoio', 'public');
        }
        
       
        Exercicio::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'pontos' => $request->pontos, 
            'data_publicacao' => $request->data_publicacao,
            'data_fechamento' => $request->data_fechamento,
            'arquivo_path' => $path,
            'imagem_apoio_path' => $imagemPath, 
            'turma_id' => $request->turma_id,
            'professor_id' => Auth::guard('professor')->id()
        ]);

        return redirect()->back()->with('sweet_success', 'Exercício criado com sucesso!');
    }
    

}
