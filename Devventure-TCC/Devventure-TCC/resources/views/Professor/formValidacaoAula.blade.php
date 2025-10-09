<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Construir Formulário - {{ $aula->titulo }}</title>
    
   
    <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="{{ asset('css/Professor/formValidacao.css') }}" rel="stylesheet">
</head>

<body>
    <main class="container">
        <header>
            <h1>Etapa 2: Construir Formulário de Múltipla Escolha</h1>
            <p class="intro-text">Para a Aula: "{{ $aula->titulo }}"</p>
        </header>

        <form action="{{ route('formularios.store', $aula) }}" method="POST" id="quiz-form">
            @csrf
            
            <div class="card form-builder-card">
                <div class="form-group mb-4">
                    <label for="titulo" class="h5">Título do Formulário</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Ex: Exercício de Fixação sobre Funções" required value="{{ old('titulo') }}">
                </div>
                <hr>

                <div id="perguntas-container">
                    
                    <div class="pergunta-item mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="pergunta-titulo"></label> 
                        </div>

                       
                        <div class="input-wrapper mb-3">
                            <i class='bx bxs-help-circle input-icon'></i>
                            <input type="text" name="perguntas[0][texto]" class="form-control" placeholder="Digite o texto da pergunta aqui" required>
                        </div>
                        
                        <h6>Opções de Resposta (Marque a correta)</h6>
                        
                        <div class="opcoes-container">
                          
                            <div class="input-group mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="perguntas[0][correta]" value="0" id="option_0_0" required>
                                    <label class="form-check-label" for="option_0_0"></label>
                                </div>
                                <input type="text" name="perguntas[0][opcoes][]" class="form-control" placeholder="Texto da opção" required>
                                <button type="button" class="btn remove-opcao-btn" title="Remover Opção"><i class='bx bx-x'></i></button>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-secondary btn-sm add-opcao-btn mt-2">
                            <i class='bx bx-plus'></i> Adicionar Opção
                        </button>
                    </div>
                </div>

                <button type="button" id="add-pergunta-btn" class="btn mt-3">
                    <i class='bx bx-plus'></i> Adicionar Nova Pergunta
                </button>
            </div>

            <div class="text-end mt-4"> 
                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-check-double'></i> Salvar Formulário Completo
                </button>
            </div>
        </form>
    </main>
    
    {{-- Garanta que o JS também está atualizado --}}
    <script src="{{ asset('js/Professor/formValidacaoAula.js') }}"></script>
</body>
</html>