const numMesaInput = document.getElementById('nummesa'); // Campo principal da mesa
const mesaInputs = document.querySelectorAll('input[name="mesa_pedido"]');

// Atualiza o valor de todos os campos "mesa_pedido" ao digitar no campo "nummesa"
numMesaInput.addEventListener('input', () => {
    const numMesaValue = numMesaInput.value;
    mesaInputs.forEach(mesaInput => {
        mesaInput.value = numMesaValue; // Atualiza todos os campos "mesa_pedido"
    });
});

// Captura o botão para adicionar um lanche
const enviarLancheBtns = document.querySelectorAll('.enviarLanche input[type="button"]');


const pedidosDiv = document.getElementById('pedidos');
const listaPedidos = document.getElementById('listaPedidos');
const comandaMesa = document.getElementById('comandaMesa');
const btnDivCancelar = document.getElementById('btnDivCancelar');
const btnDiv = document.getElementById('btnDiv');

let cont = 0

enviarLancheBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
        event.preventDefault();
        cont += 1

        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

        // Captura os dados do lanche e da mesa
        const nomeLanche = form.querySelector('input[name="lanche"]').value;
        const valorLanche = form.querySelector('input[name="valor_lanche"]').value;
        const detalhesLanche = form.querySelector('input[name="detalhesLanche"]').value;
        const mesaPedido = form.querySelector('input[name="mesa_pedido"]').value || numMesaInput.value; // Garante que o valor será capturado

        // Verifica se o valor da mesa está presente
        if (!mesaPedido || mesaPedido < 0) {
            alert('Por favor, insira o número da mesa antes de selecionar o pedido.');
            cont = 0
            return;
        }

        // Cria uma nova linha na tabela do formulário
        const row = document.createElement('tr');

        const nomeLancheCell = document.createElement('th');
        nomeLancheCell.className = 'bg-light text-primary';
        nomeLancheCell.textContent = nomeLanche;
        row.appendChild(nomeLancheCell);

        const valorCell = document.createElement('td');
        valorCell.className = 'text-center align-middle bg-light fw-bold';
        valorCell.style.color = 'blue';
        valorCell.textContent = `$${parseFloat(valorLanche).toFixed(2)}`;
        row.appendChild(valorCell);

        const detalheLancheCell = document.createElement('td');
        detalheLancheCell.className = 'text-center align-middle bg-light';
        detalheLancheCell.textContent = detalhesLanche;
        row.appendChild(detalheLancheCell);

        if (cont == 1) {
            const mesaCell = document.createElement('spam');
            mesaCell.textContent = mesaPedido; // Adiciona o valor da mesa
            comandaMesa.appendChild(mesaCell);
        }

        // Adiciona a nova linha à tabela
        listaPedidos.appendChild(row);

        // Cria inputs ocultos para envio no formulário
        const nomeLancheInput = document.createElement('input');
        nomeLancheInput.type = 'hidden';
        nomeLancheInput.name = 'nome_lanche[]';
        nomeLancheInput.value = nomeLanche;

        const valorInput = document.createElement('input');
        valorInput.type = 'hidden';
        valorInput.name = 'valor_lanche[]';
        valorInput.value = valorLanche;

        const detalhesLancheInput = document.createElement('input');
        detalhesLancheInput.type = 'hidden';
        detalhesLancheInput.name = 'detalhes_lanche[]';
        detalhesLancheInput.value = detalhesLanche;

        const mesaInput = document.createElement('input');
        mesaInput.type = 'hidden';
        mesaInput.name = 'mesa[]';
        mesaInput.value = mesaPedido;
        
        btnDivCancelar.addEventListener('click', ()=>{
            let confirma = confirm("Cancelar?")
            if(confirma){
                location.reload()
            }
        })

        pedidosDiv.appendChild(nomeLancheInput);
        pedidosDiv.appendChild(valorInput);
        pedidosDiv.appendChild(mesaInput);
        pedidosDiv.appendChild(detalhesLancheInput);
    });
});

const enviarBebidaBtns = document.querySelectorAll('.enviarBebida input[type="button"]');
const listaBebidas = document.getElementById('listaBebidas');

enviarBebidaBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
        event.preventDefault();
        cont += 1;

        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

        // Captura os dados da bebida
        const nomeBebida = form.querySelector('input[name="bebida"]').value;
        const detalhesBebida = form.querySelector('input[name="detalhesBebida"]').value;
        const mesaPedido = form.querySelector('input[name="mesa_pedido"]').value || numMesaInput.value;

        // Captura o tamanho e o valor da bebida
        const select = form.querySelector('select#tamanho_valor');
        const [valorBebida, tamanhoBebida] = select.value.split('|'); // Divide pelo separador "|"

        // Verifica se o valor da mesa está presente
        if (!mesaPedido || mesaPedido < 0) {
            alert('Por favor, insira o número da mesa antes de selecionar o pedido.');
            cont = 0;
            return;
        }

        // Cria uma nova linha na tabela do formulário
        const row = document.createElement('tr');

        const nomeBebidaCell = document.createElement('th');
        nomeBebidaCell.className = 'bg-light text-primary';
        nomeBebidaCell.textContent = `${nomeBebida} (${tamanhoBebida})`; // Exibe o nome e o tamanho
        row.appendChild(nomeBebidaCell);

        const valorBebidaCell = document.createElement('td');
        valorBebidaCell.className = 'text-center align-middle bg-light fw-bold';
        valorBebidaCell.style.color = 'blue';
        valorBebidaCell.textContent = `$${parseFloat(valorBebida).toFixed(2)}`;
        row.appendChild(valorBebidaCell);

        const detalheBebidaCell = document.createElement('td');
        detalheBebidaCell.className = 'text-center align-middle bg-light';
        detalheBebidaCell.textContent = detalhesBebida;
        row.appendChild(detalheBebidaCell);

        if (cont == 1) {
            const mesaCell = document.createElement('span');
            mesaCell.textContent = mesaPedido;
            comandaMesa.appendChild(mesaCell);
        }

        // Adiciona a nova linha à tabela
        listaBebidas.appendChild(row);

        // Cria inputs ocultos para envio no formulário
        const nomeBebidaInput = document.createElement('input');
        nomeBebidaInput.type = 'hidden';
        nomeBebidaInput.name = 'nome_bebida[]';
        nomeBebidaInput.value = nomeBebida;

        const valorBebidaInput = document.createElement('input');
        valorBebidaInput.type = 'hidden';
        valorBebidaInput.name = 'valor_bebida[]';
        valorBebidaInput.value = valorBebida;

        const tamanhoBebidaInput = document.createElement('input');
        tamanhoBebidaInput.type = 'hidden';
        tamanhoBebidaInput.name = 'tamanho_bebida[]';
        tamanhoBebidaInput.value = tamanhoBebida;

        const detalhesBebidaInput = document.createElement('input');
        detalhesBebidaInput.type = 'hidden';
        detalhesBebidaInput.name = 'detalhes_bebida[]';
        detalhesBebidaInput.value = detalhesBebida;


        const mesaInput = document.createElement('input');
        mesaInput.type = 'hidden';
        mesaInput.name = 'mesa[]';
        mesaInput.value = mesaPedido;

        pedidosDiv.appendChild(nomeBebidaInput);
        pedidosDiv.appendChild(valorBebidaInput);
        pedidosDiv.appendChild(tamanhoBebidaInput);
        pedidosDiv.appendChild(detalhesBebidaInput);
        pedidosDiv.appendChild(mesaInput); 
    });
});




