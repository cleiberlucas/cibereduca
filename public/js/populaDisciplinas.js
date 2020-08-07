/**
 * Popula combo de disciplinas a partir do 
 * Turma escolhido
 */
$(document).ready(function(){
      
    // Turma selecionado
    $('#turma').change(function(){

        // Turma id
        var id = $(this).val();

        // Empty the dropdown
        $('#disciplina').find('option').not(':first').remove();
    
        // AJAX request 
        $.ajax({
          url: '/admin/tiposturmas/getDisciplinas/'+id,
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

                var id = response['data'][i].id_disciplina;
                var nome = response['data'][i].disciplina;

                var option = "<option value='"+id+"'>"+nome+"</option>"; 

                $("#disciplina").append(option); 
              }
            }

          }
      });
    });    
});  
