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
            document.getElementById('apagar').value = 'preenchido999';
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

  const form = document.getElementById('pedidos');
  const btnEnviar = document.getElementById('btnEnviarPedido');

  btnEnviar.addEventListener('click', function (e) {
    e.preventDefault();

    const nome = document.getElementById('nome-lanche').textContent.trim();
    const valor = document.getElementById('valor-lanche').textContent.trim();
    const detalhes = document.getElementById('detalhes_lanche').value.trim();

    if (!nome || !valor) {
      Swal.fire({
        title: '⚠️ Nenhum lanche selecionado!',
        text: 'Escolha um lanche antes de enviar.',
        icon: 'info',
        confirmButtonText: 'OK',
        confirmButtonColor: '#0d6efd'
      });
      return;
    }

    Swal.fire({
      title: '📋 Confirmar Edição?',
      html: `
        <div style="text-align: left;">
          <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
              <tr style="background-color: #f0f0f0;">
                <th style="padding: 6px 10px; text-align: left;">Item</th>
                <th style="padding: 6px 10px; text-align: right;">Valor</th>
                <th style="padding: 6px 10px; text-align: left;">Detalhes</th>
              </tr>
            </thead>
            <tbody>
              <tr style="border-bottom: 1px solid #ccc;">
                <td style="padding: 6px 10px;">🍔 ${nome}</td>
                <td style="padding: 6px 10px; text-align: right; color: #0d6efd;"><strong>R$ ${valor}</strong></td>
                <td style="padding: 6px 10px; font-style: italic;">${detalhes || '-'}</td>
              </tr>
            </tbody>
          </table>
        </div>
      `,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: '✅ Enviar Pedido',
      cancelButtonText: '🛑 Cancelar',
      reverseButtons: true,
      customClass: {
        confirmButton: 'btn btn-primary mx-2',
        cancelButton: 'btn btn-danger mx-2'
      },
      buttonsStyling: false
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit(); // Envia o formulário
      }
    });
  });

};