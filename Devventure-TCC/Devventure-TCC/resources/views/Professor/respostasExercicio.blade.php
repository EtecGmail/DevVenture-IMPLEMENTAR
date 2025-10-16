<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corrigir Exercício: {{ $exercicio->nome }}</title>

    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    
    <link href="{{ asset('css/Professor/respostasExercicio.css') }}" rel="stylesheet">
</head>
<body>

    @include('layouts.navbar')

    <main class="correcao-wrapper">
        <header class="page-header">
            <a href="{{ route('professor.exercicios.index') }}" class="back-link"><i class='bx bx-chevron-left'></i> Voltar para Exercícios</a>
            <h1>Respostas para: <strong>{{ $exercicio->nome }}</strong></h1>
            <p>Turma: {{ $exercicio->turma->nome_turma }}</p>
        </header>

        <div class="respostas-grid">
            @forelse ($exercicio->respostas as $resposta)
                <div class="card-aluno">
                    <!-- Informações do Aluno -->
                    <div class="aluno-info">
                        <img src="{{ $resposta->aluno->avatar ? asset('storage/' . $resposta->aluno->avatar) : 'https://i.pravatar.cc/50?u='.$resposta->aluno->id }}" alt="Avatar" class="avatar">
                        <div class="aluno-details">
                            <h4>{{ $resposta->aluno->nome }}</h4>
                            <small>Enviado em: {{ $resposta->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y \à\s H:i') }}</small>
                        </div>
                    </div>

                    <!-- Ficheiros Enviados para Baixar -->
                    <div class="arquivos-enviados">
                        <h5>Arquivo de Resposta:</h5>
                        @forelse($resposta->arquivos as $arquivo)
                            <a href="{{ asset('storage/' . $arquivo->arquivo_path) }}" target="_blank" class="arquivo-link" download="{{ $arquivo->nome_original ?? basename($arquivo->arquivo_path) }}">
                                <i class='bx bxs-download'></i> {{ $arquivo->nome_original ?? basename($arquivo->arquivo_path) }}
                            </a>
                        @empty
                            <p class="empty-message">Nenhum ficheiro foi enviado.</p>
                        @endforelse
                    </div>

                    <!-- Formulário de Avaliação -->
                    <form action="{{ route('professor.respostas.avaliar', $resposta) }}" method="POST" class="form-avaliacao">
                        @csrf
                        <h5>Sua Avaliação</h5>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="conceito-{{ $resposta->id }}">Conceito</label>
                                <select name="conceito" id="conceito-{{ $resposta->id }}" required>
                                    <option value="" disabled {{ !$resposta->conceito ? 'selected' : '' }}>Selecione</option>
                                    <option value="MB" {{ $resposta->conceito == 'MB' ? 'selected' : '' }}>MB (Muito Bom)</option>
                                    <option value="B" {{ $resposta->conceito == 'B' ? 'selected' : '' }}>B (Bom)</option>
                                    <option value="R" {{ $resposta->conceito == 'R' ? 'selected' : '' }}>R (Regular)</option>
                                    <option value="I" {{ $resposta->conceito == 'I' ? 'selected' : '' }}>I (Insatisfatório)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nota-{{ $resposta->id }}">Nota (0-100)</label>
                                <input type="number" name="nota" id="nota-{{ $resposta->id }}" value="{{ $resposta->nota }}" placeholder="Ex: 85" min="0" max="100" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="feedback-{{ $resposta->id }}">Feedback (Opcional)</label>
                            <textarea name="feedback" id="feedback-{{ $resposta->id }}" rows="2" placeholder="Deixe um comentário para o aluno...">{{ $resposta->feedback }}</textarea>
                        </div>
                        <button type="submit" class="btn-salvar-avaliacao">
                            <i class='bx bx-check'></i> Guardar Avaliação
                        </button>
                    </form>
                </div>
            @empty
                <div class="card-aluno empty-state">
                    <i class='bx bx-info-circle'></i>
                    <p>Nenhum aluno enviou uma resposta para este exercício ainda.</p>
                </div>
            @endforelse
        </div>
    </main>

    @include('layouts.footer')

    @if (session('sweet_success'))
    <script>
        
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: "{{ session('sweet_success') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    </script>
    @endif
</body>
</html>

