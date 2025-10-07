<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Footer DevVenture</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="{{ asset('css/layouts/footer.css') }}" rel="stylesheet">
</head>
<body>

<footer class="footer">
  <div class="footer-container">
    <div class="footer-grid">
      
      <div class="footer-section about">
        <div class="footer-logo">
          <img src="./images/logoDevventure.png" alt="DevVenture logo">
          <span>DevVenture</span>
        </div>
        <p class="footer-description">
          Plataforma educacional da equipe Harpion para conectar professores e alunos da ETEC Guaianazes.
        </p>
      </div>

      <div class="footer-section links">
        <h3>Links Rápidos</h3>
        <ul>
          <li><a href="/">Início</a></li>
          <li><a href="/loginProfessor">Área do Professor</a></li>
          <li><a href="/loginAluno">Área do Aluno</a></li>
          <li><a href="/loginProfessor">Login Professor</a></li>
          <li><a href="/loginAluno">Login Aluno</a></li>
        </ul>
      </div>

      <div class="footer-section contact">
        <h3>Contato</h3>
        <ul>
          <li><i class="fas fa-envelope"></i><span>harpion@etec.sp.gov.br</span></li>
          <li><i class="fas fa-phone"></i><span>(11) 2551-3547</span></li>
          <li><i class="fas fa-map-marker-alt"></i><span>Rua Feliciano de Mendonça, 290, Guaianazes – SP</span></li>
        </ul>
      </div>

      <div class="footer-section project-info">
        <h3>Equipe Harpion</h3>
        <p class="footer-description">
          Projeto de TCC em Desenvolvimento de Sistemas na ETEC Guaianazes.
        </p>
        <div class="footer-techs">
          <span>PHP</span>
          <span>Laravel</span>
          <span>HTML</span>
          <span>CSS</span>
          <span>JS</span>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <div class="footer-copy">
        © <span id="year"></span> Equipe Harpion. Todos os direitos reservados.
      </div>
      <div class="footer-social-links">
        <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
      </div>
    </div>
  </div>
</footer>

<script>
  document.getElementById('year').textContent = new Date().getFullYear();
</script>

</body>
</html>