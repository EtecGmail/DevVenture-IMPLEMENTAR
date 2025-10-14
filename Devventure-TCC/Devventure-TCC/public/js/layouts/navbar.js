document.addEventListener('DOMContentLoaded', () => {

    // --- LÓGICA PARA CONTROLAR OS DROPDOWNS DE PERFIL ---
    const dropdownButtons = document.querySelectorAll('.profile-button');
    dropdownButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.stopPropagation();
            const dropdown = button.nextElementSibling;
            document.querySelectorAll('.profile-dropdown-content.active').forEach(d => {
                if (d !== dropdown) d.classList.remove('active');
            });
            dropdown.classList.toggle('active');
        });
    });

    window.addEventListener('click', () => {
        document.querySelectorAll('.profile-dropdown-content.active').forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    });

    // --- LÓGICA INTELIGENTE PARA MODAIS ---
    const modalTriggers = document.querySelectorAll('.modal-trigger');
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            const modalId = trigger.dataset.modalTarget;
            const modal = document.querySelector(modalId);
            if (modal) {
                modal.style.display = 'flex';
            }
        });
    });

    const modalCloseButtons = document.querySelectorAll('.modal-close');
    modalCloseButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.dataset.modalClose;
            const modal = document.querySelector(modalId);
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });

    const modalOverlays = document.querySelectorAll('.modal-overlay');
    modalOverlays.forEach(modal => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });

    // --- LÓGICA DA NAVBAR (ALTERADA) ---
    const navbar = document.querySelector('.navbar'); 
    const body = document.body;

    // Verifica se a navbar existe na página antes de continuar
    if (navbar) {
        // CONDIÇÃO: A lógica de transparência só se aplica se o <body> tiver o id 'welcome-page'
        if (body.id === 'welcome-page') {
            // Adiciona o listener de scroll APENAS na página de welcome
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
        } else {
            // Para TODAS as outras páginas, a navbar já começa com a cor fixa
            navbar.classList.add('scrolled');
        }
    }
});