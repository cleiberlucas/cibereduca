/**
 * popula combo responsáveis com todos os responsaveis do sistema
 * Qualquer situação
 * Qualquer unidade de ensino
 */
function popularResponsaveisTodos(){
        
  // AJAX request   
    //limpando o combo de responsaveis
    $('#fk_id_pessoa').find('option').not(':first').remove();

      $.ajax({
        url: '/secretaria/pessoas/getResponsaveisTodos/1', 
        type: 'get',
        dataType: 'json',
        success: function(response){      
          //console.log('response '+response);
          
            //console.log('procurando responsaveis');
            var len = 0;
            if(response['data'] != null){
              len = response['data'].length;
            }
           //console.log('qtd resp '+len);

            if(len > 0){
              // Read data and create <option >
              for(var i=0; i<len; i++){

                var id = response['data'][i].id_pessoa;
                var nome = response['data'][i].nome;

                var option = "<option value='"+id+"'>"+nome+"</option>"; 

                $("#fk_id_pessoa").append(option); 
              }
            }
          /* 
          else{
            alert("ATENÇÃO! Não foi possível recarregar os responsáveis.");            
            //window.location.reload();
          } */
        }
    });
  }    
