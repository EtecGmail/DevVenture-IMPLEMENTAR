<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// --- Importe seus Controllers ---
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\AlunoLoginController;
use App\Http\Controllers\Auth\ProfessorLoginController;
use App\Http\Controllers\Auth\AdmLoginController;
use App\Http\Controllers\Aluno\PerfilController as AlunoPerfilController;
use App\Http\Controllers\Professor\PerfilController as ProfessorPerfilController;
use App\Http\Controllers\Aluno\RespostaController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Aluno\ExercicioAlunoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PÁGINAS PÚBLICAS E FORMULÁRIOS DE LOGIN ---

Route::get('/', function () {
    return view('welcome');
});

Route::get('loginAluno', function () { return view('Aluno.login'); })->name('login.aluno');
Route::get('loginProfessor', function () { return view('Professor.login'); })->name('login.professor');
Route::get('/loginAdm', function () { return view('Adm.login'); })->name('login.admin');


// --- FLUXO DE AUTENTICAÇÃO, CADASTRO, 2FA E REDEFINIÇÃO DE SENHA ---

// CADASTRO
Route::post('/cadastro-aluno', [AlunoPerfilController::class, 'store'])->name('aluno.cadastrar');
Route::post('/cadastrar-prof', [ProfessorPerfilController::class, 'store'])->name('professor.cadastro.action');

// LOGIN
Route::post('/login-aluno', [AlunoLoginController::class, 'verifyUser'])->name('aluno.login');
Route::post('/login-professor', [ProfessorLoginController::class, 'verifyUser'])->name('professor.login.action');
Route::post('/login-adm', [AdmLoginController::class, 'verifyUser']);

// VERIFICAÇÃO DE DUAS ETAPAS (2FA) APÓS O LOGIN
// --- URL ALTERADA para evitar conflito ---
Route::get('/login/verificar-2fa', [TwoFactorController::class, 'showVerifyForm'])->name('2fa.verify.form');
Route::post('/login/verificar-2fa', [TwoFactorController::class, 'verifyCode'])->name('2fa.verify.code');

