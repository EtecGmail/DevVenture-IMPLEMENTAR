<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $aula->titulo }}</title>
    <link href="{{ asset('css/Aluno/verAulaAluno.css') }}" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<main class="page-ver-aula">
    <div class="container">
        <a href="{{ route('turmas.especifica', $aula->turma) }}" class="btn-voltar-aula">
            <i class='bx bx-arrow-back'></i> Voltar para a Turma
        </a>

        <header class="aula-header">
            <h1>{{ $aula->titulo }}</h1>
            <div class="aula-meta-info"> 
                <p class="turma-info"><i class='bx bxs-chalkboard'></i> Turma: {{ $aula->turma->nome_turma }}</p>
                <p class="professor-info"><i class='bx bxs-user-badge'></i> Professor(a): {{ $aula->turma->professor->nome }}</p>
            </div>
        </header>

        
        <div id="video-wrapper" 
             data-video-id="{{ $videoId }}" 
             data-aula-id="{{ $aula->id }}" 
             data-progress-url="{{ route('aulas.progresso') }}">

            <div class="video-container">
                @if($videoId)
                    <div id="player-iframe-id"></div>
                @else
                    <p class="video-invalido">Link de vídeo inválido ou não encontrado.</p>
                @endif
            </div>
        </div>
        
        <div class="status-video" id="status-video">
            <p><i class='bx bx-info-circle'></i> Assista ao vídeo até o final para validar sua presença.</p>
        </div>

      <div id="quiz-container" class="quiz-container" style="display: none;">
    

    @if ($aula->formulario && $aula->formulario->perguntas->isNotEmpty())

        <div class="card-formulario">
            <h3>📝 Valide sua Aula</h3>
            <p>Responda às perguntas abaixo para validar sua presença e aprendizado nesta aula.</p>

            @php
                $jaRespondeu = \App\Models\Resposta::where('aluno_id', auth('aluno')->id())
                                    ->whereIn('pergunta_id', $aula->formulario->perguntas->pluck('id'))
                                    ->exists();
            @endphp

            @if ($jaRespondeu)
                <div class="alert-success">
                    <i class='bx bx-check-circle'></i>
                    Você já respondeu a este formulário. Bom trabalho!
                </div>
            @else
                <form action="{{ route('aluno.formulario.responder', $aula) }}" method="POST">
                    @csrf
                    
                    @foreach ($aula->formulario->perguntas as $pergunta)
                        <div class="form-group-quiz" style="margin-top: 20px;">
                            <p class="pergunta-texto" style="font-weight: bold; margin-bottom: 10px;">{{ $loop->iteration }}. {{ $pergunta->texto_pergunta }}</p>
                            
                            <div class="opcoes-container">
                                @foreach ($pergunta->opcoes as $opcao)
                                    <div class="opcao-radio" style="margin-bottom: 5px;">
                                        <input 
                                            type="radio" 
                                            name="respostas[{{ $pergunta->id }}]" 
                                            id="opcao-{{ $opcao->id }}" 
                                            value="{{ $opcao->id }}"
                                            required>
                                        <label for="opcao-{{ $opcao->id }}" style="margin-left: 5px;">{{ $opcao->texto_opcao }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <button type="submit" class="btn-enviar-respostas" style="margin-top: 25px;">Enviar Respostas</button>
                </form>
            @endif
        </div>
        
    @endif

</div>  
 </div> 
</main>

{{-- ======================== SCRIPTS ========================= --}}
<script src="https://www.youtube.com/iframe_api"></script>
<script src="{{ asset('js/Aluno/verAulas.js') }}"></script>

</body>
</html>
