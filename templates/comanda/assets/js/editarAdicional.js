// Aguardar o carregamento completo da página
window.onload = function() {

    const botoesAdicionarAdicionais = document.querySelectorAll('.enviarIngrediente input[type="button"]');

    botoesAdicionarAdicionais.forEach(function(botao) {
      botao.addEventListener('click', function() {
          const linha = botao.closest('tr');   
          const adicional = linha.querySelector('input[name="nomeIngrediente"]').value; 
          const preco = linha.querySelector('input[name="valorIngrediente"]').value; 
          
          document.getElementById('nome_adicional').value = "+ "+ adicional;
          document.getElementById('valor_adicional').value = preco; 

          document.getElementById('nome_adicional').style.color = 'green';  

      });
      });

      const botoesRemoverAdicionais = document.querySelectorAll('.removerIngredienteBtns input[type="button"]');

      botoesRemoverAdicionais.forEach(function(botao) {
      botao.addEventListener('click', function() {
          const linha = botao.closest('tr');   
          const adicional = linha.querySelector('input[name="nomeIngrediente"]').value; 
          const preco = 0   
          
          document.getElementById('nome_adicional').value = "- "+ adicional;
          document.getElementById('valor_adicional').value = preco; 

          document.getElementById('nome_adicional').style.color = 'red';  
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

};