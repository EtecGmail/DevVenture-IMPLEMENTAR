<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <meta name="color-scheme" content="dark light">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        (function () {
            const storageKey = 'theme';
            const doc = document.documentElement;
            const mode = localStorage.getItem(storageKey);
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (mode === 'light' || mode === 'dark') {
                doc.dataset.theme = mode;
            } else if (mode === 'auto' || mode === null) {
                doc.dataset.theme = systemPrefersDark ? 'dark' : 'light';
            }
        })();
    </script>
    <link rel="preload" href="{{ asset('js/theme-manager.js') }}" as="script">
    <link href="{{ asset('css/Adm/admDashboard.css') }}" rel="stylesheet">
</head>
<body>

    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logoDevventure.png') }}" alt="Admin Logo" class="admin-logo">
                <span class="logo-text">Admin Dashboard</span>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="#overview" class="active"><i class="fas fa-chart-line"></i><span>Visão Geral</span></a></li>
                    <li><a href="#alunos"><i class="fas fa-user-graduate"></i><span>Alunos</span></a></li>
                    <li><a href="#professores"><i class="fas fa-chalkboard-teacher"></i><span>Professores</span></a></li>
                    <li><a href="#charts-section"><i class="fas fa-chart-pie"></i><span>Análises</span></a></li>
                    <li><a href="#settings"><i class="fas fa-cog"></i><span>Configurações</span></a></li>
                    <li><a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i><span>Sair</span>
                    </a></li>
                </ul>
            </nav>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </aside>

        <div class="main-content">
            <header class="navbar">
                <div class="nav-left">
                    <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
                    <span class="navbar-title">Dashboard Administrativo</span>
                </div>
                <div class="nav-right">
                    @include('partials.theme-toggle')
                    <span class="admin-name">Olá, Admin!</span>
                    <div class="admin-avatar">
                        <img src="{{ asset('images/user.png') }}" alt="Admin Avatar">
                    </div>
                </div>
            </header>

            <div class="dashboard-body">
                <section id="overview" class="dashboard-section active">
                    <h2>Visão Geral</h2>
                    <div class="info-cards-grid">
                        <div class="info-card primary">
                            <i class="fas fa-user-graduate icon"></i>
                            <div class="card-content">
                                <span class="card-number" id="studentsCount">{{ $alunosCount ?? '0' }}</span>
                                <p class="card-text">Alunos Cadastrados</p>
                            </div>
                        </div>
                        <div class="info-card secondary">
                            <i class="fas fa-chalkboard-teacher icon"></i>
                            <div class="card-content">
                                <span class="card-number" id="teachersCount">{{ $professoresCount ?? '0' }}</span>
                                <p class="card-text">Professores Cadastrados</p>
                            </div>
                        </div>
                    </div>

                    <div class="charts-grid">
                        <div class="chart-card">
                            <h3>Alunos vs Professores (Proporção)</h3>
                            <div id="alunosProfessoresChart" style="height: 300px;"></div>
                        </div>
                        <div class="chart-card">
                            <h3>Alunos vs Professores (Contagem)</h3>
                            <div id="overviewBarChart" style="height: 300px;"></div>
                        </div>
                    </div>
                </section>

                
