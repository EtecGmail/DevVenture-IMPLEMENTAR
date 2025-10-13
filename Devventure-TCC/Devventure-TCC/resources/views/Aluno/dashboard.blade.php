<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Aluno</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/Aluno/alunoDashboard.css') }}" rel="stylesheet">
</head>
<body>

@include('layouts.navbar')

{{-- Usei sua classe original 'page-aluno-dashboard' para manter a compatibilidade --}}
<main class="page-aluno-dashboard">
    <div class="container">

        <div class="page-header">
            <div class="header-text">
                <h1>Painel do Aluno</h1>
                <p>Olá, {{ Auth::guard('aluno')->user()->nome }}! Continue seus estudos.</p>
            </div>
            <a href="{{ route('aluno.turma') }}" class="btn-primary">
                <i class='bx bxs-group'></i> Acessar Minhas Turmas
            </a>
        </div>

        @if($convites->isNotEmpty())
        <div class="card card-convites-destaque">
            <h3>🔔 Você tem novos convites!</h3>
            <div class="convites-container">
                @foreach ($convites as $convite)
                    <div class="convite-item">
                        <div class="convite-info">
                            <p>O professor(a) <strong>{{ $convite->turma->professor->nome }}</strong> convidou você para a turma:</p>
                            <span><i class='bx bxs-chalkboard'></i> {{ $convite->turma->nome_turma }}</span>
                        </div>
                        <div class="convite-actions">
                            <form action="{{ route('convites.aceitar', $convite) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-aceitar"><i class='bx bx-check'></i> Aceitar</button>
                            </form>
                            <form action="{{ route('convites.recusar', $convite) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-recusar"><i class='bx bx-x'></i> Recusar</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="dashboard-grid">
            
            <div class="coluna-principal">
                <div class="card">
                    <h3><i class='bx bx-bar-chart-alt-2'></i> Seu Progresso nas Aulas</h3>
                    <p class="modulo-title">Progresso total de vídeos assistidos</p>
                    <div class="progress-bar-container">
                        <div class="progress-fill" style="width: {{ $progressoPercentual }}%;">{{ $progressoPercentual }}%</div>
                    </div>
                    <p class="progresso-detalhes">Continue assistindo às aulas para avançar.</p>
                </div>

                <div class="card">
                    <h3><i class='bx bx-alarm-exclamation'></i> Próximas Entregas</h3>
                    <div class="deadlines-list">
                        @forelse($proximosExercicios as $exercicio)
                            @php
                                $entregue = $exercicio->respostas->isNotEmpty();
                            @endphp
                            <a href="{{ route('aluno.exercicios.mostrar', $exercicio->id) }}" class="deadline-item {{ $entregue ? 'delivered' : 'pending' }}">
                                <div class="status-icon">
                                    @if($entregue)
                                        <i class='bx bxs-check-circle'></i>
                                    @else
                                        <i class='bx bxs-time-five'></i>
                                    @endif
                                </div>
                                <div class="deadline-info">
                                    <strong>{{ $exercicio->nome }}</strong>
                                    <small>Turma: {{ $exercicio->turma->nome_turma }}</small>
                                </div>
                                <div class="deadline-date {{ $exercicio->data_fechamento->isToday() || $exercicio->data_fechamento->isTomorrow() ? 'urgent' : '' }}">
                                    <span>{{ $exercicio->data_fechamento->setTimezone('America/Sao_Paulo')->format('d/m/Y') }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="empty-state">
                                <i class='bx bx-check-double'></i>
                                <p>Nenhum exercício com prazo futuro. Bom trabalho!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

           <div class="coluna-lateral">

    <div class="card">
        <h3><i class='bx bxs-star'></i> Meus Pontos</h3>
        <div class="my-points-display">
            <span>{{ Auth::guard('aluno')->user()->total_pontos }}</span>
            <small>pontos totais</small>
        </div>
        <p class="my-points-info">Continue completando aulas e exercícios para subir no ranking!</p>
    </div>

    <div class="card card-minhas-turmas">
        <h3><i class='bx bxs-chalkboard'></i> Minhas Turmas</h3>
        <div class="lista-turmas-dashboard">
            @forelse($minhasTurmas as $turma)
                <div class="turma-item-wrapper">
                    <a href="{{ route('turmas.especifica', $turma) }}" class="turma-item-dashboard">
                        <div class="turma-info">
                            <strong>{{ $turma->nome_turma }}</strong>
                            <small>Professor(a): {{ $turma->professor->nome }}</small>
                        </div>
                        <i class='bx bx-chevron-right'></i>
                    </a>
                    <a href="{{ route('aluno.turma.ranking', $turma) }}" class="turma-ranking-link">
                        <i class='bx bx-bar-chart-alt-2'></i> Ver Ranking
                    </a>
                </div>
            @empty
                <p class="empty-message">Você ainda não está matriculado em nenhuma turma.</p>
            @endforelse
        </div>
    </div>

</div>

        </div>
    </div>
</main>

@include('layouts.footer')

{{-- Script para SweetAlert --}}
@if (session('sweet_success'))
    <script>
        Swal.fire({
            title: 'Sucesso!',
            text: "{{ session('sweet_success') }}",
            icon: 'success',
            confirmButtonColor: '#0d3c70', // Usando sua cor primária
            confirmButtonText: 'Ok'
        });
    </script>
@endif

</body>
</html>