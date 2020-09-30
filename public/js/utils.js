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