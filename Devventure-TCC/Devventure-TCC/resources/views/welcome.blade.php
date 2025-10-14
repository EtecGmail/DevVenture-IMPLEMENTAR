<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Jornada de Aprendizado</title>
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
</head>
<body  id="welcome-page">

    {{-- O id na body, é para a logica de scrollagem funcionar somente aqui --}}

    @include('layouts.navbar')

    <main>
    
        <section class="hero">
            <canvas id="canvas"></canvas>

            <div class="hero-container">
                <div class="hero-textos">
                    <h1>DevVenture</h1>
                    <h2>Lógica de Programação <br> Interativa</h2>
                    <p>
                        Sistema web educacional completo que permite a professores e alunos
                        uma imersão no ensino e aprendizado da lógica de programação básica.
                    </p>
                    <div class="buttons">
                        <a href="/loginAluno" class="btn primary">Começar a aprender</a>
                        <a href="#modulos" class="btn secondary">Conhecer a plataforma</a>
                    </div>
                </div>
                <div class="hero-carousel">
                    <figure class="icon-cards mt-3">
                        <div class="icon-cards__content">
                            <div class="icon-cards__item d-flex align-items-center justify-content-center">
                                <span class="h1"><img src="{{ asset('images/TelaAluno.jpeg') }}" alt="Tela do Aluno"></span>
                            </div>
                            <div class="icon-cards__item d-flex align-items-center justify-content-center">
                                <span class="h1"><img src="{{ asset('images/TelaAula.jpeg') }}" alt="Tela de Aula"></span>
                            </div>
                            <div class="icon-cards__item d-flex align-items-center justify-content-center">
                                <span class="h1"><img src="{{ asset('images/TelaVideo.jpeg') }}" alt="Tela de Vídeo"></span>
                            </div>
                        </div>
                    </figure>
                </div>
            </div>
        </section>

        <section id="modulos" class="modulos reveal">
            <h2>Nossos Módulos</h2>
            <div class="modulos-container">
                <div class="modulo-card purple">
                    <div class="icon-area-modulo">
                        <div class="icon"><i class='bxr bxs-laptop'></i></div>
                    </div>
                    <h3>Simulador de Algoritmos</h3>
                    <p>Execute e visualize fluxogramas de forma interativa.</p>
                </div>
                <div class="modulo-card blue">
                    <div class="icon-area-modulo">
                        <div class="icon"><i class='bxr bxs-trophy bx-flip-horizontal'></i></div>
                    </div>
                    <h3>Rankings & Gamificação</h3>
                    <p>Participe de desafios e acompanhe seu desempenho.</p>
                </div>
                <div class="modulo-card pink">
                    <div class="icon-area-modulo">
                        <div class="icon"><i class='bxr bxs-camcoder'></i></div>
                    </div>
                    <h3>Vídeos Explicativos</h3>
                    <p>Aulas dinâmicas para facilitar seu aprendizado.</p>
                </div>
                <div class="modulo-card darkblue">
                    <div class="icon-area-modulo">
                        <div class="icon"><i class='bxr bxs-message-dots-2'></i></div>
                    </div>
                    <h3>Fórum Interativo</h3>
                    <p>Tire suas dúvidas e interaja com a comunidade.</p>
                </div>
            </div>
        </section>

        <section class="jornada reveal">
            <h1>Uma jornada de aprendizado intuitiva</h1>
            <p class="subtitulo">Em apenas três passos, você estará no caminho para dominar a lógica.</p>
            <div class="etapas">
                <div class="etapa">
                    <div class="numero">1</div>
                    <div class="card">
                        <h2>Explore os Módulos</h2>
                        <p>Navegue por aulas, vídeos e desafios gamificados projetados para um aprendizado eficaz e divertido.</p>
                    </div>
                </div>
                <div class="etapa">
                    <div class="numero">2</div>
                    <div class="card">
                        <h2>Pratique no Simulador</h2>
                        <p>Teste seus conhecimentos com nosso simulador de algoritmos, recebendo feedback visual e instantâneo.</p>
                    </div>
                </div>
                <div class="etapa">
                    <div class="numero">3</div>
                    <div class="card">
                        <h2>Suba no Ranking</h2>
                        <p>Complete exercícios, ganhe pontos e mostre suas habilidades competindo de forma saudável com outros alunos.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="secao-depoimentos reveal">
            <h2>O que nossos alunos dizem</h2>
            <br><br>
            <div class="container-depoimentos-grid">
                <div class="formulario-depoimento">
                    <h3>Deixe seu depoimento</h3>
                    <form id="formDepoimento">
                        @csrf <div class="campo-formulario">
                            <label for="textoDepoimento">Seu depoimento:</label>
                            <textarea id="textoDepoimento" required maxlength="300" placeholder="Compartilhe sua experiência com a plataforma..."></textarea>
                            <span class="contador-caracteres">0/300</span>
                        </div>
                        <div class="campo-formulario">
                            <label for="autorDepoimento">Seu nome e informações:</label>
                            <input type="text" id="autorDepoimento" required placeholder="Ex: Maria Silva, Estudante de ADS">
                        </div>
                        <button type="submit" class="btn-enviar">Enviar Depoimento</button>
                    </form>
                </div>

                <div class="carrossel-depoimentos">
                    <div class="container-depoimentos" id="containerDepoimentos">
                        {{-- 
                            Aqui você pode usar um loop do Blade para exibir os depoimentos
                            que vêm do seu Controller. Exemplo:
                        --}}
                        @if(isset($depoimentos) && $depoimentos->count() > 0)
                            @foreach($depoimentos as $depoimento)
                                <div class="card-wrapper">
                                    <div class="camada-fundo">
                                        <div class="card-fundo"></div>
                                        <div class="card-fundo"></div>
                                    </div>
                                    <div class="card-depoimento">
                                        <p>"{{ $depoimento->texto }}"</p>
                                        <span>- {{ $depoimento->autor }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- Cards de exemplo caso não haja depoimentos do banco de dados --}}
                            <div class="card-wrapper">
                                <div class="camada-fundo">
                                    <div class="card-fundo"></div><div class="card-fundo"></div>
                                </div>
                                <div class="card-depoimento">
                                    <p>"Uma plataforma incrível que tornou a lógica de programação divertida e acessível!"</p>
                                    <span>- João da Silva, Desenvolvedor Jr.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    @include('layouts.footer')

    <script src="{{ asset('js/hero.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>