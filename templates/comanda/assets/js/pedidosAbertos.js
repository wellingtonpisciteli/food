const buttons = document.querySelectorAll(".btnAbrirAberto"); 
const mesaComanda = document.querySelectorAll(".mesaAberta"); 
const tablePedido = document.querySelectorAll(".tablePedido"); 
const mesaButtonsContainer = document.getElementById("mesaButtonsContainer");
const btnMostrarMesas = document.querySelector(".btnMostrarMesas"); 
const botoesExcluir = document.querySelectorAll(".excluir-mesa");

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
        const mesa = button.getAttribute("data-id-mesa");  // Obtém o número da mesa clicada

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
        btnMostrarMesas.style.backgroundColor = "darkblue";
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
            title: '<span style="color: black;">Tem certeza?</span>',
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

const controleMesa = document.querySelectorAll('input[name="controleMesa"]');


document.querySelectorAll('.editar-mesa').forEach(botao => {
    botao.addEventListener('click', function (e) {
        e.preventDefault();
        
        const mesaAtual = this.getAttribute('data-mesa-atual');

        Swal.fire({
            title: '<span style="color: black;">Editar Mesa</span>',
            icon: 'question',
            html: `
                <input type="text" id="novaMesa" class="swal2-input" placeholder="Nova mesa">
            `,
            showCancelButton: true,
            confirmButtonColor: 'blue',
            cancelButtonColor: 'darkred',
            confirmButtonText: 'Atualizar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const novaMesa = document.getElementById('novaMesa').value;
                if (!novaMesa) {
                    Swal.showValidationMessage('Você precisa informar a nova mesa');
                }
      
                controleMesa.forEach(num => {
                    if (novaMesa == num.value) {
                        Swal.showValidationMessage('Ops! Essa mesa já está ocupada. Tente outro número.');
                    }
                })

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