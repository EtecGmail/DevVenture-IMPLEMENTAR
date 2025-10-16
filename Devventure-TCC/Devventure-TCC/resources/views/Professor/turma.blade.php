<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gerenciador de Turmas</title>
  <link href="{{ asset('css/Professor/turmaProfessor.css') }}" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
</head>
<body>

  @include('layouts.navbar')

  <main>
    <section class="intro">
        <a href="/professorDashboard" class="btn-voltar">
            <i class='bx bx-chevron-left'></i>
            Voltar
        </a>
      <h1>Turmas</h1>
      <p>Crie e gerencie turmas com total facilidade</p>

      <div class="add-turma">
        <button>Adicionar turma</button>
      </div>
    </section>

    <section class="turmas">
      <div class="turmas-header">
        <div>
          <h2>Minhas turmas</h2>
          <p>Acesse uma turma para gerenciar os detalhes.</p>
        </div>
        <form action="{{ url('/professorGerenciarEspecifica') }}" method="GET" class="search">
          <input name="search" type="text" placeholder="Pesquisar turma..." value="{{ request('search') }}">
          <button type="submit"><i class='bx bx-search'></i></button>
        </form>
      </div>

      <div class="turmas-scroll">
        <div class="turmas-grid">
            @forelse ($turmas as $turma)
            <div class="card">
                <!-- ===== DIV ADICIONAL AQUI ===== -->
                <div class="card-body">
                    <h3>{{ $turma->nome_turma }}</h3>
                    <div class="tags">
                        <span class="tag">{{ ucfirst($turma->turno) }}</span>
                    </div>
                    <p class="info">
                        <i class='bx bxs-group'></i> 
                        {{ $turma->alunos_count }} 
                        {{ $turma->alunos_count == 1 ? 'Aluno' : 'Alunos' }}
                    </p>
                    <p class="info">
                        <i class='bx bxs-book-content'></i> 
                        {{ $turma->exercicios_count }} 
                        {{ $turma->exercicios_count == 1 ? 'atividade' : 'atividades' }}
                    </p>
                </div>

                <div class="card-actions">
                    <a href="{{ route('professor.relatorios.index', $turma) }}" class="card-action-btn btn-report">
                        <i class='bx bx-line-chart'></i> Relatórios
                    </a>
                    <a href="{{ route('turmas.especificaID', $turma) }}" class="card-action-btn btn-manage">
                        <i class='bx bx-cog'></i> Gerenciar
                    </a>
                </div>
            </div>
            @empty
                <p>Você ainda não criou nenhuma turma. Clique em "Adicionar turma" para começar!</p>
            @endforelse
        </div>
      </div>

       <div class="ver-tudo">
         <button  id="btnVerTudo">Ver tudo</button>
       </div>
    </section>
  </main>


  <div class="modal-overlay" id="modal">
  <div class="modal-content">

    <form action="{{ url('/cadastrar-turma') }}" method="POST">
      @csrf
       <h2>Criar Turma</h2>

      <label for="nome_turma" class="sr-only">Nome da turma</label>
      <input type="text" id="nome_turma" name="nome_turma" placeholder="Nome da turma" required />

      <label for="turno" class="sr-only">Turno</label>
      <select id="turno" name="turno" required>
        <option value="" disabled selected>Turno</option>
        <option value="manha">Manhã</option>
        <option value="tarde">Tarde</option>
        <option value="noite">Noite</option>
      </select>

      <label for="disciplina" class="sr-only">Ano da Turma</label>
      <input type="text" id="disciplina" name="ano_turma" placeholder="Ano da Turma" required />

      <label for="data_inicio">Data de início da turma</label>
      <input type="date" id="data_inicio" name="data_inicio" required />

      <label for="data_fim">Data de término da turma</label>
      <input type="date" id="data_fim" name="data_fim" required />

      <div class="modal-buttons">
        <button type="button" id="cancelar">Cancelar</button>
        <button type="submit" class="criar">Criar turma</button>
      </div>

    </form> 
  </div>
</div>


  @include('layouts.footer')

 
 <script src="{{ asset('js/Professor/turmaProfessor.js') }}"></script>
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
