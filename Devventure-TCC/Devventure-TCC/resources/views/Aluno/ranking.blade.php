<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking da Turma - {{ $turma->nome_turma }}</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/Aluno/alunoRanking.css') }}" rel="stylesheet">
</head>
<body>
    @include('layouts.navbar')

    <main class="ranking-wrapper">
        <header class="ranking-header">
            <a href="{{ $backRoute }}" class="btn-voltar"><i class='bx bx-chevron-left'></i> Voltar para a Turma</a>
            <h1>🏆 Ranking da Turma</h1>
            <p>{{ $turma->nome_turma }}</p>
        </header>

        <div class="ranking-table">
            <div class="table-header">
                <span>#</span>
                <span>Aluno</span>
                <span>Pontos</span>
            </div>
            @foreach($alunosRanking as $aluno)
                <div class="table-row {{ (Auth::guard('aluno')->check() && $aluno->id == Auth::guard('aluno')->id()) ? 'current-user' : '' }}">
                    <span class="rank-position">{{ $loop->iteration }}º</span>
                    <div class="user-info">
                        <img src="{{ $aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/40?u='.$aluno->id }}" alt="Avatar">
                        
                        <span>
                            {{ $aluno->nome }}
                            @if (Auth::guard('aluno')->check() && $aluno->id == Auth::guard('aluno')->id())
                                (Você)
                            @endif
                        </span>
                    </div>
                    <span class="user-points">{{ $aluno->total_pontos }} pts</span>
                </div>
            @endforeach
        </div>
    </main>

    @include('layouts.footer')
</body>
</html>