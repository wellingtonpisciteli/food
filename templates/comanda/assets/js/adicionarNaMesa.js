const numMesaInput = document.getElementById('nummesa'); 

const enviarLancheBtns = document.querySelectorAll('.enviarLanche input[type="button"]');

const pedidosDiv = document.getElementById('pedidos');
const listaPedidos = document.getElementById('listaPedidos');
const comandaMesa = document.getElementById('comandaMesa');
const totalMesa = document.getElementById('totalMesa');
const btnDivCancelar = document.getElementById('btnDivCancelar');
const btnDiv = document.getElementById('btnDiv');
const cliente = document.getElementById('cliente');
const tipoControle = document.getElementById('tipoControle');


let cont = 0
let apagarLanche = false
let totalLanche = 0
let totalBebida = 0
let total = 0
let idIngrediente = 0
let idBebida = 0
let controleBebida = false
let controleCont = 0

function getDataHoraAtual() {
    const agora = new Date();

    const ano = agora.getFullYear();
    const mes = String(agora.getMonth() + 1).padStart(2, '0');
    const dia = String(agora.getDate()).padStart(2, '0');

    const horas = String(agora.getHours()).padStart(2, '0');
    const minutos = String(agora.getMinutes()).padStart(2, '0');
    const segundos = String(agora.getSeconds()).padStart(2, '0');

    return `${ano}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;
}
  

const idIngredi = document.querySelectorAll('.idLancheNovo')
const idBebidaNova = document.querySelectorAll('.idBebidaNova')
const id_mesa = document.getElementById('id_mesa').value

let ultimoInput = idIngredi.length > 0 ? idIngredi[idIngredi.length - 1].value : null;
let ultimoInputBebida = idBebidaNova.length > 0 ? idBebidaNova[idBebidaNova.length - 1].value : null;

let proximoValor = parseInt(ultimoInput) || 0;
let proximoValorBebida = parseInt(ultimoInputBebida) || 0;


enviarLancheBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
        controleBebida = true
        cont += 1
        controleCont += 1
        proximoValor++;

        console.log('====================');
        console.log('🚀 Início dos dados:');
        console.log('idLanche:', proximoValor);

        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

        // Captura os dados do lanche e da mesa
        const nomeLanche = form.querySelector('input[name="lanche"]').value;
        const valorLanche = form.querySelector('input[name="valor_lanche"]').value;
        const detalhesLanche = form.querySelector('input[name="detalhesLanche"]').value;
        const mesaPedido = form.querySelector('input[name="mesa_pedido"]').value || numMesaInput.value; // Garante que o valor será capturado
        const idCardapio = form.querySelector('input[name="idCardapio"]').value;

        console.log(mesaPedido)

        // Verifica se o valor da mesa está presente
        if (!mesaPedido || mesaPedido < 0) {
            Swal.fire({
                title: '<span style="color: black;">Numero da Mesa?</span>',
                text: 'Selecione um número',
                icon: 'question',
                confirmButtonText: 'OK',
                confirmButtonColor: 'blue'  
            });
            cont = 0
            return;
        }

        // Cria uma nova linha na tabela do formulário
        const row = document.createElement('tr');

        const nomeLancheCell = document.createElement('th');
        nomeLancheCell.className = 'bg-light';
        nomeLancheCell.style = 'color: black';
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
        obsLancheBtnApagar.style = 'border-radius: 2px; background-color: darkred; border: black; width: 30px;'
        obsLancheBtnApagar.innerHTML = '<i class="fa-solid fa-trash"></i>';
        obsLancheBtnApagar.onclick = () => {

            row.remove(); // Remove a linha inteira do DOM

            // Coloca os inputs ocultos em um array
            const hiddenInputs = [detalhesLancheInput, mesaInput, idLancheInput, idCardapioInput];

            // Itera sobre o array para remover os inputs
            hiddenInputs.forEach(function (input) {
                input.remove();
            });

            total -= parseFloat(valorLanche);
            totalCell.textContent = `$${total.toFixed(2)}`;

            controleCont -= 1
        };

        const obsLancheCell = document.createElement('td');
        obsLancheCell.className = 'text-center align-middle bg-light';
        obsLancheCell.appendChild(obsLancheBtnApagar);
        row.appendChild(obsLancheCell);

        if (cont == 1) {
            if (!cliente){
                const mesaCell = document.createElement('span');
                mesaCell.textContent = "Comanda: " + mesaPedido; // Adiciona o valor da mesa
                comandaMesa.appendChild(mesaCell);
            }else{
                const mesaCell = document.createElement('span');
                mesaCell.textContent = "Cliente: " + cliente.value; // Adiciona o valor da mesa
                comandaMesa.appendChild(mesaCell);

                const controleDestino = document.createElement('input');
                controleDestino.type = 'hidden';
                controleDestino.name = 'controleDestino';
                controleDestino.value = tipoControle.value;

                console.log(controleDestino)

                pedidosDiv.appendChild(controleDestino);
            }    
        }

        // Adiciona a nova linha à tabela
        listaPedidos.appendChild(row);

        // Cria inputs ocultos para envio no formulário
        const mesaInput = document.createElement('input');
        mesaInput.type = 'hidden';
        mesaInput.name = 'mesa[]';
        mesaInput.value = mesaPedido;

        console.log(mesaInput)

        const id_mesaInput = document.createElement('input');
        id_mesaInput.type = 'hidden';
        id_mesaInput.name = 'id_mesa[]';
        id_mesaInput.value = id_mesa;

        const idLancheInput = document.createElement('input');
        idLancheInput.type = 'hidden';
        idLancheInput.name = 'id_lanche[]';
        idLancheInput.value = proximoValor;

        const detalhesLancheInput = document.createElement('input');
        detalhesLancheInput.type = 'hidden';
        detalhesLancheInput.name = 'detalhes_lanche[]';
        detalhesLancheInput.value = detalhesLanche;

        const idCardapioInput = document.createElement('input');
        idCardapioInput.type = 'hidden';
        idCardapioInput.name = 'idCardapioLanche[]';
        idCardapioInput.value = idCardapio;

        pedidosDiv.appendChild(mesaInput);
        pedidosDiv.appendChild(id_mesaInput);
        pedidosDiv.appendChild(idLancheInput);
        pedidosDiv.appendChild(detalhesLancheInput);
        pedidosDiv.appendChild(idCardapioInput);

        form.querySelector('input[name="detalhesLanche"]').value = "";
    });
});

const enviarBebidaBtns = document.querySelectorAll('.enviarBebida input[type="button"]');
const listaBebidas = document.getElementById('listaBebidas');


enviarBebidaBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
        proximoValorBebida++
        cont += 1;
        controleCont += 1
        
        console.log('====================');
        console.log('🚀 Início dos dados:');
        console.log('Novo IdBebida:', proximoValorBebida);
        
        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

        // Captura os dados da bebida
        const nomeBebida = form.querySelector('input[name="bebida"]').value;
        const detalhesBebida = form.querySelector('input[name="detalhesBebida"]').value;
        const mesaPedido = form.querySelector('input[name="mesa_pedido"]').value || numMesaInput.value;
        const idMarcaBebida = form.querySelector('input[name="idMarca"]').value;

        // Captura o tamanho e o valor da bebida
        const select = form.querySelector('select#tamanho_valor');
        const [idTamanhoValorBebida, valorBebida, tamanhoBebida] = select.value.split('|'); // Divide pelo separador "|"

        // Verifica se o valor da mesa está presente
        if (!mesaPedido || mesaPedido < 0) {
            Swal.fire({
                title: '<span style="color: black;">Numero da Mesa?</span>',
                text: 'Selecione um número',
                icon: 'question',
                confirmButtonText: 'OK',
                confirmButtonColor: 'blue'  
            });
            cont = 0
            return;
        }

        // Cria uma nova linha na tabela do formulário
        const row = document.createElement('tr');

        const nomeBebidaCell = document.createElement('th');
        nomeBebidaCell.className = 'bg-light';
        nomeBebidaCell.style = 'color: black';
        nomeBebidaCell.textContent = `${nomeBebida} (${tamanhoBebida})`;
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
        obsBebidaBtnApagar.style = 'border-radius: 2px; background-color: darkred !important; border: black; width: 30px'
        obsBebidaBtnApagar.innerHTML = '<i class="fa-solid fa-trash"></i>';
        obsBebidaBtnApagar.onclick = () => {
            row.remove(); // Remove a linha inteira do DOM

            // Coloca os inputs ocultos em um array
            const hiddenInputs = [detalhesBebidaInput, mesaInput, idBebidaInput, idMarcaInput, idTamanhoInput];

            // Itera sobre o array para remover os inputs
            hiddenInputs.forEach(function (input) {
                input.remove();
            });

            total -= parseFloat(valorBebida);
            totalCell.textContent = `$${total.toFixed(2)}`;

            controleCont -= 1
        };

        const obsBebidaCell = document.createElement('td');
        obsBebidaCell.className = 'text-center align-middle bg-light';
        obsBebidaCell.appendChild(obsBebidaBtnApagar);
        row.appendChild(obsBebidaCell);

        if (cont == 1) {
            if (!cliente){
                const mesaCell = document.createElement('span');
                mesaCell.textContent = "Comanda: " + mesaPedido; // Adiciona o valor da mesa
                comandaMesa.appendChild(mesaCell);
            }else{
                const mesaCell = document.createElement('span');
                mesaCell.textContent = "Cliente: " + cliente.value; // Adiciona o valor da mesa
                comandaMesa.appendChild(mesaCell);

                const controleDestino = document.createElement('input');
                controleDestino.type = 'hidden';
                controleDestino.name = 'controleDestino';
                controleDestino.value = tipoControle.value;

                console.log(controleDestino)

                pedidosDiv.appendChild(controleDestino);
            }    
        }

        // Adiciona a nova linha à tabela
        listaBebidas.appendChild(row);

        // Cria inputs ocultos para envio no formulário
        const mesaInput = document.createElement('input');
        mesaInput.type = 'hidden';
        mesaInput.name = 'mesa_bebida[]';
        mesaInput.value = mesaPedido;

        const id_mesaInput = document.createElement('input');
        id_mesaInput.type = 'hidden';
        id_mesaInput.name = 'id_mesaBebida[]';
        id_mesaInput.value = id_mesa;

        const idBebidaInput = document.createElement('input');
        idBebidaInput.type = 'hidden';
        idBebidaInput.name = 'id_bebida[]';
        idBebidaInput.value = proximoValorBebida;

        console.log(idBebidaInput)

        const detalhesBebidaInput = document.createElement('input');
        detalhesBebidaInput.type = 'hidden';
        detalhesBebidaInput.name = 'detalhes_bebida[]';
        detalhesBebidaInput.value = detalhesBebida;

        const idMarcaInput = document.createElement('input');
        idMarcaInput.type = 'hidden';
        idMarcaInput.name = 'idMarcaBebida[]';
        idMarcaInput.value = idMarcaBebida;

        const idTamanhoInput = document.createElement('input');
        idTamanhoInput.type = 'hidden';
        idTamanhoInput.name = 'idTamanhoValorBebida[]';
        idTamanhoInput.value = idTamanhoValorBebida;

        pedidosDiv.appendChild(mesaInput);
        pedidosDiv.appendChild(id_mesaInput);
        pedidosDiv.appendChild(idBebidaInput);
        pedidosDiv.appendChild(detalhesBebidaInput);
        pedidosDiv.appendChild(idMarcaInput);
        pedidosDiv.appendChild(idTamanhoInput);       
    });
});

const enviarIngredienteBtns = document.querySelectorAll('.enviarIngrediente input[type="button"]');

enviarIngredienteBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
        cont += 1
        idIngrediente = proximoValor
        console.log('IdIngredi: ', idIngrediente);


        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

        // Captura os dados do lanche e da mesa
        const nomeIngrediente = form.querySelector('input[name="nomeIngrediente"]').value;
        const valorIngrediente = form.querySelector('input[name="valorIngrediente"]').value;
        const detalhesIngrediente = form.querySelector('input[name="detalhesIngrediente"]').value;
        const mesaPedido = form.querySelector('input[name="mesa_pedido"]').value || numMesaInput.value; // Garante que o valor será capturado
        const idIAdicional = form.querySelector('input[name="idAdicional"]').value;

        // Verifica se o valor da mesa está presente
        if (!mesaPedido || mesaPedido < 0) {
            Swal.fire({
                title: '<span style="color: black;">Numero da Mesa?</span>',
                text: 'Selecione um número',
                icon: 'question',
                confirmButtonText: 'OK',
                confirmButtonColor: 'blue'  
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
        obsIngredienteBtnApagar.style = 'border-radius: 2px; background-color: darkred; border: black; width: 30px;'
        obsIngredienteBtnApagar.innerHTML = '<i class="fa-solid fa-trash"></i>';
        obsIngredienteBtnApagar.onclick = () => {

            row.remove(); // Remove a linha inteira do DOM

            // Coloca os inputs ocultos em um array
            const hiddenInputs = [mesaInput, idIngredieteInput, tipoInput, idAdicionalInput];

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
            if (!cliente){
                const mesaCell = document.createElement('span');
                mesaCell.textContent = "Comanda: " + mesaPedido; // Adiciona o valor da mesa
                comandaMesa.appendChild(mesaCell);
            }else{
                const mesaCell = document.createElement('span');
                mesaCell.textContent = "Cliente: " + cliente.value; // Adiciona o valor da mesa
                comandaMesa.appendChild(mesaCell);

                const controleDestino = document.createElement('input');
                controleDestino.type = 'hidden';
                controleDestino.name = 'controleDestino';
                controleDestino.value = tipoControle.value;

                console.log(controleDestino)

                pedidosDiv.appendChild(controleDestino);
            }    
        }

        // Adiciona a nova linha à tabela
        listaPedidos.appendChild(row);

        // Cria inputs ocultos para envio no formulário
        const mesaInput = document.createElement('input');
        mesaInput.type = 'hidden';
        mesaInput.name = 'mesa_adicional[]';
        mesaInput.value = mesaPedido;

        const id_mesaInput = document.createElement('input');
        id_mesaInput.type = 'hidden';
        id_mesaInput.name = 'id_mesaAdicional[]';
        id_mesaInput.value = id_mesa;

        const idIngredieteInput = document.createElement('input');
        idIngredieteInput.type = 'hidden';
        idIngredieteInput.name = 'id_ingredi[]';
        idIngredieteInput.value = idIngrediente;

        const tipoInput = document.createElement('input');
        tipoInput.type = 'hidden';
        tipoInput.name = 'tipo[]';
        tipoInput.value = '+';

        const idAdicionalInput = document.createElement('input');
        idAdicionalInput.type = 'hidden';
        idAdicionalInput.name = 'idAdd[]';
        idAdicionalInput.value = idIAdicional;

        pedidosDiv.appendChild(mesaInput);
        pedidosDiv.appendChild(id_mesaInput);
        pedidosDiv.appendChild(idIngredieteInput);
        pedidosDiv.appendChild(tipoInput);
        pedidosDiv.appendChild(idAdicionalInput);
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
        idIngrediente = proximoValor
        console.log('Novo valor -ingrediente:', idIngrediente);

        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

        // Captura os dados do lanche e da mesa
        const nomeIngrediente = form.querySelector('input[name="nomeIngrediente"]').value;
        const valorIngrediente = 0;
        const detalhesIngrediente = form.querySelector('input[name="detalhesIngrediente"]').value;
        const mesaPedido = form.querySelector('input[name="mesa_pedido"]').value || numMesaInput.value; // Garante que o valor será capturado
        const idIAdicional = form.querySelector('input[name="idAdicional"]').value;

        // Verifica se o valor da mesa está presente
        if (!mesaPedido || mesaPedido < 0) {
            Swal.fire({
                title: '<span style="color: black;">Numero da Mesa?</span>',
                text: 'Selecione um número',
                icon: 'question',
                confirmButtonText: 'OK',
                confirmButtonColor: 'blue'  
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
        obsIngredienteBtnApagar.style = 'border-radius: 2px; background-color: darkred; border: black; width: 30px;'
        obsIngredienteBtnApagar.innerHTML = '<i class="fa-solid fa-trash"></i>';
        obsIngredienteBtnApagar.onclick = () => {

            row.remove(); // Remove a linha inteira do DOM

            // Coloca os inputs ocultos em um array
            const hiddenInputs = [mesaInput, idIngredieteInput, tipoInput, idAdicionalInput];

            // Itera sobre o array para remover os inputs
            hiddenInputs.forEach(function (input) {
                input.remove();
            });
        };

        const obsIngredienteCell = document.createElement('td');
        obsIngredienteCell.className = 'text-center align-middle bg-light';
        obsIngredienteCell.appendChild(obsIngredienteBtnApagar);
        row.appendChild(obsIngredienteCell);

        if (cont == 1) {
            if (!cliente){
                const mesaCell = document.createElement('span');
                mesaCell.textContent = "Comanda: " + mesaPedido; // Adiciona o valor da mesa
                comandaMesa.appendChild(mesaCell);
            }else{
                const mesaCell = document.createElement('span');
                mesaCell.textContent = "Cliente: " + cliente.value; // Adiciona o valor da mesa
                comandaMesa.appendChild(mesaCell);

                const controleDestino = document.createElement('input');
                controleDestino.type = 'hidden';
                controleDestino.name = 'controleDestino';
                controleDestino.value = tipoControle.value;

                console.log(controleDestino)

                pedidosDiv.appendChild(controleDestino);
            }    
        }

        // Adiciona a nova linha à tabela
        listaPedidos.appendChild(row);

        // Cria inputs ocultos para envio no formulário
        const mesaInput = document.createElement('input');
        mesaInput.type = 'hidden';
        mesaInput.name = 'mesa_adicional[]';
        mesaInput.value = mesaPedido;

        const id_mesaInput = document.createElement('input');
        id_mesaInput.type = 'hidden';
        id_mesaInput.name = 'id_mesaAdicional[]';
        id_mesaInput.value = id_mesa;

        const idIngredieteInput = document.createElement('input');
        idIngredieteInput.type = 'hidden';
        idIngredieteInput.name = 'id_ingredi[]';
        idIngredieteInput.value = idIngrediente;

        const tipoInput = document.createElement('input');
        tipoInput.type = 'hidden';
        tipoInput.name = 'tipo[]';
        tipoInput.value = '-';

        const idAdicionalInput = document.createElement('input');
        idAdicionalInput.type = 'hidden';
        idAdicionalInput.name = 'idAdd[]';
        idAdicionalInput.value = idIAdicional;

        pedidosDiv.appendChild(mesaInput);
        pedidosDiv.appendChild(id_mesaInput);
        pedidosDiv.appendChild(idIngredieteInput);
        pedidosDiv.appendChild(tipoInput);
        pedidosDiv.appendChild(idAdicionalInput);
    });
});

function mostrarConfirmacaoPedido(listaPedidos, listaBebidas, total, comandaMesa) {
    let linhasPedido = '';

    function gerarLinhas(trs, tipo) {
        trs.forEach(linha => {
            const nome = linha.children[0].textContent.trim();
            const valor = linha.children[1].textContent;
            const detalhes = linha.children[2].textContent;

            const nomeLower = nome.toLowerCase();
            let icone = '🍔'; // padrão: lanche

            if (tipo === 'bebida') {
                icone = '🥤';
            } else if (nome.startsWith('+') || nomeLower.includes('adicional') || nomeLower.includes('extra') || nomeLower.includes('acréscimo')) {
                icone = '➕';
            } else if (nome.startsWith('-')) {
                icone = '❌';
            }

            linhasPedido += `
                <tr style="border-bottom: 1px solid #ccc;">
                    <td style="padding: 6px 10px; color: black;">${icone} <strong> ${nome} </strong></td>
                    <td style="padding: 6px 10px; text-align: right; color: blue;"><strong>${valor}</strong></td>
                    <td style="padding: 6px 10px; font-style: italic;">${detalhes || '-'}</td>
                </tr>
            `;
        });
    }

    gerarLinhas(listaPedidos.querySelectorAll('tr'), 'lanche');
    gerarLinhas(listaBebidas.querySelectorAll('tr'), 'bebida');

    const mesa = comandaMesa.textContent || "Não definida";
    const totalTexto = total.toFixed(2);

    return Swal.fire({
        title: '📦 <span style="color: black;">Confirmar Pedido?</span>',
        html: `
            <div style="text-align: left;">
                <p style="color: black;"><strong>${mesa}</strong></p>
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="background-color: #f0f0f0;">
                            <th style="padding: 6px 10px; text-align: left; color: black;">Item</th>
                            <th style="padding: 6px 10px; text-align: right; color: black;">Valor</th>
                            <th style="padding: 6px 10px; text-align: left; color: black;">Detalhes</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${linhasPedido}
                    </tbody>
                </table>
                <p style="margin-top: 10px; color: black;"><strong>Total:</strong> <span style="color: #198754;"><strong>R$ ${totalTexto} </strong></span></p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Enviar Pedido',
        cancelButtonText: '🛑 Manter Pedido',
        reverseButtons: true,
        confirmButtonColor: 'blue',      // azul para confirmar
        cancelButtonColor: 'darkred',       
    });
}

