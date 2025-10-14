<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Detalhes do Exercício - {{ $exercicio->nome }}</title>

    <!-- Ícones e Alertas -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS Separado -->
    <link href="{{ asset('css/Aluno/exercicioDetalhe.css') }}" rel="stylesheet">
</head>
<body>

    <div class="page-wrapper">
        <header class="page-header">
             <!-- ROTA DO BOTÃO 'VOLTAR' CORRIGIDA -->
            <a href="{{ route('turmas.especifica', $exercicio->turma_id) }}" class="btn btn-secondary">
                <i class='bx bx-chevron-left'></i> Voltar para a Turma
            </a>
        </header>

        <main class="page-content">
            <!-- Coluna da Esquerda: Detalhes do Exercício -->
            <div class="main-content">
                <div class="card">
                    <div class="exercise-header">
                        <h1>{{ $exercicio->nome }}</h1>
                        <div class="deadline">
                            <i class='bx bxs-time-five'></i>
                            <span>Prazo de entrega: {{ \Carbon\Carbon::parse($exercicio->data_fechamento)->setTimezone('America/Sao_Paulo')->format('d/m/Y \à\s H:i') }}</span>
                        </div>
                    </div>

                    <div class="card-section">
                        <h2>Instruções do Professor</h2>
                        <p>{{ $exercicio->descricao ?: 'Nenhuma instrução adicional foi fornecida.' }}</p>
                    </div>

                    <!-- ========================================================== -->
                    <!-- ===== SEÇÃO DE MATERIAIS DE APOIO CORRIGIDA AQUI ===== -->
                    <!-- ========================================================== -->
                    <div class="card-section">
                        <h2>Materiais de Apoio</h2>
                        <div class="materials-list">
                            {{-- Loop para exibir todas as imagens de apoio --}}
                            @foreach ($exercicio->imagensApoio as $imagem)
                                <a href="{{ asset('storage/' . $imagem->imagem_path) }}" target="_blank" class="material-item">
                                    <i class='bx bxs-image-alt'></i>
                                    <span>Ver imagem {{ $loop->count > 1 ? $loop->iteration : '' }}</span>
                                </a>
                            @endforeach

                            {{-- Loop para exibir todos os arquivos de apoio --}}
                            @foreach ($exercicio->arquivosApoio as $arquivo)
                                <a href="{{ asset('storage/' . $arquivo->arquivo_path) }}" target="_blank" class="material-item">
                                    <i class='bx bxs-download'></i>
                                    {{-- Usa o nome original do arquivo --}}
                                    <span>Baixar: {{ $arquivo->nome_original }}</span>
                                </a>
                            @endforeach
                            
                            {{-- Mensagem para quando não há nenhum material --}}
                            @if ($exercicio->imagensApoio->isEmpty() && $exercicio->arquivosApoio->isEmpty())
                                <p class="empty-message">Nenhum material de apoio foi fornecido.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna da Direita: Status e Ações -->
            <aside class="sidebar">
                <div class="card submission-card">
                    <div class="card-section">
                        <h2>Status da Entrega</h2>

                        @if ($respostaAnterior && $respostaAnterior->arquivos->isNotEmpty())
                            <div class="status-badge status-delivered">
                                <i class='bx bxs-check-circle'></i>
                                <span>Entregue</span>
                            </div>
                            <p class="submission-date"><strong>Último envio em:</strong> {{ \Carbon\Carbon::parse($respostaAnterior->data_envio)->setTimezone('America/Sao_Paulo')->format('d/m/Y \à\s H:i') }}</p>
                            <div class="submitted-files">
                                <h4>Arquivos enviados:</h4>
                                <ul>
                                    @foreach ($respostaAnterior->arquivos as $arquivo)
                                        <li>
                                            <a href="{{ asset('storage/' . $arquivo->arquivo_path) }}" target="_blank">
                                                <i class='bx bxs-file-blank'></i> {{ basename($arquivo->arquivo_path) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif (now()->isAfter($exercicio->data_fechamento))
                             <div class="status-badge status-late">
                                <i class='bx bxs-error-circle'></i>
                                <span>Prazo Encerrado</span>
                            </div>
                            <p class="empty-message">O prazo para esta atividade terminou e nenhuma resposta foi enviada.</p>
                        @else
                            <div class="status-badge status-pending">
                                <i class='bx bxs-info-circle'></i>
                                <span>Pendente</span>
                            </div>
                            <p class="empty-message">Você ainda não enviou uma resposta para esta atividade.</p>
                        @endif
                    </div>

                    @if (now()->isBefore($exercicio->data_fechamento))
                        <div class="card-section action-area">
                            <h3>{{ $respostaAnterior ? 'Enviar novamente' : 'Sua Resposta' }}</h3>
                            
                            @if ($errors->any())
                                <div class="error-box">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('aluno.exercicios.responder', $exercicio->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="arquivo_resposta" class="file-drop-area">
                                        <i class='bx bxs-cloud-upload'></i>
                                        <span>Arraste e solte ou clique para enviar</span>
                                        <small>Envie múltiplos arquivos se necessário</small>
                                        <input name="arquivos_resposta[]" type="file" id="arquivo_resposta" class="input-file" required multiple />
                                    </label>
                                    <div id="file-list"></div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-enviar">
                                    <i class='bx bx-paper-plane'></i>
                                    {{ $respostaAnterior ? 'Atualizar Resposta' : 'Enviar Resposta' }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </aside>
        </main>
    </div>

    <!-- SCRIPTS -->
    <script>
        const inputArquivo = document.getElementById('arquivo_resposta');
        const fileListContainer = document.getElementById('file-list');
        if (inputArquivo) {
            inputArquivo.addEventListener('change', function() {
                fileListContainer.innerHTML = '';
                if (this.files.length > 0) {
                    const list = document.createElement('ul');
                    for (const file of this.files) {
                        const listItem = document.createElement('li');
                        listItem.innerHTML = `<i class='bx bxs-file'></i> ${file.name}`;
                        list.appendChild(listItem);
                    }
                    fileListContainer.appendChild(list);
                }
            });
        }
    </script>
    @if (session('sweet_error'))
    <script>
        Swal.fire({
            title: "Atenção!",
            text: "{{ session('sweet_error') }}",
            icon: "error",
            confirmButtonText: "Ok"
        });
    </script>
    @endif
</body>
</html>

