document.addEventListener('DOMContentLoaded', function() {

    // --- LÓGICA DO MODAL ---
    const modal = document.getElementById('modal');
    const openModalButton = document.querySelector('.add-exercicio button');
    const closeModalButton = document.getElementById('cancelar');

    if (openModalButton && modal) {
        openModalButton.addEventListener('click', () => {
            modal.style.display = 'flex';
        });
    }

    if (closeModalButton && modal) {
        closeModalButton.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }

    if (modal) {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    }

    // --- MOSTRAR NOMES DOS ARQUIVOS ---
    const inputArquivos = document.getElementById('arquivos_apoio');
    const nomeArquivosSpan = document.getElementById('nomeArquivos');
    const inputImagens = document.getElementById('imagens_apoio');
    const nomeImagensSpan = document.getElementById('nomeImagens');

    function handleFileSelection(inputElement, spanElement, defaultText) {
        if (inputElement && spanElement) {
            inputElement.addEventListener('change', function() {
                if (this.files.length > 1) {
                    spanElement.textContent = `${this.files.length} arquivos selecionados`;
                } else if (this.files.length === 1) {
                    spanElement.textContent = this.files[0].name;
                } else {
                    spanElement.textContent = defaultText;
                }
            });
        }
    }

    handleFileSelection(inputArquivos, nomeArquivosSpan, 'Nenhum arquivo');
    handleFileSelection(inputImagens, nomeImagensSpan, 'Nenhuma imagem');


    // --- BOTÃO "VER TUDO" ---
    const btnVerTudo = document.getElementById('btnVerTudo');
    const exerciciosScroll = document.querySelector('.exercicios-scroll');

    if (btnVerTudo && exerciciosScroll) {
        btnVerTudo.addEventListener('click', () => {
            exerciciosScroll.classList.toggle('expandido');
            
            if (exerciciosScroll.classList.contains('expandido')) {
                btnVerTudo.textContent = 'Mostrar em linha';
            } else {
                btnVerTudo.textContent = 'Ver tudo';
            }
        });
    }

    // ==========================================================
    // === TORNAR OS CARDS CLICÁVEIS SEM BLOQUEAR OS DOWNLOADS ===
    // ==========================================================
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Impede que o clique em um link interno (download, imagem, etc.)
            // acione o redirecionamento do card
            if (e.target.closest('.link-arquivo') || e.target.tagName === 'IMG') return;
            window.location.href = this.dataset.url;
        });
    });

});
