window.onload = function() {

    const botoesAdicionarBebida = document.querySelectorAll('.enviarBebida input[type="button"]');
    const novoTotal = document.getElementById('novoTotal');
    const total = document.getElementById('total');
    const valorBebida = document.getElementById('valor-bebida');

    let totalInicial = total.value - valorBebida.value

    botoesAdicionarBebida.forEach(function(botao) {
        botao.addEventListener('click', function() {

            const linha = botao.closest('tr');

            const bebida = linha.querySelector('input[name="bebida"]').value; 
            const selectTamanho = linha.querySelector('select#tamanho_valor');
            const valorTamanho = selectTamanho.value; 
            const [idTamanhoValor, preco, tamanho] = valorTamanho.split('|');
            const idCardapio = linha.querySelector('input[name="idCardapio"]').value;

            novoTotal.value = Number(totalInicial) + Number(preco);
            
            document.getElementById('nome-bebida').innerHTML = bebida;
            document.getElementById('valor-bebida').innerHTML = preco + ",00"; 
            document.getElementById('tamanho-bebida').innerHTML = tamanho; 
            document.getElementById('idCardapio_bebida').value = idCardapio;
            document.getElementById('idTamanhoValor').value = idTamanhoValor;
        });
    });

    const apagarPedidos = document.querySelectorAll('.apagarPedido');

    apagarPedidos.forEach(apagarPedido => {
        apagarPedido.addEventListener('click', (event) => {
        event.preventDefault(); 

        Swal.fire({
            title: 'Apagar?',
            text: 'Deseja apagar este lanche?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                novoTotal.value = totalInicial;
                document.getElementById('apagar').value = 'preenchido';
                document.getElementById('pedidos').submit();
            } else {
                console.log('Cancelado');
            }
        });
        });
    });

}