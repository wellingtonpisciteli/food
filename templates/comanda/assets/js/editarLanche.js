// Aguardar o carregamento completo da página
window.onload = function() {

  const nummesa = document.getElementById('nummesa');
  const atualizarMesa = document.getElementById('atualizarMesa');
  const mesa = document.querySelector('input[name="mesa"]')
  const controleMesa = document.querySelectorAll('input[name="controleMesa"]');
  const idLanche = document.getElementById('id_lanche');

  nummesa.value = mesa.value

  if (mesa) {
      mesa.addEventListener('input', () => {
        if(mesa.value == nummesa.value){
          console.log("mesas iguais")
        }else{
          if (mesa.value != "") {
            atualizarMesa.value = 'preenchido'
            console.log(atualizarMesa.value)
          }else{
            atualizarMesa.value = 'naopreenchido'
            console.log(atualizarMesa.value)
          }
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
  let valorAdicional = 0
  let precoLanche = 0
  let controleTotal = false

  console.log(total)

  botoesAdicionar.forEach(function(botao) {
    botao.addEventListener('click', function() {
      controleTotal = true

      const linha = botao.closest('tr'); 

      const lanche = linha.querySelector('input[name="lanche"]').value; 
      const preco = linha.querySelector('input[name="valor_lanche"]').value; 
      const detalhes = linha.querySelector('input[name="detalhesLanche"]').value;
      const idCardapio = linha.querySelector('input[name="idCardapio"]').value;

      console.log(idCardapio)
      
      precoLanche = Number(preco)
      
      novoTotal.value = Number(totalInicial) + Number(preco) + Number(valorAdicional);

      document.getElementById('nome-lanche').innerHTML = lanche;
      document.getElementById('valor-lanche').innerHTML = preco;
      document.getElementById('detalhes_lanche').value = detalhes;
      document.getElementById('id_cardapio').value = idCardapio;
    });
  });

  const enviarIngredienteBtns = document.querySelectorAll('.enviarIngrediente input[type="button"]');
  const pedidosDiv = document.getElementById('pedidos');
  const listaPedidos = document.getElementById('listaPedidos');

  enviarIngredienteBtns.forEach(btn => {
      btn.addEventListener('click', (event) => {
        idIngrediente = idLanche.value

        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

          // Captura os dados do lanche e da mesa
        const nomeIngrediente = form.querySelector('input[name="nomeIngrediente"]').value;
        const valorIngrediente = form.querySelector('input[name="valorIngrediente"]').value;

        valorAdicional += Number(valorIngrediente)

        if (controleTotal == false) {
          novoTotal.value = Number(total.value) + Number(precoLanche) + Number(valorAdicional);
        }else{
          novoTotal.value = Number(totalInicial) + Number(precoLanche) + Number(valorAdicional);
        }
        
          // Cria uma nova linha na tabela do formulário
        const row = document.createElement('tr');

        const nomeIngredienteCell = document.createElement('th');
        nomeIngredienteCell.className = 'nome_pedidoTd';
        nomeIngredienteCell.style = 'color: green;'
        nomeIngredienteCell.textContent = "+ " + nomeIngrediente;
        row.appendChild(nomeIngredienteCell);

        const detalheIngredienteCell = document.createElement('td');
        detalheIngredienteCell.className = 'detalhes_pedidoTd';
        row.appendChild(detalheIngredienteCell);

        const valorIngredienteCell = document.createElement('td');
        valorIngredienteCell.className = 'valor_pedidoTd';
        valorIngredienteCell.style = 'color: blue; font-weight: bolder;'
        valorIngredienteCell.textContent = `$${parseFloat(valorIngrediente).toFixed(2)}`;
        row.appendChild(valorIngredienteCell);

        const obsIngredienteBtnApagar = document.createElement('button');
        obsIngredienteBtnApagar.className = 'text-center align-middle text-white';
        obsIngredienteBtnApagar.style = 'border-radius: 4px; background-color: #dc3545; border: #b02a37; width: 30px;'
        obsIngredienteBtnApagar.innerHTML = '<i class="fa-solid fa-trash"></i>';
        obsIngredienteBtnApagar.onclick = () => {

            row.remove(); // Remove a linha inteira do DOM

            // Coloca os inputs ocultos em um array
            const hiddenInputs = [nomeIngredieteInput, valorIngredienteInput];

            // Itera sobre o array para remover os inputs
            hiddenInputs.forEach(function (input) {
                input.remove();
            });

            novoTotal.value -= parseFloat(valorIngrediente);
            console.log(novoTotal)
        };

        const obsIngredienteCell = document.createElement('td');
        obsIngredienteCell.className = 'apagarPedidoTd';
        obsIngredienteCell.appendChild(obsIngredienteBtnApagar);
        row.appendChild(obsIngredienteCell);

        // Adiciona a nova linha à tabela
        listaPedidos.appendChild(row);

        const idIngredieteInput = document.createElement('input');
        idIngredieteInput.type = 'hidden';
        idIngredieteInput.name = 'id_ingredi[]';
        idIngredieteInput.value = idIngrediente;

        const mesaIngredieteInput = document.createElement('input');
        mesaIngredieteInput.type = 'hidden';
        mesaIngredieteInput.name = 'mesa[]';
        mesaIngredieteInput.value = mesa.value ;

        const nomeIngredieteInput = document.createElement('input');
        nomeIngredieteInput.type = 'hidden';
        nomeIngredieteInput.name = 'add_ingredi[]';
        nomeIngredieteInput.value = "+ " + nomeIngrediente;

        const valorIngredienteInput = document.createElement('input');
        valorIngredienteInput.type = 'hidden';
        valorIngredienteInput.name = 'valor_ingredi[]';
        valorIngredienteInput.value = valorIngrediente;

        pedidosDiv.appendChild(idIngredieteInput);
        pedidosDiv.appendChild(mesaIngredieteInput);
        pedidosDiv.appendChild(nomeIngredieteInput);
        pedidosDiv.appendChild(valorIngredienteInput);
      });
  });

  const removerIngredienteBtns = document.querySelectorAll('.removerIngredienteBtns input[type="button"]');

removerIngredienteBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
        idIngrediente = idLanche.value

        // Pega a linha relacionada ao botão
        const form = btn.closest('tr');

        // Captura os dados do lanche e da mesa
        const nomeIngrediente = form.querySelector('input[name="nomeIngrediente"]').value;
        const valorIngrediente = 0;

        // Cria uma nova linha na tabela do formulário
        const row = document.createElement('tr');

        const nomeIngredienteCell = document.createElement('th');
        nomeIngredienteCell.className = 'nome_pedidoTd';
        nomeIngredienteCell.style = 'color: red;'
        nomeIngredienteCell.textContent = "- " + nomeIngrediente;
        row.appendChild(nomeIngredienteCell);

        const detalheIngredienteCell = document.createElement('td');
        detalheIngredienteCell.className = 'detalhes_pedidoTd';
        row.appendChild(detalheIngredienteCell);

        const valorIngredienteCell = document.createElement('td');
        valorIngredienteCell.className = 'valor_pedidoTd';
        valorIngredienteCell.style = 'color: blue; font-weight: bolder;'
        valorIngredienteCell.textContent = `$${parseFloat(0).toFixed(2)}`;
        row.appendChild(valorIngredienteCell);

        const obsIngredienteBtnApagar = document.createElement('button');
        obsIngredienteBtnApagar.className = 'text-center align-middle text-white';
        obsIngredienteBtnApagar.style = 'border-radius: 4px; background-color: #dc3545; border: #b02a37; width: 30px;'
        obsIngredienteBtnApagar.innerHTML = '<i class="fa-solid fa-trash"></i>';
        obsIngredienteBtnApagar.onclick = () => {

            row.remove(); // Remove a linha inteira do DOM

            // Coloca os inputs ocultos em um array
            const hiddenInputs = [nomeIngredieteInput, valorIngredienteInput];

            // Itera sobre o array para remover os inputs
            hiddenInputs.forEach(function (input) {
                input.remove();
            });
        };

        const obsIngredienteCell = document.createElement('td');
        obsIngredienteCell.className = 'apagarPedidoTd';
        obsIngredienteCell.appendChild(obsIngredienteBtnApagar);
        row.appendChild(obsIngredienteCell);

        // Adiciona a nova linha à tabela
        listaPedidos.appendChild(row);

        // Cria inputs ocultos para envio no formulário
        const idIngredieteInput = document.createElement('input');
        idIngredieteInput.type = 'hidden';
        idIngredieteInput.name = 'id_ingredi[]';
        idIngredieteInput.value = idIngrediente;

        const mesaInput = document.createElement('input');
        mesaInput.type = 'hidden';
        mesaInput.name = 'mesa[]';
        mesaInput.value = mesa.value;

        const nomeIngredieteInput = document.createElement('input');
        nomeIngredieteInput.type = 'hidden';
        nomeIngredieteInput.name = 'add_ingredi[]';
        nomeIngredieteInput.value = '- ' + nomeIngrediente;

        const valorIngredienteInput = document.createElement('input');
        valorIngredienteInput.type = 'hidden';
        valorIngredienteInput.name = 'valor_ingredi[]';
        valorIngredienteInput.value = valorIngrediente;

        pedidosDiv.appendChild(idIngredieteInput);
        pedidosDiv.appendChild(mesaInput);
        pedidosDiv.appendChild(nomeIngredieteInput);
        pedidosDiv.appendChild(valorIngredienteInput);
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

  document.querySelector('#btnAbrirIngrediente').addEventListener('click', function () {
    var tabela = document.getElementById('adicionarTabelaIngrediente');
    tabela.classList.toggle('show');
  });

};