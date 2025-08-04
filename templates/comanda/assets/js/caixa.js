const enviarLancheBtns = document.querySelectorAll('.enviarLanche input[type="checkbox"]');
const pedidosDiv = document.getElementById('pedidos');
const totalMesa = document.getElementById('totalMesa');
const btnDivCancelar = document.getElementById('btnDivCancelar');
const btnDiv = document.getElementById('btnDiv');
const totalTotal = document.getElementById('totalTotal');
const mesa = document.getElementById('nummesa');
const lanches = document.querySelectorAll('.lanches')
const bebidas = document.querySelectorAll('.bebidas')
const cliente = document.getElementById('controleDestino');


let cont = 0
let contBebidas = 0

lanches.forEach(function(lanche){
    cont += 1
    console.log(cont)
})

bebidas.forEach(function(bebidas){
    contBebidas += 1
    console.log(contBebidas)
})

let total = 0;
const lanchesSelecionados = [];
const bebidasSelecionados = [];

let totLanches = lanchesSelecionados.length
let totBebidas = bebidasSelecionados.length

let tot = totLanches + totBebidas;

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

const id_mesa = document.getElementById('id_mesa').value;

enviarLancheBtns.forEach(btn => {
    btn.addEventListener('change', () => {

        const form = btn.closest('tr');
        const valorLanche = form.querySelector('input[name="valor_lanche"]').value;
        const id_lanche = form.querySelector('input[name="id_lanche"]').value;
        const id_cardapio = form.querySelector('input[name="idCardapio"]').value;

        if (!lanchesSelecionados.includes(id_lanche)) {

            lanchesSelecionados.push(id_lanche);
            total += parseFloat(valorLanche);

            const idLancheInput = document.createElement('input');
            idLancheInput.type = 'hidden';
            idLancheInput.name = 'id_lanche[]';
            idLancheInput.value = id_lanche;

            const idCardapioInput = document.createElement('input');
            idCardapioInput.type = 'hidden';
            idCardapioInput.name = 'id_cardapio[]';
            idCardapioInput.value = id_cardapio;

            pedidosDiv.appendChild(idLancheInput);
            pedidosDiv.appendChild(idCardapioInput);

        } else {
            const index = lanchesSelecionados.indexOf(id_lanche);
            if (index > -1) {
                lanchesSelecionados.splice(index, 1);
                total -= parseFloat(valorLanche);

                const inputsToRemove = pedidosDiv.querySelectorAll('input[type="hidden"]');
                inputsToRemove.forEach(input => {
                    if (
                        (input.name === 'id_lanche[]' && input.value === id_lanche) ||
                        (input.name === 'id_cardapio[]' && input.value === id_cardapio)
                    ) {
                        input.remove();
                    }
                });
            }
        }

        let totalCell = document.querySelector('#totalMesa span');
        if (!totalCell) {
            totalCell = document.createElement('span');
            totalMesa.appendChild(totalCell);
        }
        totalCell.textContent = `$${total.toFixed(2)}`;

        const inputAdicional = form.querySelector('input[name="id_adicional"]');
        if (inputAdicional) {
            const id_adicional = inputAdicional.value;
            if (id_lanche === id_adicional) {
                const idAdicionalInput = document.createElement('input');
                idAdicionalInput.type = 'hidden';
                idAdicionalInput.name = 'id_adicional[]';
                idAdicionalInput.value = id_adicional;
                pedidosDiv.appendChild(idAdicionalInput);
            }
        }
    });
});

const enviarBebidaBtns = document.querySelectorAll('.enviarBebida input[type="checkbox"]');

