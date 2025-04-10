window.onload = function() {

    const botoesAdicionarBebida = document.querySelectorAll('.enviarBebida input[type="button"]');

    botoesAdicionarBebida.forEach(function(botao) {
    botao.addEventListener('click', function() {
        const linha = botao.closest('tr');   
        const bebida = linha.querySelector('input[name="bebida"]').value; 
        const selectTamanho = linha.querySelector('select#tamanho_valor');
        const valorTamanho = selectTamanho.value; 
        const [preco, tamanho] = valorTamanho.split('|');
        
        document.getElementById('nome_bebida').value = bebida;
        document.getElementById('valor_bebida').value = preco; 
        document.getElementById('tamanho_bebida').value = tamanho; 
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
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
            window.location.href = apagarPedido.getAttribute('href');
            } else {
            console.log('Cancelado');
            }
        });
        });
    });

}