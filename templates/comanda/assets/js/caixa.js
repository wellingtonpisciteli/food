const numMesaInput = document.getElementById('nummesa'); 

const enviarLancheBtns = document.querySelectorAll('.enviarLanche input[type="checkbox"]');

const pedidosDiv = document.getElementById('pedidos');
const listaPedidos = document.getElementById('listaPedidos');
const totalMesa = document.getElementById('totalMesa');
const btnDivCancelar = document.getElementById('btnDivCancelar');
const btnDiv = document.getElementById('btnDiv');
const totalTotal = document.getElementById('totalTotal');
const checkbox = document.querySelector('.check-toggle');

let cont = 0
let apagarLanche = false
let totalLanche = 0
let totalBebida = 0
let total = 0
let idIngrediente = 0
let idBebida = 0
let controleBebida = false
let controleCont = 0
const lanchesSelecionados = [];

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
  

const id_mesa = document.getElementById('id_mesa').value


enviarLancheBtns.forEach(btn => {
    btn.addEventListener('change', (event) => {
        controleBebida = true
        cont += 1
        controleCont += 1

        console.log('====================');
        console.log('🚀 Início dos dados:');

        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

        // Captura os dados do lanche e da mesa
        const valorLanche = form.querySelector('input[name="valor_lanche"]').value;
        const id_lanche = form.querySelector('input[name="id_lanche"]').value;
        const id_cardapio = form.querySelector('input[name="idCardapio"]').value;

        // Se ainda não estiver no array, adiciona
        if (!lanchesSelecionados.includes(id_lanche)) {
            lanchesSelecionados.push(id_lanche);
            total += parseFloat(valorLanche);
        } else {
            // Remove o id_lanche do array se desmarcar
            const index = lanchesSelecionados.indexOf(id_lanche);
            if (index > -1) {
                lanchesSelecionados.splice(index, 1);
                total -= parseFloat(valorLanche);
            }
        }
        
        let totalCell = document.querySelector('#totalMesa span'); // Verifica se já existe um <span> no totalMesa

        if (!totalCell) {
            // Se não existir, cria um novo elemento
            totalCell = document.createElement('span');
            totalMesa.appendChild(totalCell);
        }

        totalCell.textContent = `$${total.toFixed(2)}`;

        // Cria inputs ocultos para envio no formulário
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
        
        if (id_lanche) {
            const inputAdicional = form.querySelector('input[name="id_adicional"]');
            if (inputAdicional) { 
                const id_adicional = inputAdicional.value;

                if (id_lanche === id_adicional) {
                    const idAdicionalInput = document.createElement('input');
                    idAdicionalInput.type = 'hidden';
                    idAdicionalInput.name = 'id_adicional[]';
                    idAdicionalInput.value = id_adicional;

                    pedidosDiv.appendChild(idAdicionalInput);
                    console.log(idAdicionalInput);
                } else {
                    console.log("diferentes");
                }
            } else {
                console.log('input[name="id_adicional"] não existe nesse form');
            }
        }
    });
});

const enviarBebidaBtns = document.querySelectorAll('.enviarBebida input[type="checkbox"]');
const listaBebidas = document.getElementById('listaBebidas');


enviarBebidaBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
        cont += 1;
        controleCont += 1
        
        console.log('====================');
        console.log('🚀 Início dos dados:');
        
        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

        // Captura os dados da bebida
        const id_bebida = form.querySelector('input[name="id_bebida"]').value;
        const chave = form.querySelector('input[name="chave"]').value;

        // Captura o tamanho e o valor da bebida
        const select = form.querySelector('select#tamanho_valor');
        const [idTamanhoValorBebida, valorBebida, tamanhoBebida] = select.value.split('|'); // Divide pelo separador "|"

        // Se ainda não estiver no array, adiciona
        if (!lanchesSelecionados.includes(id_bebida)) {
            lanchesSelecionados.push(id_bebida);
            total += parseFloat(valorBebida);
        } else {
            // Remove o id_lanche do array se desmarcar
            const index = lanchesSelecionados.indexOf(id_bebida);
            if (index > -1) {
                lanchesSelecionados.splice(index, 1);
                total -= parseFloat(valorBebida);
            }
        }

        let totalCell = document.querySelector('#totalMesa span'); // Verifica se já existe um <span> no totalMesa

        if (!totalCell) {
            // Se não existir, cria um novo elemento
            totalCell = document.createElement('span');
            totalMesa.appendChild(totalCell);
        }

        totalCell.textContent = `$${total.toFixed(2)}`;

        // Cria inputs ocultos para envio no formulário
        const idBebidaInput = document.createElement('input');
        idBebidaInput.type = 'hidden';
        idBebidaInput.name = 'id_bebida[]';
        idBebidaInput.value = id_bebida;

        const idCardapioBebidaInput = document.createElement('input');
        idCardapioBebidaInput.type = 'hidden';
        idCardapioBebidaInput.name = 'chave[]';
        idCardapioBebidaInput.value = chave;

        console.log(idCardapioBebidaInput)

        pedidosDiv.appendChild(idBebidaInput);
        pedidosDiv.appendChild(idCardapioBebidaInput);
    });
});

function mostrarSubTotal(total) {

    const totalTexto = total.toFixed(2);

    return Swal.fire({
        title: '🧮 <span style="color: black;">SubTotal</span>',
        html: `
            <div style="text-align: left;">
                <p style="color: black;"><strong>Mesa: ${numMesaInput.value}</strong></p>
                <p style="margin-top: 10px; color: black;"><strong>SubTotal:</strong> <span style="color: #198754;"><strong>R$ ${totalTexto} </strong></span></p>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        cancelButtonText: '🛑 Manter',
        confirmButtonText: '✅ Cobrar',
        reverseButtons: false,
        cancelButtonColor: 'darkred',
        confirmButtonColor: 'blue'    
    });
}

function mostrarTotal(total) {

    const totalTexto = total.toFixed(2);

    return Swal.fire({
        title: '🧾 <span style="color: black;">Total</span>',
        html: `
            <div style="text-align: left;">
                <p style="color: black;"><strong>Mesa: ${numMesaInput.value}</strong></p>
                <p style="margin-top: 10px; color: black;"><strong>Total:</strong> <span style="color: #198754;"><strong>R$ ${totalTexto} </strong></span></p>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        cancelButtonText: '🛑 Manter',
        confirmButtonText: '✅ Cobrar',
        reverseButtons: false,
        cancelButtonColor: 'darkred',
        confirmButtonColor: 'blue'    
    });
}

btnDiv.addEventListener("click", (e) => {
        e.preventDefault();
        mostrarTotal(parseFloat(totalTotal.value))
            .then((result) => {
                if (result.isConfirmed) {        
                    document.getElementById('pedidos').submit();              
                } else {
                    console.log("Envio cancelado");
                }
            });
})

btnDivCancelar.addEventListener('click', (e) => {

        e.preventDefault();
        mostrarSubTotal(total)
            .then((result) => {
                if (result.isConfirmed) {        
                    document.getElementById('pedidos').submit();              
                } else {
                    console.log("Envio cancelado");
                }
            });

        const controleTotal = document.createElement('input');
        controleTotal.type = 'hidden';
        controleTotal.name = 'subtotal';
        controleTotal.value = 'subtotal';

        pedidosDiv.appendChild(controleTotal);
    
});











