/**
 * Verifica se pessoa ja esta cadastra no sistema
 * @param {string} nome 
 */

function getPessoa(nome){
  
  /* var nome = document.getElementById('nome').value; */
  if (nome.length > 2){
   /*  console.log('consultando se pessoa existe'); */
      // AJAX request 
      $.ajax({
        url: '/secretaria/pessoas/getPessoa/'+nome, 
        type: 'get',
        dataType: 'json',
        success: function(response){

          var len = 0;
          if(response['data'] != null){
            len = response['data'].length;
          }

          if(len > 0){
            // Read data and create <option >
            alert('Verifique antes de continuar. \nJá existe uma pessoa cadastrada com este nome '+response['data'][0].nome+',\n nascida em '+response['data'][0].data_nascimento+'.');
            //console.log('achou');
          }
          else{
            //console.log('não achou');
          }

        }
    });
  }
}

function dataAtualFormatada(){
  var data = new Date(),
      dia  = data.getDate().toString().padStart(2, '0'),
      mes  = (data.getMonth()+1).toString().padStart(2, '0'), //+1 pois no getMonth Janeiro começa com zero.
      ano  = data.getFullYear();
  return dia+"/"+mes+"/"+ano;
}

/**
 * Recebe date do BD ou campo data
 * converte p dd/mm/YYYY
 */
function formataData(inputFormat) {  
  var d = new Date(inputFormat+" 05:00")
  return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('/');
}

/**
 * 
 * @param {String} data 
 * @param {int} qt 
 * @returns data yyyy-mm-dd
 */
function somarMesData(data, qt){  
  
  dt = Date.parse(data);
  dt = new Date(data);
  //console.log('somar após new date '+ data);
  dt.setMonth(dt.getMonth() + qt);
  //console.log('somou mes '+dt);
  dt.setDate(dt.getDate() + 1);
  dataSomada = dt.getFullYear()+'-'+pad(dt.getMonth()+1)+'-'+pad(dt.getDate());
  return dataSomada;
}

function pad(s) { 
  return (s < 10) ? '0' + s : s; 
}

/**
 * Recalcula valor de recebível conforme desconto informado
 * Utilizado para valor principal, multas ou juros
 * @param {*} valor_principal 
 * @param {*} valor_desconto 
 * @param {*} campo_desconto 
 * @param {*} valor_total 
 */

function recalcularValor(campo_valor_principal, campo_valor_desconto, campo_desconto, campo_valor_total){
  //console.log('Recalculando Valor .');
 // console.log("Recebeu "+valor_principal+" e "+valor_desconto);
 valor_principal = parseFloat(document.getElementById(''+campo_valor_principal).value);
 valor_desconto = 0;
 if (document.getElementById(''+campo_valor_desconto).value > 0)
    valor_desconto = parseFloat(document.getElementById(''+campo_valor_desconto).value);
    
 console.log('valor desconto '+valor_desconto);
  if (valor_desconto > valor_principal){
    alert('O valor do desconto não pode ser maior que o valor principal. Principal: '+valor_principal+ 'Desconto: '+valor_desconto);
    document.getElementById(''+campo_desconto).value = 0;
    document.getElementById(''+campo_valor_total).value = valor_principal - 0;
    return;
  }
  //console.log('campo total '+campo_valor_total);
  //console.log("total = "+(valor_principal - valor_desconto));
  document.getElementById(''+campo_valor_total).value = (valor_principal - valor_desconto).toFixed(2);
  //return valor_principal - valor_desconto;
}

function desmarcarTodosChecks()
{
  $('input:checkbox').prop('checked', false);
}

function limparTodasDiv(nome_div)
{
  console.log('limpando div '+nome_div);
  /* var allDiv = document.getElementsByClassName("div"),
            div = allDiv[allDiv.length - 1];
  document.body.removeChild(div);
 */
  
  var p = $(this)
  .closest("."+nome_div); // seleciona o elemento pai com a classe

  p
  .fadeOut(350, function(){
   p.remove();
});

 //$('#'+nome_div).empty();  

}