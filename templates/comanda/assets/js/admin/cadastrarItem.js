const form = document.getElementById('form');
const btnCadastrar = document.getElementById('btnCadastrar');
const bebida = document.getElementById('bebida');

// Mapeia bebidas existentes como objetos { marca, id }
const bebidas = Array.from(document.querySelectorAll('.bebidaIgual')).map((el, i) => {
  return {
    marca: el.value,
    id: document.querySelectorAll('.idBebidaNova')[i].value
  };
});

// Determina o próximo ID possível (para bebida nova)
const idBebidaNova = document.querySelectorAll('.idBebidaNova');
let ultimoInputBebida = idBebidaNova.length > 0 ? idBebidaNova[idBebidaNova.length - 1].value : null;
let proximoValorBebida = parseInt(ultimoInputBebida) || 0;

// Determina o próximo ID para lanche
const idIngredi = document.querySelectorAll('.idLancheTeste');
let ultimoInput = idIngredi.length > 0 ? idIngredi[idIngredi.length - 1].value : null;
let proximoValor = parseInt(ultimoInput) || 0;

// Remove input existente (se houver)
function removerInputExistente(nome) {
  const existente = document.querySelector(`input[name="${nome}"]`);
  if (existente) existente.remove();
}

// Cria input hidden no form
function adicionarInputHidden(nome, valor) {
  removerInputExistente(nome);
  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = nome;
  input.value = valor;
  form.appendChild(input);
  console.log(`Input "${nome}" criado com valor:`, valor);
}

// Quando seleciona uma bebida no select
bebida.addEventListener('change', () => {
  const bebidaSelecionada = bebida.value;
  console.log('Bebida selecionada:', bebidaSelecionada);

  const bebidaEncontrada = bebidas.find(b => b.marca === bebidaSelecionada);

  let valorId;
  if (bebidaEncontrada) {
    valorId = bebidaEncontrada.id;
    console.log('Bebida já cadastrada! ID:', valorId);
  } else {
    valorId = proximoValorBebida + 10;
    console.log('Bebida nova. Gerando novo ID:', valorId);
  }

  adicionarInputHidden('id_bebida', valorId);
});

// Ao clicar no botão "Cadastrar"
btnCadastrar.addEventListener('click', (e) => {
  e.preventDefault();

  // Adiciona input de ID do lanche
  adicionarInputHidden('id_referencia', proximoValor + 10);

  // Confirmação com SweetAlert
  Swal.fire({
    title: '<span style="color: black;">Deseja cadastrar os dados?</span>',
    text: 'Confirme para continuar com o envio do formulário.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: '✅ Sim, cadastrar',
    cancelButtonText: '❌ Cancelar',
    confirmButtonColor: 'darkblue',
    cancelButtonColor: 'darkred'
  }).then((result) => {
    if (result.isConfirmed) {
      form.submit(); // Envia o formulário
    }
  });
});