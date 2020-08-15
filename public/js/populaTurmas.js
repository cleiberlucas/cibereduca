/**
 * Popula combo de turmas a partir do 
 * Ano letivo escolhido
 */
$(document).ready(function(){
      
    // Ano selecionado
    $('#anoLetivo').change(function(){

        // Ano id
        var id = $(this).val();
        //console.log("id ano "+id);
        // Empty the dropdown
        $('#turma').find('option').not(':first').remove();
        $('#disciplina').find('option').not(':first').remove();
        $('#mes').find('option').not(':first').remove();
    
        // AJAX request 
        $.ajax({
          url: '/secretaria/turmas/getTurmas/'+id,
          type: 'get',
          dataType: 'json',
          success: function(response){
    
            var len = 0;
            if(response['data'] != null){
              len = response['data'].length;
            }

            if(len > 0){
              // Read data and create <option >
              for(var i=0; i<len; i++){

                var id = response['data'][i].id_turma;
                var nome = response['data'][i].nomeTurma;

                var option = "<option value='"+id+"'>"+nome+"</option>"; 

                $("#turma").append(option); 
              }
            }

          }
      });
    });    
});  
