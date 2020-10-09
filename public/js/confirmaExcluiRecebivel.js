/**
 * Confirma exclusão recebimento 
 */
function confirmaExcluiRecebivel(id_recebivel){
        
    // AJAX request 
    if (confirm('Confirma a exclusão do recebível?')){

        $.ajax({
          url: '/financeiro/destroy/'+id_recebivel, 
          type: 'get',
          dataType: 'html',
          success: function(response){            
            history.go(-1);
            alert("Recebível excluído com sucesso.");            
          }
      });
    }    
}
