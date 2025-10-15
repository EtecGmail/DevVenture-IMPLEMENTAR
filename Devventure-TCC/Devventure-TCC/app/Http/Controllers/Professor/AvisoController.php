<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Aviso;
use App\Models\Turma; // Importe o model Turma

class AvisoController extends Controller
{
    
    public function create()
    {
        
        $turmas = Auth::user()->turmas;
        return view('professor.avisosCriar', compact('turmas'));
    }

   
    public function store(Request $request)
    {
        // 1. Validação dos dados
        $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'turmas' => 'required|array|min:1', 
            'turmas.*' => 'exists:turmas,id', 
        ]);

        
        $aviso = Aviso::create([
            'professor_id' => Auth::id(),
            'titulo' => $request->titulo,
            'conteudo' => $request->conteudo,
        ]);

        
        $aviso->turmas()->attach($request->turmas);

        
        return redirect()->route('professorDashboard')->with('sweet_success', 'Aviso enviado com sucesso!');
    }
}