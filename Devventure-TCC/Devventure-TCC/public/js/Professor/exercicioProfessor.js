document.addEventListener('DOMContentLoaded', function() {

    // --- LÓGICA PARA ABRIR E FECHAR O MODAL (Preservada e Melhorada) ---
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

    // --- LÓGICA ATUALIZADA PARA MOSTRAR NOMES DOS ARQUIVOS SELECIONADOS ---

    // 1. Seleciona os novos elementos do formulário pelos novos IDs
    const inputArquivos = document.getElementById('arquivos_apoio');
    const nomeArquivosSpan = document.getElementById('nomeArquivos');
    
    const inputImagens = document.getElementById('imagens_apoio');
    const nomeImagensSpan = document.getElementById('nomeImagens');

    // 2. Função reutilizável para lidar com a seleção de arquivos
    function handleFileSelection(inputElement, spanElement, defaultText) {
        if (inputElement && spanElement) {
            inputElement.addEventListener('change', function() {
                // Se mais de um arquivo for selecionado, mostra a quantidade
                if (this.files.length > 1) {
                    spanElement.textContent = `${this.files.length} arquivos selecionados`;
                } 
                // Se apenas um arquivo for selecionado, mostra o nome dele
                else if (this.files.length === 1) {
                    spanElement.textContent = this.files[0].name;
                } 
                // Se nenhum arquivo for selecionado, volta ao texto padrão
                else {
                    spanElement.textContent = defaultText;
                }
            });
        }
    }

    // 3. Aplica a função para ambos os campos de upload
    handleFileSelection(inputArquivos, nomeArquivosSpan, 'Nenhum arquivo');
    handleFileSelection(inputImagens, nomeImagensSpan, 'Nenhuma imagem');


    // --- LÓGICA PARA O BOTÃO "VER TUDO" (Preservada) ---
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
});