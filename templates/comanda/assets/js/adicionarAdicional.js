const numMesaInput = document.getElementById('nummesa'); 
const pedidosDiv = document.getElementById('pedidos');
const listaPedidos = document.getElementById('listaPedidos');
const comandaMesa = document.getElementById('comandaMesa');
const totalMesa = document.getElementById('totalMesa');
const btnDivCancelar = document.getElementById('btnDivCancelar');
const btnDiv = document.getElementById('btnDiv');

let cont = 0
let total = 0
let idIngrediente = 0

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
  

const idIngredi = document.getElementById('idLancheNovo')
console.log(idIngredi)

const enviarIngredienteBtns = document.querySelectorAll('.enviarIngrediente input[type="button"]');

enviarIngredienteBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
        cont += 1
        idIngrediente = idIngredi.value
        console.log('IdIngredi: ', idIngrediente);


        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

        // Captura os dados do lanche e da mesa
        const nomeIngrediente = form.querySelector('input[name="nomeIngrediente"]').value;
        const valorIngrediente = form.querySelector('input[name="valorIngrediente"]').value;
        const detalhesIngrediente = form.querySelector('input[name="detalhesIngrediente"]').value;
        const mesaPedido = form.querySelector('input[name="mesa_pedido"]').value || numMesaInput.value; // Garante que o valor será capturado
        const idIAdicional = form.querySelector('input[name="idAdicional"]').value;
        console.log(mesaPedido)

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
            const hiddenInputs = [mesaInput, idIngredieteInput, tipoInput, idAdicionalInput];

            // Itera sobre o array para remover os inputs
            hiddenInputs.forEach(function (input) {
                input.remove();
            });

            total -= parseFloat(valorIngrediente);
            totalCell.textContent = `$${total.toFixed(2)}`;

            cont -= 1
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
        mesaInput.name = 'mesa_adicional[]';
        mesaInput.value = mesaPedido;

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
        pedidosDiv.appendChild(idIngredieteInput);
        pedidosDiv.appendChild(tipoInput);
        pedidosDiv.appendChild(idAdicionalInput);
    });
});

const removerIngredienteBtns = document.querySelectorAll('.removerIngredienteBtns input[type="button"]');

removerIngredienteBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
        cont += 1
        idIngrediente = idIngredi.value
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
            const hiddenInputs = [mesaInput, idIngredieteInput, tipoInput, idAdicionalInput];

            // Itera sobre o array para remover os inputs
            hiddenInputs.forEach(function (input) {
                input.remove();
            });

            cont -= 1
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
        mesaInput.name = 'mesa_adicional[]';
        mesaInput.value = mesaPedido;

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
        pedidosDiv.appendChild(idIngredieteInput);
        pedidosDiv.appendChild(tipoInput);
        pedidosDiv.appendChild(idAdicionalInput);
    });
});

function mostrarConfirmacaoAdicionais(listaPedidos, total, comandaMesa) {
    let linhasPedido = '';

    listaPedidos.querySelectorAll('tr').forEach(linha => {
        const nome = linha.children[0].textContent.trim();
        const valor = linha.children[1].textContent;
        const detalhes = linha.children[2].textContent;

        let icone = '🟢'; // padrão
        if (nome.startsWith('+')) icone = '➕';
        else if (nome.startsWith('-')) icone = '❌';

        linhasPedido += `
            <tr style="border-bottom: 1px solid #ccc;">
                <td style="padding: 6px 10px;">${icone} ${nome}</td>
                <td style="padding: 6px 10px; text-align: right; color: #0d6efd;"><strong>${valor}</strong></td>
                <td style="padding: 6px 10px; font-style: italic;">${detalhes || '-'}</td>
            </tr>
        `;
    });

    const mesa = comandaMesa.textContent || "Não definida";
    const totalTexto = total.toFixed(2);

    return Swal.fire({
        title: 'Confirmar Adicionais?',
        html: `
            <div style="text-align: left;">
                <p><strong>Mesa:</strong> ${mesa}</p>
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="background-color: #f0f0f0;">
                            <th style="padding: 6px 10px; text-align: left;">Adicional</th>
                            <th style="padding: 6px 10px; text-align: right;">Valor</th>
                            <th style="padding: 6px 10px; text-align: left;">Detalhes</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${linhasPedido}
                    </tbody>
                </table>
                <p style="margin-top: 10px;"><strong>Total:</strong> <span style="color: #198754;">R$ ${totalTexto}</span></p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Enviar Adicionais',
        cancelButtonText: '🛑 Cancelar',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-primary mx-2',
            cancelButton: 'btn btn-danger mx-2'
        },
        buttonsStyling: false
    });
}

btnDiv.addEventListener("click", (e) => {
    if (cont == 0) {
        e.preventDefault()
        Swal.fire({
            title: 'Comanda Vazia!',
            text: 'Selecione um item do cárdapio.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
        return
    }

    if (cont != 0) {
        e.preventDefault();
         mostrarConfirmacaoAdicionais(listaPedidos, total, comandaMesa).then((result) => {
            if (result.isConfirmed) {     
                document.getElementById('pedidos').submit();  
            } else {
                console.log("Envio cancelado");
            }
        });

        const controleTotal = document.createElement('input');
        controleTotal.type = 'hidden';
        controleTotal.name = 'controleTotal';
        controleTotal.value = 'controleTotal';

        pedidosDiv.appendChild(controleTotal);
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











