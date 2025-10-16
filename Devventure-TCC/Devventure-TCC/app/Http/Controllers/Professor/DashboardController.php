<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\Aula;
use App\Models\Turma;
use Illuminate\Support\Facades\Auth;
use App\Models\Convite;

class DashboardController extends Controller
{
     public function dashboard()
    {
        $professor = Auth::guard('professor')->user();

        
        $turmas = $professor->turmas()->withCount('alunos')->latest()->get(); 

        $aulasRecentes = Aula::whereIn('turma_id', $professor->turmas()->pluck('id'))
                            ->latest()
                            ->take(3)
                            ->get();

        $totalAlunos = Aluno::whereHas('turmas', function ($query) use ($professor) {
            $query->where('professor_id', $professor->id);
        })->count();
        
        $totalAulas = Aula::whereIn('turma_id', $professor->turmas()->pluck('id'))->count();

        $convitesPendentes = Convite::where('professor_id', $professor->id)
                                    ->where('status', 'pendente')
                                    ->count();

        
        return view('Professor/dashboard', [
            'turmas' => $turmas, 
            'aulasRecentes' => $aulasRecentes,
            'totalAlunos' => $totalAlunos,
            'totalAulas' => $totalAulas,
            'convitesPendentes' => $convitesPendentes,
        ]);
    }

}
