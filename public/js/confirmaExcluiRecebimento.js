/**
 * Confirma exclusão recebimento 
 */
function confirmaExcluiRecebimento(id_recebivel){
        
    // AJAX request 
    if (confirm('Confirma a exclusão do recebimento?')){

        $.ajax({
          url: '/financeiro/recebimento/destroy/'+id_recebivel, 
          type: 'get',
          dataType: 'html',
          success: function(response){            
            window.location.reload();
            alert("Recebimento excluído com sucesso.");            
          }
      });
    }    
}
