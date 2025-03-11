const numMesaInput = document.getElementById('nummesa'); // Campo principal da mesa
const mesaInputs = document.querySelectorAll('input[name="mesa_pedido"]');
const controleMesa = document.querySelectorAll('input[name="controleMesa"]');

// Atualiza o valor de todos os campos "mesa_pedido" ao digitar no campo "nummesa"
if (numMesaInput) {
    numMesaInput.addEventListener('input', () => {
        controleMesa.forEach(num => {
            if (numMesaInput.value == num.value) {
                Swal.fire({
                    title: 'Mesa inválida',
                    text: 'Escolha outro número',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }
        })
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
let apagarLanche = false
let totalLanche = 0
let totalBebida = 0
let total = 0
// Recupera o valor de idLanche do localStorage, se existir, senão inicia com 0
let idLanche = localStorage.getItem('idLanche') ? parseInt(localStorage.getItem('idLanche')) : 0;
let idIngrediente = 0

enviarLancheBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
        cont += 1
        idLanche += 1
        console.log(idLanche)

        // Atualiza o idLanche no localStorage para garantir que ele não zere
        localStorage.setItem('idLanche', idLanche);

        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

        // Captura os dados do lanche e da mesa
        const nomeLanche = form.querySelector('input[name="lanche"]').value;
        const valorLanche = form.querySelector('input[name="valor_lanche"]').value;
        const detalhesLanche = form.querySelector('input[name="detalhesLanche"]').value;
        const mesaPedido = form.querySelector('input[name="mesa_pedido"]').value || numMesaInput.value; // Garante que o valor será capturado

        // Verifica se o valor da mesa está presente
        if (!mesaPedido || mesaPedido < 0) {
            Swal.fire({
                title: 'Número da Mesa!',
                text: 'Selecione um número',
                icon: 'info',
                confirmButtonText: 'OK'
            });
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

        const obsLancheBtnApagar = document.createElement('button');
        obsLancheBtnApagar.className = 'text-center align-middle text-white';
        obsLancheBtnApagar.style = 'border-radius: 4px; background-color: #dc3545; border: #b02a37; width: 30px;'
        obsLancheBtnApagar.innerHTML = '<i class="fa-solid fa-trash"></i>';
        obsLancheBtnApagar.onclick = () => {

            row.remove(); // Remove a linha inteira do DOM

            // Coloca os inputs ocultos em um array
            const hiddenInputs = [nomeLancheInput, valorInput, detalhesLancheInput, mesaInput, idLancheInput];

            // Itera sobre o array para remover os inputs
            hiddenInputs.forEach(function (input) {
                input.remove();
            });

            total -= parseFloat(valorLanche);
            totalCell.textContent = `$${total.toFixed(2)}`;
        };

        const obsLancheCell = document.createElement('td');
        obsLancheCell.className = 'text-center align-middle bg-light';
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

        const idLancheInput = document.createElement('input');
        idLancheInput.type = 'hidden';
        idLancheInput.name = 'id_lanche[]';
        idLancheInput.value = idLanche;

        console.log(idLancheInput)

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

        btnDiv.addEventListener("click", () => {
            const totalInput = document.createElement('input');
            totalInput.type = 'hidden';
            totalInput.name = 'total[]';
            totalInput.value = total;

            pedidosDiv.appendChild(totalInput);

        })

        pedidosDiv.appendChild(mesaInput);
        pedidosDiv.appendChild(idLancheInput);
        pedidosDiv.appendChild(nomeLancheInput);
        pedidosDiv.appendChild(valorInput);
        pedidosDiv.appendChild(detalhesLancheInput);

        form.querySelector('input[name="detalhesLanche"]').value = "";
    });
});

const enviarBebidaBtns = document.querySelectorAll('.enviarBebida input[type="button"]');
const listaBebidas = document.getElementById('listaBebidas');

enviarBebidaBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {

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
            Swal.fire({
                title: 'Número da Mesa!',
                text: 'Selecione um número',
                icon: 'info',
                confirmButtonText: 'OK'
            });
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
            hiddenInputs.forEach(function (input) {
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

        btnDiv.addEventListener("click", () => {
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

const enviarIngredienteBtns = document.querySelectorAll('.enviarIngrediente input[type="button"]');

enviarIngredienteBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
        cont += 1
        idIngrediente = idLanche

        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

        // Captura os dados do lanche e da mesa
        const nomeIngrediente = form.querySelector('input[name="nomeIngrediente"]').value;
        const valorIngrediente = form.querySelector('input[name="valorIngrediente"]').value;
        const detalhesIngrediente = form.querySelector('input[name="detalhesIngrediente"]').value;
        const mesaPedido = form.querySelector('input[name="mesa_pedido"]').value || numMesaInput.value; // Garante que o valor será capturado

        // Verifica se o valor da mesa está presente
        if (!mesaPedido || mesaPedido < 0) {
            Swal.fire({
                title: 'Número da Mesa!',
                text: 'Selecione um número',
                icon: 'info',
                confirmButtonText: 'OK'
            });
            cont = 0
            return;
        }

        // Cria uma nova linha na tabela do formulário
        const row = document.createElement('tr');

        const nomeIngredienteCell = document.createElement('th');
        nomeIngredienteCell.className = 'bg-light';
        nomeIngredienteCell.style = 'color: green;'
        nomeIngredienteCell.textContent = "+ " + nomeIngrediente;
        row.appendChild(nomeIngredienteCell);

        const valorIngredienteCell = document.createElement('td');
        valorIngredienteCell.className = 'text-center align-middle bg-light fw-bolder';
        valorIngredienteCell.style = 'color: blue;'
        valorIngredienteCell.textContent = `$${parseFloat(valorIngrediente).toFixed(2)}`;
        row.appendChild(valorIngredienteCell);

        let totalCell = document.querySelector('#totalMesa span'); // Verifica se já existe um <span> no totalMesa

        if (!totalCell) {
            // Se não existir, cria um novo elemento
            totalCell = document.createElement('span');
            totalMesa.appendChild(totalCell);
        }

        total += parseFloat(valorIngrediente);

        totalCell.textContent = `$${total.toFixed(2)}`;

        const detalheIngredienteCell = document.createElement('td');
        detalheIngredienteCell.className = 'text-center align-middle bg-light';
        detalheIngredienteCell.textContent = detalhesIngrediente;
        row.appendChild(detalheIngredienteCell);

        const obsIngredienteBtnApagar = document.createElement('button');
        obsIngredienteBtnApagar.className = 'text-center align-middle text-white';
        obsIngredienteBtnApagar.style = 'border-radius: 4px; background-color: #dc3545; border: #b02a37; width: 30px;'
        obsIngredienteBtnApagar.innerHTML = '<i class="fa-solid fa-trash"></i>';
        obsIngredienteBtnApagar.onclick = () => {

            row.remove(); // Remove a linha inteira do DOM

            // Coloca os inputs ocultos em um array
            const hiddenInputs = [nomeIngredieteInput, valorIngredienteInput, mesaInput, idIngredieteInput];

            // Itera sobre o array para remover os inputs
            hiddenInputs.forEach(function (input) {
                input.remove();
            });

            total -= parseFloat(valorIngrediente);
            totalCell.textContent = `$${total.toFixed(2)}`;
        };

        const obsIngredienteCell = document.createElement('td');
        obsIngredienteCell.className = 'text-center align-middle bg-light';
        // obsIngredienteCell.appendChild(obsLancheBtnEditar);
        obsIngredienteCell.appendChild(obsIngredienteBtnApagar);
        row.appendChild(obsIngredienteCell);

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

        const idIngredieteInput = document.createElement('input');
        idIngredieteInput.type = 'hidden';
        idIngredieteInput.name = 'id_ingredi[]';
        idIngredieteInput.value = idIngrediente;

        console.log(idIngredieteInput)

        const nomeIngredieteInput = document.createElement('input');
        nomeIngredieteInput.type = 'hidden';
        nomeIngredieteInput.name = 'add_ingredi[]';
        nomeIngredieteInput.value = "+ " + nomeIngrediente;

        const valorIngredienteInput = document.createElement('input');
        valorIngredienteInput.type = 'hidden';
        valorIngredienteInput.name = 'valor_ingredi[]';
        valorIngredienteInput.value = valorIngrediente;

        btnDiv.addEventListener("click", () => {
            const totalInput = document.createElement('input');
            totalInput.type = 'hidden';
            totalInput.name = 'total[]';
            totalInput.value = total;

            pedidosDiv.appendChild(totalInput);

        })

        pedidosDiv.appendChild(mesaInput);
        pedidosDiv.appendChild(nomeIngredieteInput);
        pedidosDiv.appendChild(idIngredieteInput);
        pedidosDiv.appendChild(valorIngredienteInput);
    });
});

document.querySelector('#btnAbrirIngrediente').addEventListener('click', function () {
    var tabela = document.getElementById('adicionarTabelaIngrediente');
    tabela.classList.toggle('show');
});


const removerIngredienteBtns = document.querySelectorAll('.removerIngredienteBtns input[type="button"]');

removerIngredienteBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
        cont += 1
        idIngrediente = idLanche

        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

        // Captura os dados do lanche e da mesa
        const nomeIngrediente = form.querySelector('input[name="nomeIngrediente"]').value;
        const valorIngrediente = 0;
        const detalhesIngrediente = form.querySelector('input[name="detalhesIngrediente"]').value;
        const mesaPedido = form.querySelector('input[name="mesa_pedido"]').value || numMesaInput.value; // Garante que o valor será capturado

        // Verifica se o valor da mesa está presente
        if (!mesaPedido || mesaPedido < 0) {
            Swal.fire({
                title: 'Número da Mesa!',
                text: 'Selecione um número',
                icon: 'info',
                confirmButtonText: 'OK'
            });
            cont = 0
            return;
        }

        // Cria uma nova linha na tabela do formulário
        const row = document.createElement('tr');

        const nomeIngredienteCell = document.createElement('th');
        nomeIngredienteCell.className = 'bg-light';
        nomeIngredienteCell.style = 'color: red;'
        nomeIngredienteCell.textContent = "- " + nomeIngrediente;
        row.appendChild(nomeIngredienteCell);

        const valorIngredienteCell = document.createElement('td');
        valorIngredienteCell.className = 'text-center align-middle bg-light fw-bolder';
        valorIngredienteCell.style = 'color: blue;'
        valorIngredienteCell.textContent = `$${parseFloat(0).toFixed(2)}`;
        row.appendChild(valorIngredienteCell);

        let totalCell = document.querySelector('#totalMesa span'); // Verifica se já existe um <span> no totalMesa

        if (!totalCell) {
            // Se não existir, cria um novo elemento
            totalCell = document.createElement('span');
            totalMesa.appendChild(totalCell);
        }

        total += parseFloat(valorIngrediente);

        totalCell.textContent = `$${total.toFixed(2)}`;

        const detalheIngredienteCell = document.createElement('td');
        detalheIngredienteCell.className = 'text-center align-middle bg-light';
        detalheIngredienteCell.textContent = detalhesIngrediente;
        row.appendChild(detalheIngredienteCell);

        const obsIngredienteBtnApagar = document.createElement('button');
        obsIngredienteBtnApagar.className = 'text-center align-middle text-white';
        obsIngredienteBtnApagar.style = 'border-radius: 4px; background-color: #dc3545; border: #b02a37; width: 30px;'
        obsIngredienteBtnApagar.innerHTML = '<i class="fa-solid fa-trash"></i>';
        obsIngredienteBtnApagar.onclick = () => {

            row.remove(); // Remove a linha inteira do DOM

            // Coloca os inputs ocultos em um array
            const hiddenInputs = [nomeIngredieteInput, valorIngredienteInput, mesaInput, idIngredieteInput];

            // Itera sobre o array para remover os inputs
            hiddenInputs.forEach(function (input) {
                input.remove();
            });

            // total -= parseFloat(valorIngrediente);
            // totalCell.textContent = `$${total.toFixed(2)}`;
        };

        const obsIngredienteCell = document.createElement('td');
        obsIngredienteCell.className = 'text-center align-middle bg-light';
        obsIngredienteCell.appendChild(obsIngredienteBtnApagar);
        row.appendChild(obsIngredienteCell);

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

        const idIngredieteInput = document.createElement('input');
        idIngredieteInput.type = 'hidden';
        idIngredieteInput.name = 'id_ingredi[]';
        idIngredieteInput.value = idIngrediente;

        console.log(idIngredieteInput)

        const nomeIngredieteInput = document.createElement('input');
        nomeIngredieteInput.type = 'hidden';
        nomeIngredieteInput.name = 'add_ingredi[]';
        nomeIngredieteInput.value = '- ' + nomeIngrediente;

        const valorIngredienteInput = document.createElement('input');
        valorIngredienteInput.type = 'hidden';
        valorIngredienteInput.name = 'valor_ingredi[]';
        valorIngredienteInput.value = valorIngrediente;

        btnDiv.addEventListener("click", () => {
            const totalInput = document.createElement('input');
            totalInput.type = 'hidden';
            totalInput.name = 'total[]';
            totalInput.value = total;

            pedidosDiv.appendChild(totalInput);

        })

        pedidosDiv.appendChild(mesaInput);
        pedidosDiv.appendChild(nomeIngredieteInput);
        pedidosDiv.appendChild(idIngredieteInput);
        pedidosDiv.appendChild(valorIngredienteInput);
    });
});

btnDiv.addEventListener("click", (e) => {
    if (cont == 0) {
        e.preventDefault()
        Swal.fire({
            title: 'Comanda Vazia!',
            text: 'Selecione um item do cárdapio.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
    }
})

btnDivCancelar.addEventListener('click', () => {
    // Usando Swal.fire para confirmação com a opção de "Sim" e "Não"
    Swal.fire({
        title: 'Cancelar?',
        text: "Tem certeza que deseja cancelar?",
        icon: 'warning',
        showCancelButton: true,  // Exibe o botão de cancelamento
        confirmButtonText: 'Sim, cancelar',
        cancelButtonText: 'Não, manter',
        reverseButtons: true  // Coloca os botões na ordem: "Sim, cancelar" e "Não, manter"
    }).then((result) => {
        if (result.isConfirmed) {
            // Se o usuário confirmar (clicar em "Sim, cancelar")
            location.reload();  // Recarregar a página
        } else {
            // Caso o usuário cancele, não faz nada
            console.log("Cancelado");
        }
    });
});











