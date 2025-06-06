const buttons = document.querySelectorAll(".btnAbrirAberto"); 
const mesaComanda = document.querySelectorAll(".mesaAberta"); 
const tablePedido = document.querySelectorAll(".tablePedido"); 
const mesaButtonsContainer = document.getElementById("mesaButtonsContainer");
const btnMostrarMesas = document.querySelector(".btnMostrarMesas"); 
const botoesExcluir = document.querySelectorAll(".excluir-mesa");
const botoesAbrirMesa = document.querySelectorAll(".abrir-mesa");
const controleMesa = document.querySelectorAll('input[name="controleMesa"]');

function verificarMesa(mesaFechada) {
    let mesaExiste = false;

    controleMesa.forEach(mesaAberta => {
        if (mesaAberta.value == mesaFechada) {
            mesaExiste = true;
        }
    });

    return mesaExiste;
}


// Inicializa o conteúdo da primeira mesa e tabela visíveis
if (buttons.length > 0) {
    const firstMesa = buttons[0].getAttribute("data-id-mesa");

    mesaComanda.forEach(item => {
        if (item.getAttribute("data-id-mesa") === firstMesa) {
            item.style.display = "block";  // Torna visível o conteúdo da primeira mesa
        } else {
            item.style.display = "none";   // Esconde o conteúdo das outras mesas
        }
    });

    tablePedido.forEach(item => {
        if (item.getAttribute("data-id-mesa") === firstMesa) {
            item.style.display = "block";  // Torna visível a tabela do pedido correspondente
        } else {
            item.style.display = "none";   // Esconde as tabelas de outros pedidos
        }
    });
}

// Itera sobre cada botão .btnAbrirAberto
buttons.forEach(button => {
    button.addEventListener("click", () => {
        const mesa = button.getAttribute("data-id-mesa");  // Obtém o id da mesa clicada

        // Itera sobre as divs de pedidos e as tabelas de pedidos
        mesaComanda.forEach(item => {
            if (item.getAttribute("data-id-mesa") === mesa) {
                item.style.display = "block";  // Torna visível o conteúdo da mesa correspondente
            } else {
                item.style.display = "none";   // Esconde o conteúdo de outras mesas
            }
        });

        tablePedido.forEach(item => {
            if (item.getAttribute("data-id-mesa") === mesa) {
                item.style.display = "block";  // Torna visível a tabela do pedido correspondente
            } else {
                item.style.display = "none";   // Esconde a tabela de outros pedidos
            }
        });
    });
});

// Evento para mostrar/ocultar os botões das mesas ao clicar no botão "Mostrar Todos"
btnMostrarMesas.addEventListener("click", function () {
    if (mesaButtonsContainer.style.display === "none" || mesaButtonsContainer.style.display === "") {
        mesaButtonsContainer.style.display = "flex";
        btnMostrarMesas.textContent = "Fechar Todos";

        // Remove azul inline para não conflitar com o darkred
        btnMostrarMesas.style.backgroundColor = 'darkred';
        btnMostrarMesas.style.color = 'white';

        // Remove qualquer classe que possa interferir
        btnMostrarMesas.classList.remove("btn-danger");
    } else {
        mesaButtonsContainer.style.display = "none";
        btnMostrarMesas.textContent = "Mostrar Todos";

        // Volta pro azul inline
        btnMostrarMesas.style.backgroundColor = "blue";
        btnMostrarMesas.style.color = "white";

        // Remove classe btn-danger só por garantia
        btnMostrarMesas.classList.remove("btn-danger");
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
            confirmButtonColor: 'darkred',
            cancelButtonColor: 'blue',
            confirmButtonText: 'Sim, excluir mesa!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});

botoesAbrirMesa.forEach(botao => {
    botao.addEventListener("click", function (e) {
        e.preventDefault();
        const url = this.getAttribute("data-url");
        const mesaFechada = this.getAttribute("data-mesa");

         // Verifica se a mesa já existe
        if (verificarMesa(mesaFechada)) {
            Swal.fire({
                title: 'Mesa inválida',
                text: 'Esta mesa já está aberta!',
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: 'blue'
            });
            return; 
        }

        Swal.fire({
            title: 'Tem certeza?',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, abrir mesa!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});


