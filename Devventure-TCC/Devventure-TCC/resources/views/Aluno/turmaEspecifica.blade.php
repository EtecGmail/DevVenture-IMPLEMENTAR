<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $turma->nome_turma }}</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('css/Aluno/alunoTurmaEspecifica.css') }}" rel="stylesheet">
</head>
<body>
    <div class="turma-wrapper">
        <header class="turma-header">
            <div class="header-overlay"></div>
            <div class="header-content">
                <a href="{{ route('aluno.turma') }}" class="back-link"><i class='bx bx-chevron-left'></i> Voltar</a>
                <div class="header-info">
                    <h1>{{ $turma->nome_turma }}</h1>
                    <p>Professor(a): {{ $turma->professor->nome }}</p>
                </div>
                <div class="header-stats">
                    <div class="stat-item"><i class='bx bxs-group'></i><span>{{ $alunos->count() }} Alunos</span></div>
                    <div class="stat-item"><i class='bx bxs-book-content'></i><span>{{ $exercicios->count() }} Exercícios</span></div>
                    <div class="stat-item"><i class='bx bxs-videos'></i><span>{{ $aulas->count() }} Aulas</span></div>
                </div>
            </div>
        </header>

        <main class="page-body">
            <div class="main-content">
                <div class="tabs-navigation">
                    <button class="tab-link active" data-tab="exercicios"><i class='bx bxs-pencil'></i> Exercícios</button>
                    <button class="tab-link" data-tab="aulas"><i class='bx bxs-videos'></i> Aulas</button>
                    <button class="tab-link" data-tab="avisos"><i class='bx bxs-bell'></i> Mural de Avisos</button>
                </div>

                <div class="tabs-content">
                    <div class="tab-pane active" id="exercicios">
                        <div class="content-grid">
                            @forelse($exercicios as $exercicio)
                                @php
                                    $statusClass = 'status-pending';
                                    $statusText = 'Pendente';
                                    if ($exercicio->respostas->isNotEmpty()) {
                                        $statusClass = 'status-delivered';
                                        $statusText = 'Concluído';
                                    } elseif (now()->isAfter($exercicio->data_fechamento)) {
                                        $statusClass = 'status-late';
                                        $statusText = 'Prazo Encerrado';
                                    }
                                @endphp
                                <a href="{{ route('aluno.exercicios.mostrar', $exercicio->id) }}" class="exercise-card {{ $statusClass }}">
                                    <div class="card-content">
                                        <div class="card-header">
                                            <h3>{{ $exercicio->nome }}</h3>
                                            <span class="status-tag">{{ $statusText }}</span>
                                        </div>
                                        <p class="card-description">{{ Str::limit($exercicio->descricao, 100) }}</p>
                                        <div class="card-footer">
                                            <div class="deadline-info">
                                                <i class='bx bxs-time-five'></i>
                                                <span>Entregar até: {{ \Carbon\Carbon::parse($exercicio->data_fechamento)->format('d/m/Y') }}</span>
                                            </div>
                                            <i class='bx bx-right-arrow-alt card-arrow'></i>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="empty-state">
                                    <i class='bx bx-info-circle'></i>
                                    <p>Nenhum exercício postado nesta turma ainda.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="tab-pane" id="aulas">
                        <div class="content-grid">
                            @forelse($aulas as $aula)
                                <a href="{{ route('aulas.view', $aula) }}" class="lesson-card">
                                    <div class="card-content">
                                        <div class="lesson-icon">
                                            <i class='bx bxs-movie-play'></i>
                                        </div>
                                        <div class="lesson-info">
                                            <h3>{{ $aula->titulo }}</h3>
                                            <p>Clique para assistir à aula</p>
                                        </div>
                                        <i class='bx bx-right-arrow-alt card-arrow'></i>
                                    </div>
                                </a>
                            @empty
                                 <div class="empty-state">
                                    <i class='bx bx-info-circle'></i>
                                    <p>Nenhuma aula disponível.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="avisos">
        <div class="avisos-list">
            @forelse($turma->avisos as $aviso)
                <div class="card-aviso">
                    <div class="card-aviso-header">
                        <h3>{{ $aviso->titulo }}</h3>
                        <span class="data-aviso">
                            {{ $aviso->created_at->diffForHumans() }} </span>
                    </div>
                    <div class="card-aviso-body">
                        {{-- Usamos nl2br para preservar as quebras de linha do textarea --}}
                        <p>{!! nl2br(e($aviso->conteudo)) !!}</p>
                    </div>
                    <div class="card-aviso-footer">
                        <span>Enviado por: {{ $aviso->professor->nome }}</span>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class='bx bx-info-circle'></i>
                    <p>Nenhum aviso postado nesta turma ainda.</p>
                </div>
            @endforelse
        </div>
    </div>
    
            </div>

            <aside class="sidebar">
                
                <div class="card ranking-card">
                    <a href="{{ route('aluno.turma.ranking', $turma) }}" class="btn-ranking">
                        <i class='bx bxs-bar-chart-alt-2'></i>
                        <span>Ver Ranking da Turma</span>
                    </a>
                </div>
                
                <div class="card">
                    <div class="card-section">
                        <h2><i class='bx bxs-group'></i> Colegas de Turma</h2>
                        <ul class="classmates-list">
                            @forelse($alunos as $aluno)
                                <li>
                                    <img src="{{ $aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/40?u='.$aluno->id }}" alt="Avatar" class="avatar">
                                    <span>{{ $aluno->nome }}</span>
                                </li>
                            @empty
                                <li class="empty-message">Nenhum outro aluno na turma.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </aside>
        </main>
    </div>
    
    <script>
        // Lógica para alternar entre as abas (Exercícios e Aulas)
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabLinks.forEach(link => {
            link.addEventListener('click', () => {
                const tab = link.getAttribute('data-tab');

                tabLinks.forEach(item => item.classList.remove('active'));
                tabPanes.forEach(item => item.classList.remove('active'));

                link.classList.add('active');
                document.getElementById(tab).classList.add('active');
            });
        });
    </script>

    @if (session('sweet_success'))
    <script>
        Swal.fire({
            title: 'Parabéns!',
            text: "{{ session('sweet_success') }}",
            icon: 'success',
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'Ótimo!'
        });
    </script>
    @endif
</body>
</html>