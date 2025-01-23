const numMesaInput = document.getElementById('nummesa'); // Campo principal da mesa
const mesaInputs = document.querySelectorAll('input[name="mesa_pedido"]');

// Atualiza o valor de todos os campos "mesa_pedido" ao digitar no campo "nummesa"
if (numMesaInput) {
    numMesaInput.addEventListener('input', () => {
        console.log('Valor da mesa:', numMesaInput.value);
        // Insira aqui o código necessário para essa funcionalidade
    });
}

// Captura o botão para adicionar um lanche
const enviarLancheBtns = document.querySelectorAll('.enviarLanche input[type="button"]');


const pedidosDiv = document.getElementById('pedidos');
const listaPedidos = document.getElementById('listaPedidos');
const comandaMesa = document.getElementById('comandaMesa');
const totalMesa = document.getElementById('totalMesa');
const btnDivCancelar = document.getElementById('btnDivCancelar');
const btnDiv = document.getElementById('btnDiv');

let cont = 0
let totalLanche = 0
let totalBebida = 0
let total = 0
let ingred = ""
let lanche_ingredi = ""

enviarLancheBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
        event.preventDefault();
        cont += 1

        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

        // Captura os dados do lanche e da mesa
        const nomeLanche = form.querySelector('input[name="lanche"]').value;
        const valorLanche = form.querySelector('input[name="valor_lanche"]').value;
        lanche_ingredi = nomeLanche
        console.log(lanche_ingredi)
        const detalhesLanche = form.querySelector('input[name="detalhesLanche"]').value;
        const mesaPedido = form.querySelector('input[name="mesa_pedido"]').value || numMesaInput.value; // Garante que o valor será capturado
        const a_lanche = form.querySelector('a[id="a_lanche"]')

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
        valorCell.className = 'text-center align-middle bg-light fw-bolder';
        valorCell.style = 'color: blue;'
        valorCell.textContent = `$${parseFloat(valorLanche).toFixed(2)}`;
        row.appendChild(valorCell);

        let totalCell = document.querySelector('#totalMesa span'); // Verifica se já existe um <span> no totalMesa

        if (!totalCell) {
            // Se não existir, cria um novo elemento
            totalCell = document.createElement('span');
            totalMesa.appendChild(totalCell);
        }

        total += parseFloat(valorLanche);

        totalCell.textContent = `$${total.toFixed(2)}`;

        const detalheLancheCell = document.createElement('td');
        detalheLancheCell.className = 'text-center align-middle bg-light';
        detalheLancheCell.textContent = detalhesLanche;
        row.appendChild(detalheLancheCell);

        const obsLancheBtnEditar = document.createElement('button');
        obsLancheBtnEditar.className = 'text-center align-middle text-white me-2 fw-bolder';
        obsLancheBtnEditar.style = 'border-radius: 4px; width: 30px;  background-color: #343aeb; border: #1c1f8f;'
        obsLancheBtnEditar.innerHTML = '<i class="fa-solid fa-pen-to-square"><i>';
        obsLancheBtnEditar.onclick = () => {
            window.location.href = a_lanche;
        }; 
        
        const obsLancheBtnApagar = document.createElement('button');
        obsLancheBtnApagar.className = 'text-center align-middle text-white';
        obsLancheBtnApagar.style = 'border-radius: 4px; background-color: #dc3545; border: #b02a37; width: 30px;'
        obsLancheBtnApagar.innerHTML = '<i class="fa-solid fa-trash"></i>';
        obsLancheBtnApagar.onclick = () => {
            row.remove(); // Remove a linha inteira do DOM

            // Coloca os inputs ocultos em um array
            const hiddenInputs = [nomeLancheInput, valorInput, detalhesLancheInput, mesaInput];

            // Itera sobre o array para remover os inputs
            hiddenInputs.forEach(function(input){
                input.remove();
            });

            total -= parseFloat(valorLanche); 
            totalCell.textContent = `$${total.toFixed(2)}`;
        }; 

        const obsLancheCell = document.createElement('td');
        obsLancheCell.className = 'text-center align-middle bg-light';
        obsLancheCell.appendChild(obsLancheBtnEditar);
        obsLancheCell.appendChild(obsLancheBtnApagar);
        row.appendChild(obsLancheCell);  
              
        if (cont == 1) {
            const mesaCell = document.createElement('span');
            mesaCell.textContent = mesaPedido; // Adiciona o valor da mesa
            comandaMesa.appendChild(mesaCell);
        }
    
        // Adiciona a nova linha à tabela
        listaPedidos.appendChild(row);

        // Cria inputs ocultos para envio no formulário
        const mesaInput = document.createElement('input');
        mesaInput.type = 'hidden';
        mesaInput.name = 'mesa[]';
        mesaInput.value = mesaPedido;

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

        btnDiv.addEventListener("click", ()=>{
            const totalInput = document.createElement('input');
            totalInput.type = 'hidden';
            totalInput.name = 'total[]';
            totalInput.value = total;

            pedidosDiv.appendChild(totalInput);

        })
        
        btnDivCancelar.addEventListener('click', ()=>{
            let confirma = confirm("Cancelar?")
            if(confirma){
                location.reload()
            }
        })

        pedidosDiv.appendChild(mesaInput);
        pedidosDiv.appendChild(nomeLancheInput);
        pedidosDiv.appendChild(valorInput);
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
    
        let totalCell = document.querySelector('#totalMesa span'); // Verifica se já existe um <span> no totalMesa

        if (!totalCell) {
            // Se não existir, cria um novo elemento
            totalCell = document.createElement('span');
            totalMesa.appendChild(totalCell);
        }

        total += parseFloat(valorBebida);

        totalCell.textContent = `$${total.toFixed(2)}`;

        const detalheBebidaCell = document.createElement('td');
        detalheBebidaCell.className = 'text-center align-middle bg-light';
        detalheBebidaCell.textContent = detalhesBebida;
        row.appendChild(detalheBebidaCell);
        
        const obsBebidaBtnApagar = document.createElement('button');
        obsBebidaBtnApagar.className = 'text-center align-middle bg-danger text-white';
        obsBebidaBtnApagar.style = 'border-radius: 4px; background-color: #dc3545; border: #b02a37; width: 30px;'
        obsBebidaBtnApagar.innerHTML = '<i class="fa-solid fa-trash"></i>';
        obsBebidaBtnApagar.onclick = () => {
            row.remove(); // Remove a linha inteira do DOM

            // Coloca os inputs ocultos em um array
            const hiddenInputs = [nomeBebidaInput, valorBebidaInput, tamanhoBebidaInput, detalhesBebidaInput, mesaInput];

            // Itera sobre o array para remover os inputs
            hiddenInputs.forEach(function(input){
                input.remove();
            });

            total -= parseFloat(valorBebida); 
            totalCell.textContent = `$${total.toFixed(2)}`;
        }; 

        const obsBebidaCell = document.createElement('td');
        obsBebidaCell.className = 'text-center align-middle bg-light';
        obsBebidaCell.appendChild(obsBebidaBtnApagar);
        row.appendChild(obsBebidaCell);

        if (cont == 1) {
            const mesaCell = document.createElement('span');
            mesaCell.textContent = mesaPedido;
            comandaMesa.appendChild(mesaCell);
        }

        // Adiciona a nova linha à tabela
        listaBebidas.appendChild(row);

        // Cria inputs ocultos para envio no formulário
        const mesaInput = document.createElement('input');
        mesaInput.type = 'hidden';
        mesaInput.name = 'mesa[]';
        mesaInput.value = mesaPedido;

        const nomeBebidaInput = document.createElement('input');
        nomeBebidaInput.type = 'hidden';
        nomeBebidaInput.name = 'nome_bebida[]';
        nomeBebidaInput.value = nomeBebida;

        const tamanhoBebidaInput = document.createElement('input');
        tamanhoBebidaInput.type = 'hidden';
        tamanhoBebidaInput.name = 'tamanho_bebida[]';
        tamanhoBebidaInput.value = tamanhoBebida;

        const valorBebidaInput = document.createElement('input');
        valorBebidaInput.type = 'hidden';
        valorBebidaInput.name = 'valor_bebida[]';
        valorBebidaInput.value = valorBebida;

        const detalhesBebidaInput = document.createElement('input');
        detalhesBebidaInput.type = 'hidden';
        detalhesBebidaInput.name = 'detalhes_bebida[]';
        detalhesBebidaInput.value = detalhesBebida;

        btnDiv.addEventListener("click", ()=>{
            const totalbebidaInput = document.createElement('input');
            totalbebidaInput.type = 'hidden';
            totalbebidaInput.name = 'total[]';
            totalbebidaInput.value = total;

            pedidosDiv.appendChild(totalbebidaInput);
        })

        pedidosDiv.appendChild(mesaInput); 
        pedidosDiv.appendChild(nomeBebidaInput);
        pedidosDiv.appendChild(tamanhoBebidaInput);
        pedidosDiv.appendChild(valorBebidaInput);
        pedidosDiv.appendChild(detalhesBebidaInput);
    });
});

const enviarIngrediBtns = document.querySelectorAll('.enviarIngredi input[type="button"]');
    
enviarIngrediBtns.forEach((btn) => {
btn.addEventListener('click', (event) => {
    event.preventDefault();

    const form = btn.closest('tr');

    const nomeIngredi = form.querySelector('input[name="nome_ingredi"]').value;

    let ingredCell = document.querySelector('#listaPedidos div'); // Verifica se já existe um <span> no totalMesa

    if(!ingredCell){
        const ingredCell = document.createElement('div');
        ingredCell.className = 'row'
        listaPedidos.appendChild(ingredCell)
    }

    const nomeIngrediCell = document.createElement('span');
    nomeIngrediCell.className = 'col bg-light text-black';
    nomeIngrediCell.textContent = " + " + nomeIngredi;    
    
    listaPedidos.appendChild(nomeIngrediCell)

    })
    
});








