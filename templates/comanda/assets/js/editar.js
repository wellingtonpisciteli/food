// Aguardar o carregamento completo da página
window.onload = function() {

  const nummesa = document.getElementById('nummesa');
  const mesa = document.querySelector('input[name="mesa"]')
  const controleMesa = document.querySelectorAll('input[name="controleMesa"]');

  controleMesa.forEach(num=>{
    console.log(num.value)
  })

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
  

  const botoesAdicionar = document.querySelectorAll('.enviarLanche input[type="button"]');

  botoesAdicionar.forEach(function(botao) {
    botao.addEventListener('click', function() {

      const linha = botao.closest('tr'); 
      const lanche = linha.querySelector('input[name="lanche"]').value; 
      const preco = linha.querySelector('input[name="valor_lanche"]').value; 
      const detalhes = linha.querySelector('input[name="detalhesLanche"]').value;
  
      document.getElementById('nome_lanche').value = lanche;
      document.getElementById('valor_lanche').value = preco;
      document.getElementById('detalhes_lanche').value = detalhes;

    });
  });

};