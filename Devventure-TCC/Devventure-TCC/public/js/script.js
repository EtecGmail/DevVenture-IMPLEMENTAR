document.addEventListener('DOMContentLoaded', function() {
  // Elementos do DOM
  const containerDepoimentos = document.getElementById('containerDepoimentos');
  const indicadores = document.getElementById('indicadores');
  const btnAnterior = document.getElementById('btnAnterior');
  const btnProximo = document.getElementById('btnProximo');
  
  // Estado do carrossel
  let currentIndex = 0;
  let isAnimating = false;
  
  // Inicializar carrossel
  function inicializarCarrossel() {
    const cards = containerDepoimentos.querySelectorAll('.card-wrapper');
    
    // Se não há cards, mostra mensagem
    if (cards.length === 0) {
      containerDepoimentos.innerHTML = '<div class="sem-depoimentos">Nenhum depoimento cadastrado.</div>';
      indicadores.innerHTML = '';
      btnAnterior.disabled = true;
      btnProximo.disabled = true;
      return;
    }
    
    
    
    // Mostra o primeiro card
    if (cards.length > 0) {
      currentIndex = 0;
      cards[0].classList.add('active');
    }
    
    // Adiciona eventos de clique aos cards
    cards.forEach((card, index) => {
      card.addEventListener('click', () => {
        const nextIndex = (index + 1) % cards.length;
        changeTestimonial(nextIndex);
      });
    });
  }
  

  

  
  // Função para trocar o depoimento ativo
  function changeTestimonial(newIndex) {
    const cards = containerDepoimentos.querySelectorAll('.card-wrapper');
    
    // Se já estiver animando ou não há cards suficientes, não faz nada
    if (isAnimating || cards.length <= 1 || newIndex === currentIndex) return;
    
    isAnimating = true;
    
    // Remove a classe active do card atual
    const currentCard = cards[currentIndex];
    currentCard.classList.remove('active');
    currentCard.classList.add('exiting');
    
    
    
    // Atualiza o índice atual
    currentIndex = newIndex;
    
    // Adiciona a classe active ao novo card
    const newCard = cards[currentIndex];
    newCard.classList.add('entering');
    
  
    
    // Aguarda um pequeno delay para a animação de saída
    setTimeout(() => {
      // Remove as classes de animação do card antigo
      currentCard.classList.remove('exiting');
      
      // Remove a classe de entrada e adiciona active ao novo card
      newCard.classList.remove('entering');
      newCard.classList.add('active');
      
      isAnimating = false;
    }, 500);
  }
  
 
  
  // Auto-rotacionar os depoimentos a cada 8 segundos (apenas se houver mais de 1)
  setInterval(() => {
    const cards = containerDepoimentos.querySelectorAll('.card-wrapper');
    if (cards.length > 1 && !isAnimating) {
      const newIndex = (currentIndex + 1) % cards.length;
      changeTestimonial(newIndex);
    }
  }, 8000);
  
  // Inicializar o carrossel
  inicializarCarrossel();
});