<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Enviar Aviso</title>
    {{-- Adapte o caminho do seu CSS --}}
    <link href="{{ asset('css/Professor/avisos.css') }}" rel="stylesheet"> 
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

@include('layouts.navbar')

<main>
    <section class="form-container">
        
        <a href="{{ url()->previous() }}" class="btn-voltar">
            <i class='bx bx-chevron-left'></i>
            Voltar
        </a>
        <h1>Enviar Novo Aviso</h1>
        <p>Escreva a mensagem e selecione para quais turmas deseja enviar.</p>

        <form action="{{ route('professor.avisos.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" placeholder="Ex: Lembrete sobre a prova" required value="{{ old('titulo') }}">
                @error('titulo') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="conteudo">Conteúdo do Aviso</label>
                <textarea id="conteudo" name="conteudo" rows="8" placeholder="Digite a mensagem completa aqui..." required>{{ old('conteudo') }}</textarea>
                @error('conteudo') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <h3>Enviar para as turmas:</h3>
                <div class="turmas-checkbox-grid">
                    @forelse ($turmas as $turma)
                        <div class="checkbox-item">
                            <input type="checkbox" id="turma_{{ $turma->id }}" name="turmas[]" value="{{ $turma->id }}">
                            <label for="turma_{{ $turma->id }}">{{ $turma->nome_turma }}</label>
                        </div>
                    @empty
                        <p>Você não possui turmas para enviar avisos.</p>
                    @endforelse
                </div>
                @error('turmas') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-enviar">Enviar Aviso</button>

        </form>
    </section>
</main>

@include('layouts.footer')

</body>
</html>