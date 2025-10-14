// Espera que todo o conteúdo da página (HTML) seja carregado antes de executar.
document.addEventListener('DOMContentLoaded', function() {

    // --- 1. SELEÇÃO DOS ELEMENTOS ---
    // Botões que abrem os modais
    const btnAbrirModalAluno = document.getElementById('btnAbrirModalAluno');
    const btnAbrirModalAula = document.getElementById('btnAbrirModalAula');

    // Os próprios modais
    const modalConvidarAluno = document.getElementById('modalConvidarAluno');
    const modalAdicionarAula = document.getElementById('modalAdicionarAula');

    // Todos os botões que fecham modais (o 'X' e os botões de "Cancelar")
    const closeButtons = document.querySelectorAll('.modal-close, .btn-cancelar');

    // --- DIAGNÓSTICO INICIAL (Verifique no console F12) ---
    // Isso ajuda a garantir que os IDs no HTML e no JS estão corretos.
    if (!btnAbrirModalAluno) console.error("Botão 'Convidar Aluno' não encontrado. Verifique o ID.");
    if (!modalConvidarAluno) console.error("Modal 'Convidar Aluno' não encontrado. Verifique o ID.");
    if (!btnAbrirModalAula) console.error("Botão 'Adicionar Aula' não encontrado. Verifique o ID.");
    if (!modalAdicionarAula) console.error("Modal 'Adicionar Aula' não encontrado. Verifique o ID.");


    // --- 2. FUNÇÕES PARA CONTROLAR OS MODAIS ---
    function openModal(modalElement) {
        if (modalElement) {
            // Em vez de adicionar uma classe, mudamos o estilo 'display' para 'flex'
            modalElement.style.display = 'flex';
        }
    }

    function closeModal() {
        // Esconde todos os modais de uma vez
        if (modalConvidarAluno) modalConvidarAluno.style.display = 'none';
        if (modalAdicionarAula) modalAdicionarAula.style.display = 'none';
    }


    // --- 3. ADICIONANDO OS EVENTOS DE CLIQUE ---

    // Evento para abrir o modal de Convidar Aluno
    if (btnAbrirModalAluno) {
        btnAbrirModalAluno.addEventListener('click', function() {
            openModal(modalConvidarAluno);
        });
    }

    // Evento para abrir o modal de Adicionar Aula
    if (btnAbrirModalAula) {
        btnAbrirModalAula.addEventListener('click', function() {
            openModal(modalAdicionarAula);
        });
    }

    // Evento para fechar os modais usando os botões 'X' e 'Cancelar'
    closeButtons.forEach(button => {
        button.addEventListener('click', closeModal);
    });

    // Evento para fechar o modal clicando fora dele (no overlay)
    window.addEventListener('click', function(event) {
        if (event.target === modalConvidarAluno || event.target === modalAdicionarAula) {
            closeModal();
        }
    });


    // --- 4. LÓGICA PARA SWEETALERT2 (Sua lógica original, preservada) ---
    if (window.flashMessages) {
        if (window.flashMessages.sweetSuccessConvite) {
            Swal.fire('Sucesso!', window.flashMessages.sweetSuccessConvite, 'success');
        }
        if (window.flashMessages.sweetErrorConvite) {
            Swal.fire('Erro!', window.flashMessages.sweetErrorConvite, 'error');
        }
        if (window.flashMessages.sweetErrorAula) {
            Swal.fire('Erro na Aula!', window.flashMessages.sweetErrorAula, 'error');
        }
    }

    if (typeof aulaCriadaFeedback !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: aulaCriadaFeedback.message,
            html: 'Deseja criar um formulário de validação para esta aula agora?',
            showCancelButton: true,
            confirmButtonColor: '#00796B',
            cancelButtonColor: '#6c757d',
            confirmButtonText: aulaCriadaFeedback.next_action_text,
            cancelButtonText: 'Fazer isso depois',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = aulaCriadaFeedback.next_action_url;
            }
        });
    }
    
    if (typeof formularioCriadoSuccess !== 'undefined') {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: formularioCriadoSuccess,
            showConfirmButton: false,
            timer: 2000
        });
    }

});