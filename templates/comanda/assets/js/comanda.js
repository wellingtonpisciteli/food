const comanda_mesa = document.querySelectorAll('.comanda_mesa');
const lanche_mesa = document.querySelectorAll('.lanche_mesa');


let numerosVistos = new Set();  // Usando um Set para armazenar números únicos

comanda_mesa.forEach(mesa => {
  const numero = mesa.value;  // Acessa o valor do input hidden
  
  if (!numerosVistos.has(numero)) {
    console.log(`O número ${numero} é único até agora.`);
    numerosVistos.add(numero);  // Adiciona o número ao Set
  }
});

lanche_mesa.forEach(lanche=>{
    console.log(lanche.value)
})

