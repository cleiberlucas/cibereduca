/**
 * Popula combo de periodos letivos a partir do 
 * Ano letivo escolhido
 */
$(document).ready(function(){
      
  // Ano selecionado
  $('#anoLetivo').change(function(){

      // Ano id
      var id = $(this).val();
      //console.log("id ano "+id);
      // Empty the dropdown
      $('#periodo').find('option').not(':first').remove();
      $('#conteudo_periodo').find('option').not(':first').remove();      
  
      // AJAX request 
      $.ajax({
        url: '/admin/periodosletivos/getPeriodos/'+id,
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

              var id = response['data'][i].id_periodo_letivo;
              var nome = response['data'][i].periodo_letivo;

              var option = "<option value='"+id+"'>"+nome+"</option>"; 

              $("#periodo").append(option);
              $("#conteudo_periodo").append(option); 
            }
          }

        }
    });
  });    
});  
