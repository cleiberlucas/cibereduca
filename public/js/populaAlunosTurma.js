/**
 * Popula combo de alunos a partir da
 * Turma escolhida
 */
$(document).ready(function(){
      
    // Ano selecionado
    $('#turma').change(function(){
        //console.log('clicou ano');
        // Ano id
        var id = $(this).val();

        //console.log(id);

        // Empty the dropdown
        $('#fk_id_matricula').find('option').not(':first').remove();
            
        // AJAX request 
        $.ajax({          
          url: '/secretaria/matriculas/getAlunosTurma/'+id,
          type: 'get',
          dataType: 'json',
          success: function(response){
            console.log('procurando alunos');
            var len = 0;
            if(response['data'] != null){
              len = response['data'].length;
            }
           // console.log('qtd alunos '+len);

            if(len > 0){
              // Read data and create <option >
              for(var i=0; i<len; i++){

                var id = response['data'][i].id_matricula;
                var nome = response['data'][i].nome;

                var option = "<option value='"+id+"'>"+nome+"</option>"; 

                $("#fk_id_matricula").append(option); 
              }
            }

          }
      });
    });    
});  
