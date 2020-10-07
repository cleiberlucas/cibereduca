/**
 * Cria campos para lançamento de 2ª LINHA DE RECEBIMENTO
 * Para o caso de pagamento de parte em dinheiro e parte em cartão por exemplo 
 * Os campos são criados automaticamente, forma de pagamento e valor
 */
function calcularSegundoRecebimento(){
      
  //Limpando a div dos acréscimos  
    var valor_recebido0 = document.getElementById("valor_recebido[0]").value;

    valor_total_principal = parseFloat(document.getElementById("valor_total").value);
    valor_multa = 0;
    valor_juro = 0;
    //console.log('VALOR TOTAL PRINCIPAL '+valor_total_principal);
    if (document.getElementById("valor_total_acrescimo[0]"))
      valor_multa = parseFloat(document.getElementById("valor_total_acrescimo[0]").value);
  
    if (document.getElementById("valor_total_acrescimo[1]"))
      valor_juro = parseFloat(document.getElementById("valor_total_acrescimo[1]").value);
  
    var valor_total_recebido = valor_total_principal + valor_multa + valor_juro;

    if (valor_recebido0 < valor_total_recebido){
      document.getElementById("valor_recebido[1]").value = (valor_total_recebido - valor_recebido0).toFixed(2);
      document.getElementById("fk_id_forma_pagamento[1]").setAttribute('required', 'required');
    }
    else{
      document.getElementById("valor_recebido[0]").value = valor_total_recebido;
      document.getElementById("valor_recebido[1]").value = 0;
      document.getElementById("fk_id_forma_pagamento[1]").removeAttribute('required', 'required');
      alert('O valor recebido não pode ser maior que o total recebível.')
    }
    
}
