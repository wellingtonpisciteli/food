// Aguardar o carregamento completo da página
window.onload = function() {

  const lanches = document.querySelectorAll('.lanches')
  let cont = 0

  lanches.forEach(function(lanche){
    cont += 1
    console.log(cont)
  })

  const botoesAdicionar = document.querySelectorAll('.enviarLanche input[type="button"]');

  botoesAdicionar.forEach(function(botao) {
    botao.addEventListener('click', function() {

      const linha = botao.closest('tr'); 

      const lanche = linha.querySelector('input[name="lanche"]').value; 
      const preco = linha.querySelector('input[name="valor_lanche"]').value; 
      const detalhes = linha.querySelector('input[name="detalhesLanche"]').value;
      const idCardapio = linha.querySelector('input[name="idCardapio"]').value;
      
      document.getElementById('nome-lanche').innerHTML = lanche;
      document.getElementById('valor-lanche').innerHTML = preco;
      document.getElementById('detalhes_lanche').value = detalhes;
      document.getElementById('id_cardapio').value = idCardapio;
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
          if (cont === 1) {
            document.getElementById('id_cardapio').value = 999;
          }else {
            document.getElementById('apagar').value = 'preenchido';
          }
          document.getElementById('pedidos').submit();
        } else {
            console.log('Cancelado');
        }
      });
    });
  });

};