<section id="alunos" class="dashboard-section">
                    <h2>Alunos</h2>
                    <div class="card data-table-card">
                        <header class="card-header">
                            <h4>Lista de Alunos</h4>
                            <div class="card-actions">
                                <form class="search-box" id="searchAlunosForm">
                                    <input type="text" id="searchAlunosInput" placeholder="Pesquisar aluno...">
                                    <button type="submit" class="btn-icon"><i class="fas fa-search"></i></button>
                                </form>
                                <button class="btn btn-outline" id="exportStudentsBtn"><i class="fas fa-download"></i> Exportar</button>
                            </div>
                        </header>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>RA</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="alunosTableBody">
                                    @forelse($alunosData as $aluno)
                                    <tr>
                                        <td>{{ $aluno->nome }}</td>
                                        <td>{{ $aluno->email }}</td>
                                        <td>{{ $aluno->ra }}</td>
                                        <td>
                                            @if ($aluno->status === 'ativo')
                                                <span class="badge badge-success">Ativo</span>
                                            @else
                                                <span class="badge badge-danger">Bloqueado</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" class="btn-icon" title="Ver Detalhes do Aluno"><i class="fas fa-eye"></i></a>
                                            @if ($aluno->status === 'ativo')
                                                <form action="{{ route('admin.alunos.block', $aluno->id) }}" method="POST" style="display: inline;"
                                                      class="form-confirm" data-action-text="bloquear" data-user-name="{{ $aluno->nome }}">
                                                    @csrf
                                                    <button type="submit" class="btn-icon" title="Bloquear Aluno"><i class="fas fa-ban text-danger"></i></button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.alunos.unblock', $aluno->id) }}" method="POST" style="display: inline;"
                                                      class="form-confirm" data-action-text="desbloquear" data-user-name="{{ $aluno->nome }}">
                                                    @csrf
                                                    <button type="submit" class="btn-icon" title="Desbloquear Aluno"><i class="fas fa-check-circle text-success"></i></button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center">Nenhum aluno cadastrado.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination" id="alunosPagination">
                           </div>
                    </div>
                </section>

                <section id="professores" class="dashboard-section">
                    <h2>Professores</h2>
                    <div class="card data-table-card">
                        <header class="card-header">
                            <h4>Lista de Professores</h4>
                            <div class="card-actions">
                                <form class="search-box" id="searchProfessoresForm">
                                    <input type="text" id="searchProfessoresInput" placeholder="Pesquisar professor...">
                                    <button type="submit" class="btn-icon"><i class="fas fa-search"></i></button>
                                </form>
                                <button class="btn btn-outline" id="exportTeachersBtn"><i class="fas fa-download"></i> Exportar</button>
                            </div>
                        </header>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>CPF</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="professoresTableBody">
                                    @forelse($professoresData as $professor)
                                    <tr>
                                        <td>{{ $professor->nome }}</td>
                                        <td>{{ $professor->email }}</td>
                                        <td>{{ $professor->cpf }}</td>
                                        <td>
                                            @if ($professor->status === 'ativo')
                                                <span class="badge badge-success">Ativo</span>
                                            @else
                                                <span class="badge badge-danger">Bloqueado</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" class="btn-icon" title="Ver Detalhes do Professor"><i class="fas fa-eye"></i></a>
                                            @if ($professor->status === 'ativo')
                                                <form action="{{ route('admin.professores.block', $professor->id) }}" method="POST" style="display: inline;"
                                                      class="form-confirm" data-action-text="bloquear" data-user-name="{{ $professor->nome }}">
                                                    @csrf
                                                    <button type="submit" class="btn-icon" title="Bloquear Professor"><i class="fas fa-ban text-danger"></i></button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.professores.unblock', $professor->id) }}" method="POST" style="display: inline;"
                                                      class="form-confirm" data-action-text="desbloquear" data-user-name="{{ $professor->nome }}">
                                                    @csrf
                                                    <button type="submit" class="btn-icon" title="Desbloquear Professor"><i class="fas fa-check-circle text-success"></i></button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center">Nenhum professor cadastrado.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination" id="professoresPagination">
                           </div>
                    </div>
                </section>


                <section id="charts-section" class="dashboard-section">
                    <h2>Análises e Gráficos</h2>
                    <div class="charts-grid-full">
                        <div class="chart-card">
                            <h3>Distribuição de Usuários (Pizza)</h3>
                            <div id="userDistributionPieChart" style="height: 350px;"></div>
                        </div>
                        <div class="chart-card">
                            <h3>Distribuição de Usuários (Barras)</h3>
                            <div id="userDistributionBarChart" style="height: 350px;"></div>
                        </div>
                    </div>
                </section>

                <section id="settings" class="dashboard-section">
                    <h2>Configurações</h2>
                    <div class="card">
                        <p>Área para configurações gerais do sistema, gestão de permissões de admin, etc.</p>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
    <script>
        window.dashboardData = {
            alunosCount: {{ $alunosCount ?? 0 }},
            professoresCount: {{ $professoresCount ?? 0 }},
        };
    </script>
    <script src="{{ asset('js/theme-manager.js') }}" defer></script>
    <script src="{{ asset('js/Adm/admDashboard.js') }}"></script>
</body>
</html>
