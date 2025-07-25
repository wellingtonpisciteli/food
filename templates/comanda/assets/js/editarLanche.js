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
      const precoRaw = linha.querySelector('input[name="valor_lanche"]').value;
      const detalhes = linha.querySelector('input[name="detalhesLanche"]').value;
      const idCardapio = linha.querySelector('input[name="idCardapio"]').value;

      const preco = parseFloat(precoRaw.replace(',', '.'));

      const valorFormatado = preco.toLocaleString('pt-BR', {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
      });
      
      document.getElementById('nome-lanche').innerHTML = lanche;
      document.getElementById('valor-lanche').innerHTML = `$${valorFormatado}`;
      document.getElementById('detalhes_lanche').value = detalhes;
      document.getElementById('id_cardapio').value = idCardapio;
    });
  });


  const apagarPedidos = document.querySelectorAll('.apagarPedido');

  apagarPedidos.forEach(apagarPedido => {
    apagarPedido.addEventListener('click', (event) => {
      event.preventDefault(); 

      Swal.fire({
        title: '<span style="color: black;">Apagar?</span>',
        text: 'Deseja apagar este lanche?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'darkred',
        cancelButtonColor: 'blue',
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
        confirmButtonColor: 'blue'
      });
      return;
    }

    Swal.fire({
      title: '<span style="color: black;">📋 Confirmar Edição?</span>',
      html: `
        <div style="text-align: left;">
          <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
              <tr style="background-color: #f0f0f0;">
                <th style="padding: 6px 10px; text-align: left; color: black;">Item</th>
                <th style="padding: 6px 10px; text-align: right; color: black;">Valor</th>
                <th style="padding: 6px 10px; text-align: left; color: black;">Detalhes</th>
              </tr>
            </thead>
            <tbody>
              <tr style="border-bottom: 1px solid #ccc;">
                <td style="padding: 6px 10px; color: black;"><strong>🍔 ${nome}</strong></td>
                <td style="padding: 6px 10px; text-align: right; color: blue;"><strong>${valor}</strong></td>
                <td style="padding: 6px 10px; font-style: italic;">${detalhes || '-'}</td>
              </tr>
            </tbody>
          </table>
        </div>
      `,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: '✅ Confirmar',
      cancelButtonText: '🛑 Cancelar',
      reverseButtons: true,
      confirmButtonColor: 'blue',      // azul para confirmar
      cancelButtonColor: 'darkred'     // vermelho escuro para cancelar
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit(); // Envia o formulário
      }
    });
  });

};