/**
 * Confirma exclusão recebível
 */
function confirmaExcluiRecebivel(id_recebivel){
        
    // AJAX request 
    if (confirm('Confirma a exclusão do recebível?')){

        $.ajax({
          url: '/financeiro/recebivel/destroy/'+id_recebivel, 
          type: 'get',
          dataType: 'html',
          success: function(response){      
            console.log('response '+response);
            if (response == 1){
             // window.history.back();   
              alert("Recebível excluído com sucesso.");            
              window.location.reload();
            }
            else{
              alert("ATENÇÃO! Não foi possível excluir o recebível.");            
              window.location.reload();
            }
          }
      });
    }    
}
