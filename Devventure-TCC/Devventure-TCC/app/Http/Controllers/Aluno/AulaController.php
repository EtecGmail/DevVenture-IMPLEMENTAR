<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aula;
use Illuminate\Support\Facades\Auth;
use App\Models\Aluno;
use App\Models\Turma;
class AulaController extends Controller
{
public function aula(Aula $aula)
{
    
    $aula->load('turma.professor', 'formulario.perguntas.opcoes');


    $videoId = null;
    if ($aula->video_url) {
        $regex = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        if (preg_match($regex, $aula->video_url, $match)) {
            $videoId = $match[1];
        }
    }

    Auth::guard('aluno')->user()->aulas()->syncWithoutDetaching($aula->id);

    
    return view('Aluno.verAulas', [
        'aula' => $aula,
        'videoId' => $videoId
    ]);
}

public function salvarProgresso(Request $request)
{
    $aluno = Auth::guard('aluno')->user();

   
    $aluno->aulas()->syncWithoutDetaching([
        $request->aula_id => [
            'segundos_assistidos' => $request->segundos_assistidos
        ]
    ]);

    return response()->json(['status' => 'sucesso']);
}

}
