<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Turma: {{ $turma->nome_turma }}</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/Professor/detalheTurma.css') }}" rel="stylesheet">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

    <div class="turma-wrapper">
        <header class="turma-header">
            <div class="header-overlay"></div>
            <div class="header-content">
                <a href="{{ route('professor.turmas') }}" class="back-link"><i class='bx bx-chevron-left'></i> Voltar para Minhas Turmas</a>
                <div class="header-info">
                    <h1>{{ $turma->nome_turma }}</h1>
                    <p>Disciplina: {{ $turma->disciplina ?? 'Não especificada' }} | Turno: {{ ucfirst($turma->turno) }}</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-secondary" id="btnAbrirModalAula"><i class='bx bx-video-plus'></i> Adicionar Aula</button>
                    <button class="btn btn-secondary" id="btnAbrirModalAluno"><i class='bx bx-user-plus'></i> Convidar Aluno</button>
                    <a href="{{ route('professor.turma.ranking', $turma) }}" class="btn btn-primary btn-ranking">
                        <i class='bx bxs-bar-chart-alt-2'></i> Ver Ranking
                    </a>
                </div>
            </div>
        </header>

    <main class="page-body">
    <div class="main-content">
        <div class="card">
            <div class="card-header">
                <h2><i class='bx bxs-group'></i> Alunos Matriculados ({{ $alunos->total() }})</h2>
            </div>
            <ul class="student-list">
                @forelse($alunos as $aluno)
                    <a href="{{ route('professor.relatorios.aluno', ['turma' => $turma, 'aluno' => $aluno]) }}" class="student-item">
                        <div class="student-info">
                            <img src="{{ $aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/40?u='.$aluno->id }}" alt="Avatar" class="avatar">
                            <span>{{ $aluno->nome }}</span>
                        </div>
                        <div class="student-progress">
                            <small>{{ $aluno->progresso_percentual ?? 0 }}%</small>
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: {{ $aluno->progresso_percentual ?? 0 }}%;"></div>
                            </div>
                        </div>
                    </a>
                @empty
                    <li class="empty-message">Nenhum aluno na turma.</li>
                @endforelse
            </ul>

            <div class="pagination">
                {{ $alunos->appends(request()->except('alunosPage'))->links() }}
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class='bx bxs-bell'></i> Mural de Avisos ({{ $avisos->total() }})</h2>
            </div>
            <ul class="avisos-list">
                @forelse ($avisos as $aviso)
                    <li class="aviso-item">
                        <div class="aviso-header">
                            <h3 class="aviso-title">{{ $aviso->titulo }}</h3>
                            <small class="aviso-date">{{ $aviso->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="aviso-content">
                            <p>{!! nl2br(e($aviso->conteudo)) !!}</p>
                        </div>
                    </li>
                @empty
                    <li class="empty-message">Nenhum aviso enviado para esta turma.</li>
                @endforelse
            </ul>
            
            <div class="pagination">
                {{ $avisos->appends(request()->except('avisosPage'))->links() }}
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class='bx bxs-time-five'></i> Histórico da Turma ({{ $historico->total() }})</h2>
            </div>
            <ul class="timeline">
                @forelse ($historico as $item)
                    <li class="timeline-item timeline-item--{{ $item['tipo'] }}">
                        <div class="timeline-marker">
                            <div class="timeline-icon">
                                <i class='bx {{ $item['tipo'] == 'aula' ? 'bxs-videos' : 'bxs-spreadsheet' }}'></i>
                            </div>
                        </div>
                        <div class="timeline-content">
                            <span class="timeline-date">
                                {{ \Carbon\Carbon::parse($item['data'])->setTimezone('America/Sao_Paulo')->format('d/m \à\s H:i') }}
                            </span>
                            <h3 class="timeline-title">{{ $item['titulo'] }}</h3>
                            <p class="timeline-detail">{{ $item['detalhe'] }}</p>
                        </div>
                    </li>
                @empty
                    <li class="empty-message">Nenhuma atividade registrada.</li>
                @endforelse
            </ul>
            
            <div class="pagination">
                {{ $historico->appends(request()->except('historicoPage'))->links() }}
            </div>
        </div>
    </div>

    <aside class="sidebar">
        <div class="card">
            <div class="card-header">
                <h2><i class='bx bxs-spreadsheet'></i> Exercícios ({{ $exercicios->total() }})</h2>
            </div>
            <ul class="content-list">
                @forelse ($exercicios as $exercicio)
                    <li class="content-item">
                        <span>{{ $exercicio->nome }}</span>
                        <small>Até {{ \Carbon\Carbon::parse($exercicio->data_fechamento)->setTimezone('America/Sao_Paulo')->format('d/m/Y') }}</small>
                    </li>
                @empty
                    <li class="empty-message">Nenhum exercício cadastrado.</li>
                @endforelse
            </ul>
            
            <div class="pagination">
                {{ $exercicios->appends(request()->except('exerciciosPage'))->links() }}
            </div>
        </div>
    </aside>
</main>
    </div>

    <div class="modal-overlay" id="modalConvidarAluno">
        <div class="modal-content">
            <form action="{{ route('turmas.convidar', $turma) }}" method="POST">
                @csrf
                <button type="button" class="modal-close"><i class='bx bx-x'></i></button>
                <h2><i class='bx bx-user-plus'></i> Convidar Aluno para a Turma</h2>
                <p>Digite o Registro do Aluno (RA/RM) para enviar um convite para <strong>{{ $turma->nome_turma }}</strong>.</p>
                <div class="form-group com-icone">
                    <i class='bx bx-id-card'></i>
                    <input type="text" name="ra" placeholder="Digite o RA/RM do aluno" required autocomplete="off">
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn-cancelar">Cancelar</button>
                    <button type="submit" class="btn-confirmar">Enviar Convite</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modalAdicionarAula">
        <div class="modal-content">
            <form action="{{ route('turmas.aulas.formsAula', $turma) }}" method="POST">
                @csrf
                <button type="button" class="modal-close"><i class='bx bx-x'></i></button>
                <h2><i class='bx bx-video-plus'></i> Adicionar Nova Aula</h2>
                <p>Preencha os dados abaixo para cadastrar uma nova aula.</p>
                
                <div class="form-group">
                    <label for="titulo">Título da Aula</label>
                    <input type="text" id="titulo" name="titulo" required>
                </div>
                
                <div class="form-group">
                    <label for="video_url">Link do Vídeo (YouTube)</label>
                    <input type="url" id="video_url" name="video_url" required>
                </div>

                <div class="form-group">
                    <label for="duracao_texto">Duração (Ex: 3,44 para 3m e 44s)</label>
                    <input type="text" id="duracao_texto" name="duracao_texto" placeholder="Minutos,Segundos" required>
                </div>

                <div class="form-group">
                    <label for="pontos_aula">Pontos da Aula</label>
                    <input type="number" id="pontos_aula" name="pontos" value="5" required min="0">
                </div>

                <div class="modal-buttons">
                    <button type="button" class="btn-cancelar">Cancelar</button>
                    <button type="submit" class="btn-confirmar">Adicionar Aula</button>
                </div>
            </form>
        </div>
    </div>

    
    <script>
        window.flashMessages = {
            sweetSuccessConvite: "{{ session('sweet_success_convite') }}",
            sweetErrorConvite: "{{ session('sweet_error_convite') }}",
            sweetErrorAula: "{{ session('sweet_error_aula') ?? '' }}"
        };
        @if (session('aula_criada_feedback'))
            const aulaCriadaFeedback = @json(session('aula_criada_feedback'));
        @endif
        @if (session('formulario_criado_success'))
            const formularioCriadoSuccess = @json(session('formulario_criado_success'));
        @endif
    </script>
    <script src="{{ asset('js/Professor/detalheTurmaProfessor.js') }}"></script>
</body>
</html>