enviarBebidaBtns.forEach(btn => {
    btn.addEventListener('click', () => {

        const form = btn.closest('tr');
        const id_bebida = form.querySelector('input[name="id_bebida"]').value;
        const chave = form.querySelector('input[name="chave"]').value;
        const select = form.querySelector('select#tamanho_valor');
        const valorBebida = parseFloat(select.value);

        if (!bebidasSelecionados.includes(id_bebida)) {
            bebidasSelecionados.push(id_bebida);
            total += parseFloat(valorBebida);

            const idBebidaInput = document.createElement('input');
            idBebidaInput.type = 'hidden';
            idBebidaInput.name = 'id_bebida[]';
            idBebidaInput.value = id_bebida;

            const idCardapioBebidaInput = document.createElement('input');
            idCardapioBebidaInput.type = 'hidden';
            idCardapioBebidaInput.name = 'chave[]';
            idCardapioBebidaInput.value = chave;

            pedidosDiv.appendChild(idBebidaInput);
            pedidosDiv.appendChild(idCardapioBebidaInput);
        } else {
            const index = bebidasSelecionados.indexOf(id_bebida);
            if (index > -1) {
                bebidasSelecionados.splice(index, 1);
                total -= parseFloat(valorBebida);

                const inputsToRemove = pedidosDiv.querySelectorAll('input[type="hidden"]');
                inputsToRemove.forEach(input => {
                    if (
                        (input.name === 'id_bebida[]' && input.value === id_bebida) ||
                        (input.name === 'chave[]' && input.value === chave)
                    ) {
                        input.remove();
                    }
                });
            }
        }

        let totalCell = document.querySelector('#totalMesa span');
        if (!totalCell) {
            totalCell = document.createElement('span');
            totalMesa.appendChild(totalCell);
        }
        totalCell.textContent = `$${total.toFixed(2)}`;
    });
});

function mostrarSubTotal(total) {
    const totalTexto = total.toFixed(2);
    const numMesa = document.getElementById('nummesa').value;

    return Swal.fire({
        title: '🧮 <span style="color: black;">SubTotal</span>',
        html: `
            <div style="text-align: left;">
                <p style="margin-top: 10px; color: black;"><strong>SubTotal:</strong> <span style="color: #198754;"><strong>R$ ${totalTexto} </strong></span></p>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        cancelButtonText: '🛑 Manter',
        confirmButtonText: '✅ Cobrar',
        reverseButtons: false,
        cancelButtonColor: 'darkred',
        confirmButtonColor: 'darkblue'
    });
}

function mostrarTotal(total) {
    const totalTexto = total.toFixed(2);
    const numMesa = document.getElementById('nummesa').value;

    return Swal.fire({
        title: '🧾 <span style="color: black;">Total</span>',
        html: `
            <div style="text-align: left;">
                <p style="margin-top: 10px; color: black;"><strong>Total:</strong> <span style="color: #198754;"><strong>R$ ${totalTexto} </strong></span></p>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        cancelButtonText: '🛑 Manter',
        confirmButtonText: '✅ Cobrar',
        reverseButtons: false,
        cancelButtonColor: 'darkred',
        confirmButtonColor: 'darkblue'
    });
}

btnDiv.addEventListener("click", (e) => {
    e.preventDefault();
    mostrarTotal(parseFloat(totalTotal.value)).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('pedidos').submit();
        } else {
            console.log("Envio cancelado");
        }
    });

    if(cliente){
        const controleDestino = document.createElement('input');
        controleDestino.type = 'hidden';
        controleDestino.name = 'controleDestino';
        controleDestino.value = 'controleDestino';

        console.log(controleDestino);

        pedidosDiv.appendChild(controleDestino);
    }
});

btnDivCancelar.addEventListener('click', (e) => {
    e.preventDefault();

    if (lanchesSelecionados.length === 0 && bebidasSelecionados.length === 0){
        Swal.fire({
            title: '<span style="color: black;">Vazio!</span>',
            text: 'Selecione um item',
            icon: 'info',
            confirmButtonText: 'OK',
            confirmButtonColor: 'darkblue'  
        });
        return
    }

    mostrarSubTotal(total).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('pedidos').submit();
        } else {
            console.log("Envio cancelado");
        }
    });

    const subtotalInput = document.querySelector('input[name="subtotal"]');

    if (lanchesSelecionados.length === cont && bebidasSelecionados.length === contBebidas) {
        console.log("Total");

        if (subtotalInput) {
            subtotalInput.remove();
        }
    } else {
        if (!subtotalInput) {
            const controleTotal = document.createElement('input');
            controleTotal.type = 'hidden';
            controleTotal.name = 'subtotal';
            controleTotal.value = 'subtotal';

            console.log(controleTotal);

            pedidosDiv.appendChild(controleTotal);
        }
    }

    if(cliente){
        const controleDestino = document.createElement('input');
        controleDestino.type = 'hidden';
        controleDestino.name = 'controleDestino';
        controleDestino.value = 'controleDestino';

        console.log(controleDestino);

        pedidosDiv.appendChild(controleDestino);
    }
    
});