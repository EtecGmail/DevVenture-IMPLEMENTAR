<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de {{ $aluno->nome }}</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/Professor/relatorioAluno.css') }}" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="reports-wrapper">
    <header class="reports-header">
        <a href="{{ route('turmas.especificaID', $turma) }}" class="back-link"><i class='bx bx-chevron-left'></i> Voltar para Turma</a>
        <div class="header-info">
            <h1>Relatório Individual</h1>
            <p>{{ $aluno->nome }} - {{ $turma->nome_turma }}</p>
        </div>
    </header>

    <main class="report-aluno-grid">
        <div class="report-main-content"> 
            <div class="card">
                <div class="card-header">
                    <i class='bx bxs-spreadsheet'></i> 
                    <h3>Desempenho nos Exercícios</h3>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Exercício</th>
                                <th>Data de Envio</th>
                                <th>Pontos</th>
                                <th>Nota</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aluno->respostasExercicios as $resposta)
                                <tr>
                                    <td>{{ $resposta->exercicio->nome }}</td>
                                    <td>{{ $resposta->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $resposta->nota ?? 'N/A' }}</td>
                                    <td>
                                        @if($resposta->conceito)
                                            <span class="conceito-tag conceito-{{ strtolower($resposta->conceito) }}">{{ $resposta->conceito }}</span>
                                        @else
                                            <span>N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-message">Nenhum exercício entregue.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                     <i class='bx bxs-videos'></i>
                    <h3>Aulas Concluídas</h3>
                </div>
                 <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Aula</th>
                                <th>Data de Conclusão</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $aulasConcluidas = 0; @endphp
                            @forelse($aluno->aulas as $aula)
                                @if($aula->pivot->status == 'concluido')
                                @php $aulasConcluidas++; @endphp
                                <tr>
                                    <td>{{ $aula->titulo }}</td>
                                    <td>{{ \Carbon\Carbon::parse($aula->pivot->concluido_em)->format('d/m/Y') }}</td>
                                </tr>
                                @endif
                            @empty
                                @endforelse

                            @if($aulasConcluidas == 0)
                                <tr>
                                    <td colspan="2" class="empty-message">Nenhuma aula concluída.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <aside class="sidebar">
            <div class="card summary-card">
                <img src="{{ $aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/100?u='.$aluno->id }}" alt="Avatar do Aluno" class="summary-avatar">
                <h3>{{ $aluno->nome }}</h3>
                <div class="summary-stats">
                    <div class="stat-item">
                        <strong>{{ $aluno->total_pontos }}</strong>
                        <small>Pontos Totais</small>
                    </div>
                    <div class="stat-item">
                        <strong>{{ round($aluno->respostasExercicios->avg('nota'), 1) }}</strong>
                        <small>Média Geral</small>
                    </div>
                </div>
            </div>
            <div class="card">
                 <div class="card-header">
                    <i class='bx bx-line-chart'></i>
                    <h3>Evolução de Notas</h3>
                </div>
                <div class="chart-container">
                    @if($aluno->respostasExercicios->count() > 1)
                        <canvas id="notasAlunoChart"></canvas>
                    @else
                        <div class="empty-message">
                            <p>É necessário que o aluno tenha entregue pelo menos dois exercícios para gerar o gráfico.</p>
                        </div>
                    @endif
                </div>
            </div>
        </aside>
    </main>
</div>

<script>
    @if($aluno->respostasExercicios->count() > 1)
        const notasData = {
            labels: [
                @foreach($aluno->respostasExercicios as $resposta)
                    '{{ Str::limit($resposta->exercicio->nome, 15) }}',
                @endforeach
            ],
            datasets: [{
                label: 'Nota',
                data: [
                    @foreach($aluno->respostasExercicios as $resposta)
                        {{ $resposta->nota ?? 0 }},
                    @endforeach
                ],
                fill: true,
                backgroundColor: 'rgba(0, 121, 107, 0.2)',
                borderColor: 'rgba(0, 121, 107, 1)',
                tension: 0.3
            }]
        };

        const configAluno = {
            type: 'line',
            data: notasData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: { legend: { display: false } }
            }
        };

        const alunoChart = new Chart(
            document.getElementById('notasAlunoChart'),
            configAluno
        );
    @endif
</script>

</body>
</html>