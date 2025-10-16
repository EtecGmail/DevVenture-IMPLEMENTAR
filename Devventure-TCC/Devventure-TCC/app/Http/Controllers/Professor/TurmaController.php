<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Aluno;
use App\Models\Convite;
use App\Models\Aula;
use App\Models\Exercicio;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;


class TurmaController extends Controller
{
   public function turma(Request $request){
        $turma = new Turma();
        $turma->nome_turma = $request->nome_turma;
        $turma->turno = $request->turno;
        $turma->ano_turma = $request->ano_turma;
        $turma->data_inicio = $request->data_inicio;
        $turma->data_fim = $request->data_fim;
        $turma->professor_id = Auth::guard('professor')->id();
        $turma->save();

        return redirect('/professorGerenciar')->with('sweet_success', 'Turma criada com sucesso!');
    }

      public function GerenciarTurma() 
{
   $professorId = Auth::guard('professor')->id();
   
    $turmas = Turma::where('professor_id', $professorId)
                        ->withCount(['alunos', 'exercicios']) 
                        ->get();

    return view('Professor/turma', ['turmas' => $turmas]);
}

public function turmaEspecifica(Request $request)
{
    
    $professorId = Auth::guard('professor')->id();
    
  
    $searchTerm = $request->input('search');

    
    $query = Turma::where('professor_id', $professorId);

    
    if ($searchTerm) {
        $query->where('nome_turma', 'like', '%' . $searchTerm . '%');
    }

    $turmas = $query->get();

    return view('Professor/turma', ['turmas' => $turmas]);
}



public function turmaEspecificaID(Turma $turma, Request $request)
    {
        
        $turma->load('aulas');
        $aulasDaTurma = $turma->aulas;
        $totalAulasComFormulario = $aulasDaTurma->count();

        // 1. PAGINAÇÃO: ALUNOS (10 por página)
        // Usamos 'alunosPage' como nome do parâmetro da página
        $alunosPaginados = $turma->alunos()->paginate(10, ['*'], 'alunosPage');

        // Calcula o progresso APENAS para os alunos da página atual
        $alunosComProgresso = $alunosPaginados->through(function ($aluno) use ($aulasDaTurma, $totalAulasComFormulario) {
            $aulasConcluidas = $aulasDaTurma->filter(function ($aula) use ($aluno) {
                $pivot = $aula->alunos->firstWhere('id', $aluno->id)?->pivot;
                return $pivot && $pivot->status === 'concluido';
            })->count();

            $aluno->aulas_concluidas = $aulasConcluidas;
            $aluno->total_aulas_com_formulario = $totalAulasComFormulario;
            $aluno->progresso_percentual = $totalAulasComFormulario > 0
                ? round(($aulasConcluidas / $totalAulasComFormulario) * 100)
                : 0;

            return $aluno;
        });
        
        // 2. PAGINAÇÃO: EXERCÍCIOS (5 por página)
        // É uma boa prática ordenar os resultados
        $exerciciosPaginados = $turma->exercicios()->orderBy('data_publicacao', 'desc')->paginate(5, ['*'], 'exerciciosPage');

        // 3. PAGINAÇÃO: AVISOS (5 por página)
        $avisosPaginados = $turma->avisos()->orderBy('created_at', 'desc')->paginate(5, ['*'], 'avisosPage');

        // 4. PAGINAÇÃO MANUAL: HISTÓRICO (5 por página)
        // Primeiro, montamos a lista completa como antes
        $historicoExercicios = $turma->exercicios->map(function ($exercicio) {
            return [ 'tipo' => 'exercicio', 'data' => $exercicio->data_publicacao, 'titulo' => $exercicio->nome, 'detalhe' => 'Entrega até ' . Carbon::parse($exercicio->data_fechamento)->format('d/m/Y H:i')];
        })->all();
        $historicoAulas = $aulasDaTurma->map(function ($aula) {
            return [ 'tipo' => 'aula', 'data' => $aula->created_at, 'titulo' => $aula->titulo, 'detalhe' => 'Duração: ' . floor($aula->duracao_segundos / 60) . 'm ' . ($aula->duracao_segundos % 60) . 's'];
        })->all();
        
        $historicoCompleto = new Collection(array_merge($historicoExercicios, $historicoAulas));
        $historicoOrdenado = $historicoCompleto->sortByDesc('data');

        //  criação do paginador manualmente
        $perPage = 5;
        $pageName = 'historicoPage';
        $currentPage = Paginator::resolveCurrentPage($pageName, 1);
        $currentPageItems = $historicoOrdenado->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $historicoPaginado = new LengthAwarePaginator($currentPageItems, $historicoOrdenado->count(), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);

        return view('Professor/detalheTurma', [
            'turma' => $turma,
            'alunos' => $alunosComProgresso,      
            'exercicios' => $exerciciosPaginados, 
            'historico' => $historicoPaginado,     
            'avisos' => $avisosPaginados,         
        ]);
    }



public function convidarAluno(Request $request, Turma $turma)
{
   
    $request->validate([
        'ra' => 'required|exists:aluno,ra'
    ], [
        'ra.exists' => 'Nenhum aluno encontrado com este RA.' 
    ]);

    
    $aluno = Aluno::where('ra', $request->ra)->first();

    
    $jaEstaNaTurma = $turma->alunos()->where('aluno_id', $aluno->id)->exists();
    if ($jaEstaNaTurma) {
        
        return back()->with('sweet_error_convite', 'O aluno já está em outra turma.'); 
    }

    
    $convitePendente = Convite::where('turma_id', $turma->id)
                                 ->where('aluno_id', $aluno->id)
                                 ->where('status', 'pendente')
                                 ->exists();
    if ($convitePendente) {
        
        return back()->with('sweet_success_convite', 'Convite já existe e está pendente.'); 
    }

    
    Convite::create([
        'turma_id' => $turma->id,
        'aluno_id' => $aluno->id,
        'professor_id' => Auth::guard('professor')->id(),
        'status' => 'pendente' 
    ]);

    
    return back()->with('sweet_success_convite', 'Convite enviado com sucesso!'); 
}


public function formsAula(Request $request, Turma $turma)
{
    
    $request->validate([
        'titulo' => ['required', 'string', 'max:255'],
        'video_url' => ['required', 'url'],
        'duracao_texto' => ['required', 'string', 'regex:/^\d+([,.]\d{1,2})?$/'],
        'pontos' => ['required', 'integer', 'min:0'], // <-- NOVA VALIDAÇÃO
    ]);

    $duracaoInput = str_replace(',', '.', $request->duracao_texto);
    $partes = explode('.', $duracaoInput);
    $minutos = (int) $partes[0];
    $segundos = isset($partes[1]) ? (int) $partes[1] : 0;
    
    if ($segundos >= 60) {
        return back()->withErrors(['duracao_texto' => 'Os segundos não podem ser 60 ou mais.'])->withInput();
    }

    $totalEmSegundos = ($minutos * 60) + $segundos;

    $aula = new Aula();
    $aula->turma_id = $turma->id;
    $aula->titulo = $request->titulo;
    $aula->video_url = $request->video_url;
    $aula->duracao_segundos = $totalEmSegundos;
    $aula->pontos = $request->pontos;
    $aula->save();

    $urlCriarFormulario = route('formularios.create', $aula);

    $feedback = [
        'message' => 'Aula adicionada com sucesso!',
        'next_action_url' => $urlCriarFormulario,
        'next_action_text' => 'Criar Formulário de Validação',
    ];

    return redirect()->route('turmas.especificaID', $turma->id)
                     ->with('aula_criada_feedback', $feedback);
}

public function mostrarRanking(Turma $turma)
{
    
    if ($turma->professor_id !== Auth::guard('professor')->id()) {
        abort(403, 'Acesso não autorizado a este ranking.');
    }

    
    $alunosRanking = $turma->alunos()
                           ->orderBy('total_pontos', 'desc')
                           ->orderBy('updated_at', 'asc')
                           ->get();

    
    return view('Aluno.ranking', [
        'turma' => $turma,
        'alunosRanking' => $alunosRanking,
        'backRoute' => route('turmas.especificaID', $turma->id) 
    ]);
}

}
