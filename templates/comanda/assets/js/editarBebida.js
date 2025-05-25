window.onload = function() {

    const botoesAdicionarBebida = document.querySelectorAll('.enviarBebida input[type="button"]');

    botoesAdicionarBebida.forEach(function(botao) {
        botao.addEventListener('click', function() {

            const linha = botao.closest('tr');

            const bebida = linha.querySelector('input[name="bebida"]').value; 
            const selectTamanho = linha.querySelector('select#tamanho_valor');
            const valorTamanho = selectTamanho.value; 
            const [idTamanhoValor, preco, tamanho] = valorTamanho.split('|');
            const idCardapio = linha.querySelector('input[name="idCardapio"]').value;
            
            document.getElementById('nome-bebida').innerHTML = bebida;
            document.getElementById('valor-bebida').innerHTML = preco + ",00"; 
            document.getElementById('tamanho-bebida').innerHTML = tamanho; 
            document.getElementById('idCardapio_bebida').value = idCardapio;
            document.getElementById('idTamanhoValor').value = idTamanhoValor;
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

        const nome = document.getElementById('nome-bebida').textContent.trim();
        const valor = document.getElementById('valor-bebida').textContent.trim();
        const tamanho = document.getElementById('tamanho-bebida').textContent.trim();

        if (!nome || !valor) {
        Swal.fire({
            title: '⚠️ Nenhuma bebida selecionada!',
            text: 'Escolha uma bebida antes de enviar.',
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
                    <th style="padding: 6px 10px; text-align: center;">Tamanho</th>
                    <th style="padding: 6px 10px; text-align: right;">Valor</th>
                </tr>
                </thead>
                <tbody>
                <tr style="border-bottom: 1px solid #ccc;">
                    <td style="padding: 6px 10px;">🥤 ${nome}</td>
                    <td style="padding: 6px 10px; text-align: center;">${tamanho}</td>
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

}