btnDiv.addEventListener("click", (e) => {
    if (cont == 0) {
        e.preventDefault()
        Swal.fire({
            title: '<span style="color: black;">Comanda Vazia!</span>',
            text: 'Selecione um item do cárdapio.',
            icon: 'info',
            confirmButtonText: 'OK',
            confirmButtonColor: 'blue' 
        });
        return
    }

    if (controleCont === 0 ) {
        e.preventDefault()
        Swal.fire({
            title: '<span style="color: black;">Lanche em escolha?</span>',
            text: 'Selecione "Em ecolha..."',
            icon: 'info',
            confirmButtonText: 'OK',
            confirmButtonColor: 'blue' 
        });
        return
    }

    if (cont != 0) {
        e.preventDefault();
        mostrarConfirmacaoPedido(listaPedidos, listaBebidas, total, comandaMesa)
            .then((result) => {
                if (result.isConfirmed) {        
                    document.getElementById('pedidos').submit();              
                } else {
                    console.log("Envio cancelado");
                }
            });

        let hora = getDataHoraAtual();

        const horaInput = document.createElement('input');
        horaInput.type = 'hidden';
        horaInput.name = 'data_hora[]';
        horaInput.value = hora;

        const controleTotal = document.createElement('input');
        controleTotal.type = 'hidden';
        controleTotal.name = 'controleTotal';
        controleTotal.value = 'controleTotal';

        pedidosDiv.appendChild(horaInput);
        pedidosDiv.appendChild(controleTotal);
    }
})

btnDivCancelar.addEventListener('click', () => {
    // Usando Swal.fire para confirmação com a opção de "Sim" e "Não"
    Swal.fire({
        title: '<span style="color: black;">Cancelar?</span>',
        text: "Tem certeza que deseja cancelar?",
        icon: 'warning',
        showCancelButton: true,  // Exibe o botão de cancelamento
        confirmButtonText: 'Sim, cancelar',
        cancelButtonText: 'Não, manter',
        reverseButtons: true,
        confirmButtonColor: 'darkred',  // cor do botão confirmar
        cancelButtonColor: 'blue'       // cor do botão cancelar
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











