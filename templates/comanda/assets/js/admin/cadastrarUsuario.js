const btnCadastrar = document.getElementById('btnCadastrar');

// Ao clicar no botão "Cadastrar"
btnCadastrar.addEventListener('click', (e) => {
  e.preventDefault();

  // Confirmação com SweetAlert
  Swal.fire({
    title: '<span style="color: black;">Deseja cadastrar usuário?</span>',
    text: 'Confirme para continuar com o envio do formulário.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: '✅ Sim, Cadastrar',
    cancelButtonText: '❌ Cancelar',
    confirmButtonColor: 'darkblue',
    cancelButtonColor: 'darkred'
  }).then((result) => {
    if (result.isConfirmed) {
      form.submit(); // Envia o formulário
    }
  });
});