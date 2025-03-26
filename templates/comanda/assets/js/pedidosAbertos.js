const buttons = document.querySelectorAll(".btnAbrirAberto"); // Botões das mesas
const mesaComanda = document.querySelectorAll(".mesaAberta"); // Conteúdo das mesas
const tablePedido = document.querySelectorAll(".tablePedido"); // Conteúdo das tabelas de pedidos
const mesaButtonsContainer = document.getElementById("mesaButtonsContainer"); // Contêiner dos botões das mesas
const btnMostrarMesas = document.querySelector(".btnMostrarMesas"); // Botão "Mostrar Todos"

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
