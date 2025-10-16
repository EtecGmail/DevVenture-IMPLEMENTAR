<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios de Desempenho - {{ $turma->nome_turma }}</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/Professor/relatorios.css') }}" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="reports-wrapper">
    <header class="reports-header">
        <a href="{{ route('turmas.especificaID', $turma) }}" class="back-link"><i class='bx bx-chevron-left'></i> Voltar para a Turma</a>
        <div class="header-info">
            <h1><i class='bx bxs-bar-chart-alt-2'></i> Relatórios de Desempenho</h1>
            <p>{{ $turma->nome_turma }}</p>
        </div>
    </header>

    <div class="reports-grid">
       
        <div class="card card-media">
            <div class="card-header">
                <h3>Média Geral da Turma</h3>
                <i class='bx bx-line-chart card-icon'></i>
            </div>
            <div class="card-body">
                <span class="main-metric">{{ round($mediaGeral, 1) }}</span>
                <small>/ 100 pontos</small>
            </div>
        </div>

        <!-- Card: Taxa de Engajamento  -->
        <div class="card card-engajamento">
            <div class="card-header">
                <h3>Taxa de Engajamento</h3>
                <i class='bx bx-task card-icon'></i>
            </div>
            <div class="card-body">
                <span class="main-metric">{{ $taxaEngajamento }}<small>%</small></span>
                @if($ultimoExercicio)
                    <small>entregaram o exercício "{{ Str::limit($ultimoExercicio->nome, 20) }}"</small>
                @else
                    <small>Nenhum exercício publicado ainda.</small>
                @endif
            </div>
        </div>

        <!-- Card: Alunos em Destaque -->
        <div class="card card-destaques">
            <div class="card-header">
                <h3><i class='bx bxs-trophy'></i> Alunos em Destaque</h3>
            </div>
            <ul class="user-list">
                @forelse($alunosDestaque as $aluno)
                <a href="{{ route('professor.relatorios.aluno', ['turma' => $turma, 'aluno' => $aluno]) }}">
                    <img src="{{ $aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/40?u='.$aluno->id }}" alt="Avatar" class="avatar">
                    <span class="user-name">{{ $aluno->nome }}</span>
                    <span class="user-points">{{ $aluno->total_pontos }} pts</span>
                </a>
                @empty
                    <p class="empty-message">Nenhum aluno com pontuação.</p>
                @endforelse
            </ul>
        </div>
        
        <!-- Card: Alunos que Precisam de Atenção -->
        <div class="card card-atencao">
            <div class="card-header">
                <h3><i class='bx bxs-user-error'></i> Alunos que Precisam de Atenção</h3>
            </div>
             <ul class="user-list">
                @forelse($alunosAtencao as $aluno)
                <a href="{{ route('professor.relatorios.aluno', ['turma' => $turma, 'aluno' => $aluno]) }}">
                    <img src="{{ $aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/40?u='.$aluno->id }}" alt="Avatar" class="avatar">
                    <span class="user-name">{{ $aluno->nome }}</span>
                    <span class="user-points low-score">Não entregou</span>
                </a>
                @empty
                    <p class="empty-message">Todos os alunos entregaram a última atividade. Ótimo!</p>
                @endforelse
            </ul>
        </div>

   <div class="card card-grafico">
            <div class="card-header">
                <h3>Desempenho por Exercício (Média da Turma)</h3>
            </div>
            <div class="chart-container">
                
                {{-- só mostra o gráfico se houver mais de um ponto de dados --}}
                @if($desempenhoPorExercicio->count() > 1)
                    <canvas id="desempenhoChart"></canvas>
                @else
                    <div class="empty-message">
                        <p>É necessário que pelo menos dois exercícios tenham sido avaliados para gerar o gráfico de evolução.</p>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>

<script>
    // Verifica se existem dados suficientes para o gráfico antes de tentar renderizá-lo
    @if($desempenhoPorExercicio->count() > 1)
        const desempenhoData = {
            labels: [
                @foreach($desempenhoPorExercicio as $desempenho)
                    '{{ Str::limit($desempenho->nome, 20) }}',
                @endforeach
            ],
            datasets: [{
                label: 'Média da Turma',
                data: [
                    @foreach($desempenhoPorExercicio as $desempenho)
                        {{ $desempenho->respostas_avg_nota }},
                    @endforeach
                ],
                fill: true,
                backgroundColor: 'rgba(0, 121, 107, 0.2)',
                borderColor: 'rgba(0, 121, 107, 1)',
                tension: 0.3 
            }]
        };
        const config = {
            type: 'line', 
            data: desempenhoData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, max: 100 } },
                plugins: { legend: { display: false } }
            }
        };
        const myChart = new Chart(document.getElementById('desempenhoChart'), config);
    @endif
</script>

</body>
</html>
