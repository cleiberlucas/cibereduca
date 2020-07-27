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