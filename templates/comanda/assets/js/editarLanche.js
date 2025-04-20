// Aguardar o carregamento completo da página
window.onload = function() {

  const nummesa = document.getElementById('nummesa');
  const mesa = document.querySelector('input[name="mesa"]')
  const controleMesa = document.querySelectorAll('input[name="controleMesa"]');

  nummesa.value = mesa.value

  if (mesa) {
      mesa.addEventListener('input', () => {
        if(mesa.value == nummesa.value){
          console.log("mesas iguais")
        }else{
          controleMesa.forEach(num => {
            if (mesa.value == num.value) {
                Swal.fire({
                    title: 'Mesa inválida',
                    text: 'Escolha outro número',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }
          })
        }
      });
  }
  

  const botoesAdicionar = document.querySelectorAll('.enviarLanche input[type="button"]');
  const novoTotal = document.getElementById('novoTotal');
  const total = document.getElementById('total');
  const valorLanche = document.getElementById('valor_lanche');

  let totalInicial = total.value - valorLanche.value

  botoesAdicionar.forEach(function(botao) {
    botao.addEventListener('click', function() {

      const linha = botao.closest('tr'); 
      const lanche = linha.querySelector('input[name="lanche"]').value; 
      const preco = linha.querySelector('input[name="valor_lanche"]').value; 
      const detalhes = linha.querySelector('input[name="detalhesLanche"]').value;

      novoTotal.value = Number(totalInicial) + Number(preco);
  
      document.getElementById('nome_lanche').value = lanche;
      document.getElementById('valor_lanche').value = preco;
      document.getElementById('detalhes_lanche').value = detalhes;

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