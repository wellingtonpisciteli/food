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

};