document.addEventListener('DOMContentLoaded', function () {
    const addPerguntaBtn = document.getElementById('add-pergunta-btn');
    const perguntasContainer = document.getElementById('perguntas-container');
    const form = document.getElementById('quiz-form');

    const updateAllIndices = () => {
        const perguntas = perguntasContainer.querySelectorAll('.pergunta-item');
        perguntas.forEach((pergunta, perguntaIndex) => {
            // A numeração visual do título é feita pelo CSS com contadores.

            // Atualiza o name do input da pergunta
            const perguntaTextInput = pergunta.querySelector('input[name$="[texto]"]');
            if(perguntaTextInput) perguntaTextInput.name = `perguntas[${perguntaIndex}][texto]`;

            const opcoes = pergunta.querySelectorAll('.opcoes-container .input-group');
            opcoes.forEach((opcao, opcaoIndex) => {
                const radio = opcao.querySelector('input[type="radio"]');
                const label = opcao.querySelector('label');
                const textInput = opcao.querySelector('input[type="text"]');
                
                // Cria um ID único para o par radio/label, essencial para acessibilidade
                const uniqueId = `option_${perguntaIndex}_${opcaoIndex}`;

                if (radio) {
                    radio.name = `perguntas[${perguntaIndex}][correta]`;
                    radio.value = opcaoIndex;
                    radio.id = uniqueId;
                }

                if (label) {
                    label.htmlFor = uniqueId;
                }
                
                if(textInput) {
                    textInput.name = `perguntas[${perguntaIndex}][opcoes][]`;
                }
            });
        });
    };

    addPerguntaBtn.addEventListener('click', () => {
        // ===================================================================
        // MOLDE HTML CORRIGIDO PARA CORRESPONDER AO NOVO CSS
        // ===================================================================
        const novaPerguntaHTML = `
            <div class="pergunta-item mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="pergunta-titulo"></label>
                    <button type="button" class="btn remove-pergunta-btn" title="Remover Pergunta">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
                
                <div class="input-wrapper mb-3">
                    <i class='bx bxs-help-circle input-icon'></i>
                    <input type="text" name="perguntas[][texto]" class="form-control" placeholder="Digite o texto da pergunta aqui" required>
                </div>
                
                <h6>Opções de Resposta (Marque a correta)</h6>
                
                <div class="opcoes-container">
                    <div class="input-group mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="perguntas[][correta]" value="0" required>
                            <label class="form-check-label"></label>
                        </div>
                        <input type="text" name="perguntas[][opcoes][]" class="form-control" placeholder="Texto da opção" required>
                        <button type="button" class="btn remove-opcao-btn" title="Remover Opção"><i class='bx bx-x'></i></button>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-secondary btn-sm add-opcao-btn mt-2">
                    <i class='bx bx-plus'></i> Adicionar Opção
                </button>
            </div>
        `;
        // ===================================================================

        perguntasContainer.insertAdjacentHTML('beforeend', novaPerguntaHTML);
        updateAllIndices();
    });

    perguntasContainer.addEventListener('click', (e) => {
        if (e.target.closest('.remove-pergunta-btn')) {
            e.target.closest('.pergunta-item').remove();
            updateAllIndices();
        }

        if (e.target.closest('.add-opcao-btn')) {
            const opcoesContainer = e.target.closest('.pergunta-item').querySelector('.opcoes-container');
            const primeiraOpcao = opcoesContainer.querySelector('.input-group');
            const novaOpcao = primeiraOpcao.cloneNode(true);
            
            novaOpcao.querySelector('input[type="text"]').value = '';
            const radio = novaOpcao.querySelector('input[type="radio"]');
            if (radio) radio.checked = false;
            
            opcoesContainer.appendChild(novaOpcao);
            updateAllIndices();
        }
        
        if (e.target.closest('.remove-opcao-btn')) {
            const opcaoItem = e.target.closest('.input-group');
            const opcoesContainer = opcaoItem.parentElement;
            if (opcoesContainer.querySelectorAll('.input-group').length > 1) {
                opcaoItem.remove();
                updateAllIndices();
            } else {
                alert('A pergunta deve ter pelo menos uma opção.');
            }
        }
    });

    form.addEventListener('submit', (e) => {
        const perguntas = perguntasContainer.querySelectorAll('.pergunta-item');
        let formValido = true;
        perguntas.forEach((pergunta, index) => {
            const radios = pergunta.querySelectorAll('input[type="radio"]');
            if (!Array.from(radios).some(radio => radio.checked)) {
                formValido = false;
                // Usamos o número real da pergunta (index + 1)
                alert(`Por favor, marque uma resposta correta para a Pergunta ${index + 1}.`);
            }
        });
        if (!formValido) {
            e.preventDefault();
        }
    });

    updateAllIndices();
});