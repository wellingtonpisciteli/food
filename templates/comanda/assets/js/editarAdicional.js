// Aguardar o carregamento completo da página
window.onload = function() {

    const botoesAdicionarAdicionais = document.querySelectorAll('.enviarIngrediente input[type="button"]');
    const novoTotal = document.getElementById('novoTotal');
    const total = document.getElementById('total');
    const valorIngredi = document.getElementById('valor-adicional');

    let totalInicial = total.value - valorIngredi.value

    botoesAdicionarAdicionais.forEach(function(botao) {
        botao.addEventListener('click', function() {
            const linha = botao.closest('tr'); 
            
            const adicional = linha.querySelector('input[name="nomeIngrediente"]').value; 
            const preco = linha.querySelector('input[name="valorIngrediente"]').value; 
            const idCardapio = linha.querySelector('input[name="idAdicional"]').value;

            novoTotal.value = Number(totalInicial) + Number(preco);
            
            document.getElementById('nome-adicional').innerHTML = "+ "+ adicional;
            document.getElementById('valor-adicional').innerHTML = preco + ",00";
            document.getElementById('idCardapio_adicional').value = idCardapio;
            document.getElementById('tipo').value = "+";

            document.getElementById('nome-adicional').style.color = 'green';  
        });
    });

      const botoesRemoverAdicionais = document.querySelectorAll('.removerIngredienteBtns input[type="button"]');

      botoesRemoverAdicionais.forEach(function(botao) {
      botao.addEventListener('click', function() {
            const linha = botao.closest('tr');  

            const adicional = linha.querySelector('input[name="nomeIngrediente"]').value;
            const idCardapio = linha.querySelector('input[name="idAdicional"]').value; 
            const preco = 0  
            
            novoTotal.value = totalInicial;
            
            document.getElementById('nome-adicional').innerHTML = "- "+ adicional;
            document.getElementById('valor-adicional').innerHTML = preco + ",00"; 
            document.getElementById('idCardapio_adicional').value = idCardapio;
            document.getElementById('tipo').value = "-";


            document.getElementById('nome-adicional').style.color = 'red';  
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

};