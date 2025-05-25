// Aguardar o carregamento completo da página
window.onload = function() {

    const botoesAdicionarAdicionais = document.querySelectorAll('.enviarIngrediente input[type="button"]');

    botoesAdicionarAdicionais.forEach(function(botao) {
        botao.addEventListener('click', function() {
            const linha = botao.closest('tr'); 
            
            const adicional = linha.querySelector('input[name="nomeIngrediente"]').value; 
            const preco = linha.querySelector('input[name="valorIngrediente"]').value; 
            const idCardapio = linha.querySelector('input[name="idAdicional"]').value;
            
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
                    document.getElementById('apagar').value = 'preenchido';
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

    const nome = document.getElementById('nome-adicional').textContent.trim();
    const valor = document.getElementById('valor-adicional').textContent.trim();
    const tipo = document.getElementById('tipo').value;
    const icone = tipo === '+' ? '➕' : '❌';
    const cor = tipo === '+' ? 'green' : 'red';

    if (!nome || !valor) {
      Swal.fire({
        title: '⚠️ Nenhum adicional selecionado!',
        text: 'Escolha um adicional antes de enviar.',
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
              </tr>
            </thead>
            <tbody>
              <tr style="border-bottom: 1px solid #ccc;">
                <td style="padding: 6px 10px; color: ${cor};">${icone} ${nome}</td>
                <td style="padding: 6px 10px; text-align: right; color: #0d6efd;"><strong>R$ ${valor}</strong></td>
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
        form.submit();
      }
    });
  });


};