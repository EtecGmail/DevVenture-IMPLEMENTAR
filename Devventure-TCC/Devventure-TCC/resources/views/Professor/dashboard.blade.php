<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Painel do Professor</title>
    <link href="{{ asset('css/Professor/professorDashboard.css') }}" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

@include('layouts.navbar')

<main class="page-professor-dashboard">
    <div class="container">
        
        <header class="page-header">
            <div class="header-content">
                <h1>Painel do Professor</h1>
                <p>Olá, {{ Auth::guard('professor')->user()->nome }}! Gerencie suas turmas e aulas.</p>
            </div>
        </header>

        <section class="acoes-rapidas">
            <a href="{{ route('professor.turmas') }}" class="card-acao">
                <i class='bx bxs-group'></i>
                <h3>Gerenciar Turmas</h3>
                <p>Crie, edite e adicione alunos às suas turmas.</p>
            </a>
            <a href="{{ route('professor.exercicios.index') }}" class="card-acao">
                <i class='bx bxs-spreadsheet'></i>
                <h3>Criar Exercício</h3>
                <p>Elabore e atribua novos exercícios.</p>
            </a>
            <a href="{{ route('professor.avisos.create') }}" class="card-acao">
                <i class='bx bxs-bell'></i>
                <h3>Enviar Aviso</h3>
                <p>Mande comunicados para uma ou mais turmas.</p>
            </a>
            <a href="#" class="card-acao">
                <i class='bx bx-line-chart'></i>
                <h3>Ver Relatórios</h3>
                <p>Acompanhe o desempenho dos alunos.</p>
            </a>
        </section>

         </section> @if($convitesPendentes > 0)
            <div class="card-notificacao">
                <i class='bx bxs-bell-ring'></i>
                <div class="notificacao-content">
                    <strong>Atenção:</strong> Você tem <strong>{{ $convitesPendentes }} {{ $convitesPendentes == 1 ? 'convite pendente' : 'convites pendentes' }}</strong> aguardando a resposta dos alunos.
                    <a href="#">Acompanhar convites</a>
                </div>
            </div>
        @endif

        <div class="content-grid">

            <div class="coluna-principal">
                <div class="card">
                    <h2><i class='bx bx-list-ul'></i> Suas Turmas Recentes</h2>
                    <div class="lista-turmas">
                        @forelse($turmasRecentes as $turma)
                            <div class="item-turma">
                                <div class="info-turma">
                                    <strong>{{ $turma->nome_turma }}</strong>
                                    <small>{{ $turma->alunos_count }} {{ $turma->alunos_count == 1 ? 'aluno' : 'alunos' }}</small>
                                </div>
                                <a href="{{ route('professor.turma.especifica', $turma) }}" class="btn-gerenciar">Gerenciar</a>
                            </div>
                        @empty
                            <p class="empty-message">Você ainda não criou nenhuma turma.</p>
                        @endforelse
                    </div>
                    <a href="{{ route('professor.turmas') }}" class="link-ver-todas">Ver todas as turmas <i class='bx bx-right-arrow-alt'></i></a>
                </div>
                
                <div class="card">
                    <h2><i class='bx bxs-videos'></i> Últimas Aulas Adicionadas</h2>
                    <div class="lista-aulas">
                        @forelse($aulasRecentes as $aula)
                            <div class="item-aula">
                                <i class='bx bx-play-circle'></i>
                                <div class="info-aula">
                                    <strong>{{ $aula->titulo }}</strong>
                                    <small>Turma: {{ $aula->turma->nome_turma }}</small>
                                </div>
                                <span class="data-aula">{{ $aula->created_at->format('d/m/Y') }}</span>
                            </div>
                        @empty
                            <p class="empty-message">Nenhuma aula foi criada recentemente.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="coluna-lateral">
                <div class="card card-estatisticas">
                    <h2><i class='bx bx-bar-chart-square'></i> Estatísticas Gerais</h2>
                    <div class="item-estatistica">
                        <span class="numero">{{ $totalAlunos }}</span>
                        <span class="descricao">Total de Alunos</span>
                    </div>
                    <div class="item-estatistica">
                        <span class="numero">{{ $totalAulas }}</span>
                        <span class="descricao">Aulas Criadas</span>
                    </div>
                    <div class="item-estatistica">
                        <span class="numero">89%</span>
                        <span class="descricao">Taxa de Conclusão (Exemplo)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('layouts.footer')

@if (session('sweet_success'))
    <script>
        Swal.fire({
            title: "Sucesso!",
            text: "{{ session('sweet_success') }}",
            icon: "success",
            confirmButtonText: "Ok"
        });
    </script>
@endif

<script src="{{ asset('js/Professor/professorDashboard.js') }}"></script>
</body>
</html>