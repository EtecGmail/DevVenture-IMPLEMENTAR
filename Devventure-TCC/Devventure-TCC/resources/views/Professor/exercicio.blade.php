<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Exercicios</title>
    
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/Professor/exercicioProfessor.css') }}" rel="stylesheet">
</head>
<body>

    @include('layouts.navbar')

    <main>
        <section class="intro">
            <a href="/professorDashboard" class="btn-voltar">
                <i class='bx bx-chevron-left'></i>
                Voltar
            </a>

            <h1>Exercicios</h1>
            <p>Crie e gerencie Exercicios com total facilidade</p>

            <div class="add-exercicio">
                <button>Adicionar Exercicio</button>
            </div>
        </section>

        <section class="exercicios">
            <div class="exercicios-header">
                <div>
                    <h2>Meus Exercicios</h2>
                    <p>Clique em um exercicio para gerenciá-lo</p>
                    <div class="toggle-buttons">
                        <a href="{{ url('/professorExercicios', ['status' => 'disponiveis']) }}" class="toggle-button {{ $status == 'disponiveis' ? 'ativo' : 'inativo' }}">
                            Disponíveis
                        </a>
                        <a href="{{ url('/professorExercicios', ['status' => 'concluidas']) }}" class="toggle-button {{ $status == 'concluidas' ? 'ativo' : 'inativo' }}">
                            Concluídas
                        </a>
                    </div>
                </div>
                <form action="{{ url('/professorExercicios') }}" method="GET" class="search">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="text" name="search" placeholder="Pesquisar exercício..." value="{{ request('search') }}">
                    <button type="submit"><i class='bx bx-search'></i></button>
                </form>
            </div>

            <div class="exercicios-scroll">
                <div class="exercicios-grid">
                    @forelse ($exercicios as $exercicio)
                        <div class="card">
                            <h3>{{ $exercicio->turma->nome_turma }}</h3>

                            <div class="tags">
                                <span class="tag">{{ ucfirst($exercicio->turma->turno) }}</span>
                                <span class="tag">{{ $exercicio->nome }}</span>
                            </div>
                            
                            <p class="info">Abre em: {{ \Carbon\Carbon::parse($exercicio->data_publicacao)->format('d/m/Y H:i') }}</p>
                            <p class="info">Fecha em: {{ \Carbon\Carbon::parse($exercicio->data_fechamento)->format('d/m/Y H:i') }}</p>
                            
                            
                            <p class="info card-points"><strong>Pontos:</strong> {{ $exercicio->pontos ?? 0 }}</p>

                            <div class="anexos-card">
                                @if ($exercicio->imagem_apoio_path)
                                    <img src="{{ asset('storage/' . $exercicio->imagem_apoio_path) }}" alt="Imagem de apoio" class="imagem-apoio-preview">
                                @endif

                                @if ($exercicio->arquivo_path)
                                    <a href="{{ asset('storage/' . $exercicio->arquivo_path) }}" target="_blank" class="link-arquivo">
                                        <i class='bx bxs-file-blank'></i> Baixar Arquivo
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="sem-resultados">
                            <p>Nenhum exercício encontrado para este filtro.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="ver-tudo">
                <button id="btnVerTudo">Ver tudo</button>
            </div>
        </section>
    </main>

    <div class="modal-overlay" id="modal">
        <div class="modal-content">
            <form action="{{ route('professor.exercicios.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h2>Criar Exercício</h2>

                <div class="form-group">
                    <label for="nome">Nome do Exercício</label>
                    <input id="nome" name="nome" type="text" placeholder="Ex: Atividade sobre Funções" required />
                </div>
                <div class="form-group">
                    <label for="turma_id">Turma</label>
                    <select id="turma_id" name="turma_id" required>
                        <option value="" disabled selected>Escolha uma Turma</option>
                        @foreach ($turmas as $turma)
                            <option value="{{ $turma->id }}">{{ $turma->nome_turma }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição (Opcional)</label>
                    <textarea id="descricao" name="descricao" placeholder="Instruções sobre o exercício..." rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="pontos">Pontos do Exercício</label>
                    <input id="pontos" name="pontos" type="number" placeholder="Ex: 25" value="10" required min="0" />
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="data_publicacao">Data de Publicação</label>
                        <input id="data_publicacao" name="data_publicacao" type="datetime-local" required />
                    </div>
                    <div class="form-group">
                        <label for="data_fechamento">Data de Fechamento</label>
                        <input id="data_fechamento" name="data_fechamento" type="datetime-local" required />
                    </div>

                    <div class="form-group">
                        <label for="arquivo" class="upload-label">
                            <i class='bx bx-upload'></i> 
                            <span>Escolher arquivo</span>
                        </label>
                        <input name="arquivo" type="file" id="arquivo" class="input-file" />
                        <span id="nomeArquivo" class="nomeArquivo">Nenhum arquivo</span>
                    </div>
                    <div class="form-group">
                        <label for="imagem_apoio" class="upload-label">
                            <i class='bx bx-image-add'></i> 
                            <span>Imagem de apoio</span>
                        </label>
                        <input name="imagem_apoio" type="file" id="imagem_apoio" class="input-file" accept="image/*" />
                        <span id="nomeImagemApoio" class="nomeArquivo">Nenhuma imagem</span>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="button" id="cancelar">Cancelar</button>
                    <button type="submit" class="criar">Criar Exercício</button>
                </div>
            </form>
        </div>
    </div>

    @include('layouts.footer')

    <script src="{{ asset('js/Professor/exercicioProfessor.js') }}"></script>

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
</body>
</html>