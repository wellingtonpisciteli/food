const buttons = document.querySelectorAll(".btnAbrirAberto"); // Botões das mesas
const mesaComanda = document.querySelectorAll(".mesaAberta"); // Conteúdo das mesas
const tablePedido = document.querySelectorAll(".tablePedido"); // Conteúdo das tabelas de pedidos
const mesaButtonsContainer = document.getElementById("mesaButtonsContainer"); // Contêiner dos botões das mesas
const btnMostrarMesas = document.querySelector(".btnMostrarMesas"); // Botão "Mostrar Todos"
const botoesExcluir = document.querySelectorAll(".excluir-mesa");

// Inicializa o conteúdo da primeira mesa e tabela visíveis
if (buttons.length > 0) {
    const firstMesa = buttons[0].getAttribute("data-mesa");

    mesaComanda.forEach(item => {
        if (item.getAttribute("data-mesa") === firstMesa) {
            item.style.display = "block";  // Torna visível o conteúdo da primeira mesa
        } else {
            item.style.display = "none";   // Esconde o conteúdo das outras mesas
        }
    });

    tablePedido.forEach(item => {
        if (item.getAttribute("data-mesa") === firstMesa) {
            item.style.display = "block";  // Torna visível a tabela do pedido correspondente
        } else {
            item.style.display = "none";   // Esconde as tabelas de outros pedidos
        }
    });
}

// Itera sobre cada botão .btnAbrirAberto
buttons.forEach(button => {
    button.addEventListener("click", () => {
        const mesa = button.getAttribute("data-mesa");  // Obtém o número da mesa clicada

        // Itera sobre as divs de pedidos e as tabelas de pedidos
        mesaComanda.forEach(item => {
            if (item.getAttribute("data-mesa") === mesa) {
                item.style.display = "block";  // Torna visível o conteúdo da mesa correspondente
            } else {
                item.style.display = "none";   // Esconde o conteúdo de outras mesas
            }
        });

        tablePedido.forEach(item => {
            if (item.getAttribute("data-mesa") === mesa) {
                item.style.display = "block";  // Torna visível a tabela do pedido correspondente
            } else {
                item.style.display = "none";   // Esconde a tabela de outros pedidos
            }
        });
    });
});

// Evento para mostrar/ocultar os botões das mesas ao clicar no botão "Mostrar Todos"
btnMostrarMesas.addEventListener("click", function () {
    // Verifica se os botões das mesas estão visíveis ou não
    if (mesaButtonsContainer.style.display === "none" || mesaButtonsContainer.style.display === "") {
        // Torna os botões das mesas visíveis
        mesaButtonsContainer.style.display = "flex";

        // Muda o texto do botão para "Fechar Todos"
        btnMostrarMesas.textContent = "Fechar Todos";

        // Altera o estilo do botão para "btn-danger"
        btnMostrarMesas.classList.remove("btn-primary"); // Remove a classe btn-primary
        btnMostrarMesas.classList.add("btn-danger"); // Adiciona a classe btn-danger
    } else {
        // Esconde os botões das mesas
        mesaButtonsContainer.style.display = "none";

        // Muda o texto do botão para "Mostrar Todos"
        btnMostrarMesas.textContent = "Mostrar Todos";

        // Altera o estilo do botão para "btn-primary"
        btnMostrarMesas.classList.remove("btn-danger"); // Remove a classe btn-danger
        btnMostrarMesas.classList.add("btn-primary"); // Adiciona a classe btn-primary
    }
});

botoesExcluir.forEach(botao => {
    botao.addEventListener("click", function (e) {
        e.preventDefault();
        const url = this.getAttribute("data-url");

        Swal.fire({
            title: 'Tem certeza?',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});


document.querySelectorAll('.editar-mesa').forEach(botao => {
    botao.addEventListener('click', function (e) {
        e.preventDefault();
        
        const mesaAtual = this.getAttribute('data-mesa-atual');

        Swal.fire({
            title: 'Editar Mesa',
            icon: 'question',
            html: `
                <input type="text" id="novaMesa" class="swal2-input" placeholder="Nova mesa">
            `,
            showCancelButton: true,
            confirmButtonText: 'Atualizar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const novaMesa = document.getElementById('novaMesa').value;
                if (!novaMesa) {
                    Swal.showValidationMessage('Você precisa informar a nova mesa');
                }
                return novaMesa;
            }
        }).then(result => {
            if (result.isConfirmed) {
                const novaMesa = result.value;
                const url = `${mesaAtual}/${novaMesa}`;
                window.location.href = url;
            }
        });
    });
});