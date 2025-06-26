const buttons = document.querySelectorAll(".btnAbrirAberto"); 
const comanda = document.querySelectorAll(".lancheAberto"); 
const tablePedido = document.querySelectorAll(".tablePedido"); 
const buttonsContainer = document.getElementById("lancheButtonsContainer");
const btnMostrar = document.querySelector(".btnMostrarLanches"); 
const botoesExcluir = document.querySelectorAll(".excluir-mesa");

// Inicializa o conteúdo da primeira mesa e tabela visíveis
if (buttons.length > 0) {
    const firstMesa = buttons[0].getAttribute("data-id-mesa");

    comanda.forEach(item => {
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
        const selecionado = button.getAttribute("data-id-mesa"); 

        // Itera sobre as divs de pedidos e as tabelas de pedidos
        comanda.forEach(item => {
            if (item.getAttribute("data-id-mesa") === selecionado) {
                item.style.display = "block";  // Torna visível o conteúdo da mesa correspondente
            } else {
                item.style.display = "none";   // Esconde o conteúdo de outras mesas
            }
        });

        tablePedido.forEach(item => {
            if (item.getAttribute("data-id-mesa") === selecionado) {
                item.style.display = "block";  // Torna visível a tabela do pedido correspondente
            } else {
                item.style.display = "none";   // Esconde a tabela de outros pedidos
            }
        });
    });
});

// Evento para mostrar/ocultar os botões das mesas ao clicar no botão "Mostrar Todos"
btnMostrar.addEventListener("click", function () {
    if (buttonsContainer.style.display === "none" || buttonsContainer.style.display === "") {
        buttonsContainer.style.display = "flex";
        btnMostrar.textContent = "Fechar Todos";

        // Remove azul inline para não conflitar com o darkred
        btnMostrar.style.backgroundColor = 'darkred';
        btnMostrar.style.color = 'white';

        // Remove qualquer classe que possa interferir
        btnMostrar.classList.remove("btn-danger");
    } else {
        buttonsContainer.style.display = "none";
        btnMostrar.textContent = "Mostrar Todos";

        // Volta pro azul inline
        btnMostrar.style.backgroundColor = "darkblue";
        btnMostrar.style.color = "white";

        // Remove classe btn-danger só por garantia
        btnMostrar.classList.remove("btn-danger");
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
            cancelButtonColor: 'darkblue',
            confirmButtonText: 'Sim, excluir mesa!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});

const selects = document.querySelectorAll('.tamanho-select');

selects.forEach(select => {
  // Setar valores iniciais no load
  const bebidaId = select.dataset.bebidaId;
  const firstOption = select.options[0];
  if (firstOption) {
    const inputValor = document.querySelector(`input[name="valorBebida"][data-bebida-id="${bebidaId}"]`);
    const inputTamanho = document.querySelector(`input[name="tamanhoBebida"][data-bebida-id="${bebidaId}"]`);
    if (inputValor) {
      inputValor.value = firstOption.getAttribute('data-valor');
    }
    if (inputTamanho) {
      inputTamanho.value = firstOption.value;
    }
  }

  select.addEventListener('change', (e) => {
    const bebidaId = e.target.dataset.bebidaId;
    const selectedOption = e.target.options[e.target.selectedIndex];
    const valor = selectedOption.getAttribute('data-valor');
    const tamanho = selectedOption.value;

    const inputValor = document.querySelector(`input[name="valorBebida"][data-bebida-id="${bebidaId}"]`);
    const inputTamanho = document.querySelector(`input[name="tamanhoBebida"][data-bebida-id="${bebidaId}"]`);

    if (inputValor) {
      inputValor.value = valor;
    }
    if (inputTamanho) {
      inputTamanho.value = tamanho;
    }
  });
});
