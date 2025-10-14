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



public function turmaEspecificaID(Turma $turma)
{
    $turma->load('alunos', 'exercicios', 'aulas');

    $alunosNaTurma = $turma->alunos;
    $exerciciosDaTurma = $turma->exercicios;
    $aulasDaTurma = $turma->aulas;

    $totalAulasComFormulario = $aulasDaTurma->count();

    // Calcula progresso de cada aluno
    $alunosComProgresso = $alunosNaTurma->map(function ($aluno) use ($aulasDaTurma, $totalAulasComFormulario) {

        // Conta quantas aulas ele concluiu usando o relacionamento many-to-many
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

    // HISTÓRICO
    $historicoExercicios = $exerciciosDaTurma->map(function ($exercicio) {
        return [
            'tipo' => 'exercicio',
            'data' => $exercicio->data_publicacao,
            'titulo' => $exercicio->nome,
            'detalhe' => 'Entrega até ' . Carbon::parse($exercicio->data_fechamento)->format('d/m/Y H:i'),
        ];
    })->all();

    $historicoAulas = $aulasDaTurma->map(function ($aula) {
        return [
            'tipo' => 'aula',
            'data' => $aula->created_at,
            'titulo' => $aula->titulo,
            'detalhe' => 'Duração: ' . floor($aula->duracao_segundos / 60) . 'm ' . ($aula->duracao_segundos % 60) . 's',
        ];
    })->all();

    $historicoCompleto = array_merge($historicoExercicios, $historicoAulas);

    usort($historicoCompleto, function ($a, $b) {
        return Carbon::parse($b['data'] ?? '1970-01-01') <=> Carbon::parse($a['data'] ?? '1970-01-01');
    });

    return view('Professor/detalheTurma', [
        'turma' => $turma,
        'alunos' => $alunosComProgresso,
        'exercicios' => $exerciciosDaTurma,
        'historico' => $historicoCompleto,
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