// ESQUECEU A SENHA
// Rota para exibir o formulário de "Esqueceu a Senha"
Route::get('/esqueceu-senha', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Rota para enviar o e-mail com o código
Route::post('/esqueceu-senha', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Rota para o CÓDIGO de redefinição de senha
// --- URL ALTERADA para evitar conflito ---
Route::get('/redefinir-senha/verificar-codigo', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify.form');
Route::post('/redefinir-senha/verificar-codigo', [ForgotPasswordController::class, 'verifyCode'])->name('password.verify.code');

// Rota para MOSTRAR a tela final de redefinição de senha
Route::get('/redefinir-senha/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');

// Rota para PROCESSAR e salvar a nova senha
Route::post('/redefinir-senha', [ResetPasswordController::class, 'reset'])->name('password.update');



// VERIFICAÇÃO DE E-MAIL APÓS CADASTRO
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    // Redireciona para o dashboard correto após a verificação
    if (Auth::user() instanceof \App\Models\Professor) {
        return redirect()->route('professorDashboard');
    }
    return redirect()->route('aluno.dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Um novo link de verificação foi enviado!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// --- ROTAS PROTEGIDAS ---

// ROTAS DO ALUNO
Route::middleware(['auth:aluno'])->group(function () {
    Route::get('/alunoDashboard', \App\Http\Controllers\Aluno\DashboardController::class)->name('aluno.dashboard');
    Route::post('/logout-aluno', [AlunoLoginController::class, 'logoutUser'])->name('aluno.logout');
    Route::get('/minhas-turmas', [App\Http\Controllers\Aluno\TurmaController::class, 'minhasTurmas'])->name('aluno.turma');
    Route::get('/turmaAluno/{turma}', [App\Http\Controllers\Aluno\TurmaController::class, 'mostrarTurmaEspecifica'])->name('turmas.especifica');
    Route::get('/aula/{aula}', [App\Http\Controllers\Aluno\AulaController::class, 'aula'])->name('aulas.view');
    Route::post('/aula/progresso', [App\Http\Controllers\Aluno\AulaController::class, 'salvarProgresso'])->name('aulas.progresso');
    Route::post('/convite/{convite}/aceitar', [App\Http\Controllers\Aluno\ConviteController::class, 'aceitar'])->name('convites.aceitar');
    Route::post('/convite/{convite}/recusar', [App\Http\Controllers\Aluno\ConviteController::class, 'recusar'])->name('convites.recusar');
    Route::get('/aluno/perfil', [AlunoPerfilController::class, 'edit'])->name('aluno.perfil.edit');
    Route::patch('/aluno/perfil', [AlunoPerfilController::class, 'update'])->name('aluno.perfil.update');
    Route::post('/aulas/{aula}/formulario/responder', [RespostaController::class, 'store'])
        ->name('aluno.formulario.responder');
     Route::get('/aluno/exercicios/{exercicio}', [ExercicioAlunoController::class, 'mostrar'])
         ->name('aluno.exercicios.mostrar');
    Route::post('/aluno/exercicios/{exercicio}/responder', [ExercicioAlunoController::class, 'responder'])
         ->name('aluno.exercicios.responder');
    Route::get('/turma/{turma}/ranking', [App\Http\Controllers\Aluno\TurmaController::class, 'mostrarRanking'])->name('aluno.turma.ranking');
});


// ROTAS DO PROFESSOR
Route::middleware(['auth:professor'])->group(function () {
    Route::get('/professorDashboard', [App\Http\Controllers\Professor\DashboardController::class, 'dashboard'])->name('professorDashboard');
    Route::post('/logout-professor', [ProfessorLoginController::class, 'logoutUser'])->name('professor.logout');
    Route::get('/perfilProfessor', [ProfessorPerfilController::class, 'edit'])->name('professor.perfil.edit');
    Route::patch('/perfilProfessorUpdate', [ProfessorPerfilController::class, 'update'])->name('professor.perfil.update');
    Route::get('/professorGerenciar', [App\Http\Controllers\Professor\TurmaController::class, 'GerenciarTurma'])->name('professor.turmas');
    Route::get('/professorGerenciarEspecifica', [App\Http\Controllers\Professor\TurmaController::class, 'turmaEspecifica'])->name('professor.turma.especifica');
    Route::post('/cadastrar-turma', [App\Http\Controllers\Professor\TurmaController::class, 'turma'])->name('professor.turmas.store');
    Route::get('/turmas/{turma}', [App\Http\Controllers\Professor\TurmaController::class, 'turmaEspecificaID'])->name('turmas.especificaID');
    Route::post('/turmas/{turma}/convidar', [App\Http\Controllers\Professor\TurmaController::class, 'convidarAluno'])->name('turmas.convidar');
    Route::post('/turmas/{turma}/aulas', [App\Http\Controllers\Professor\TurmaController::class, 'formsAula'])->name('turmas.aulas.formsAula');
    Route::get('/professorExercicios', [App\Http\Controllers\Professor\ExercicioController::class, 'exercicios'])->name('professor.exercicios.index');
    Route::post('/professorCriarExercicios', [App\Http\Controllers\Professor\ExercicioController::class, 'CriarExercicios'])->name('professor.exercicios.store');
    Route::get('/professor/aulas/{aula}/formulario/create', [App\Http\Controllers\Professor\FormularioController::class, 'create'])->name('formularios.create');
    Route::post('/professor/aulas/{aula}/formulario', [App\Http\Controllers\Professor\FormularioController::class, 'store'])->name('formularios.store');
    Route::get('/professor/turmas/{turma}/ranking', [App\Http\Controllers\Professor\TurmaController::class, 'mostrarRanking'])
     ->name('professor.turma.ranking');
});


// ROTAS DO ADMIN
Route::middleware('auth:admin')->group(function () {
    Route::get('/admDashboard', [App\Http\Controllers\Adm\DashboardController::class, 'admDashboard'])->name('admin.dashboard');

    // Rotas para gerenciar Alunos
    Route::post('/admin/alunos/{aluno}/block', [App\Http\Controllers\Adm\DashboardController::class, 'blockAluno'])->name('admin.alunos.block');
    Route::post('/admin/alunos/{aluno}/unblock', [App\Http\Controllers\Adm\DashboardController::class, 'unblockAluno'])->name('admin.alunos.unblock');

    // Rotas para gerenciar Professores
    Route::post('/admin/professores/{professor}/block', [App\Http\Controllers\Adm\DashboardController::class, 'blockProfessor'])->name('admin.professores.block');
    Route::post('/admin/professores/{professor}/unblock', [App\Http\Controllers\Adm\DashboardController::class, 'unblockProfessor'])->name('admin.professores.unblock');

    Route::post('/logout-adm', [AdmLoginController::class, 'logoutUser'])->name('admin.logout');
});