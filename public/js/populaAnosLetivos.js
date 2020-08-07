function instancia_ajax() {
  var ajaxRequest;   // The variable that makes Ajax possible!
  try {
    // Opera, Firefox, Safari
    ajaxRequest = new XMLHttpRequest();
  } catch (e) {
    // Internet Explorer Browsers
    try {
      ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {
        // Something went wrong
        alert("Seu Browser não suporta AJAX !");
        return false;
      }
    }
  }
  return ajaxRequest;
}
/**
 * Popula combo anos letivos
 * para uma unidade ensino 
 */
function carregarAnoLetivo() {
  var ajaxRequest = instancia_ajax();   // The variable that makes Ajax possible!
  // Create a function that will receive data sent from the server
  ajaxRequest.onreadystatechange = function () {
    if (ajaxRequest.readyState == 4) {
      var val = ajaxRequest.responseText;

      // apenas se o servidor retornar "OK"
      if (ajaxRequest.status == 200) {
        
        alert(ajaxRequest.responseText);
        /* if(response['data'] != null){
          len = response['data'].length;
        } */
       // console.log('len'+leni);
       /*  if(len > 0){ */
          // Read data and create <option >
          for(var i=0; i<1; i++){

            /* var id = response['data'][i].id_ano_letivo;
            var nome = response['data'][i].ano; */
            var option = "<option value='1'> 2020</option>";
            /* var option = "<option value='"+id+"'>"+nome+"</option>";  */

            $("#anoLetivo").append(option); 
          }
       /*  } */



        if (val == '')
          alert('Erro no processamento');
      }
      else {
        alert("Houve um problema ao obter os dados: " + val);
      }
    }
  }
  ajaxRequest.open("GET", "/admin/anosletivos/getAnosLetivos/");
  ajaxRequest.send(null);
}
  /* $(document).ready(function(){
        
      // Ano selecionado
      $('#anoLetivo').change(function(){

          // Ano id
          
          console.log('ano');
          // Empty the dropdown        
      
          // AJAX request 
          $.ajax({ 
            url: '/admin/anosletivos/getAnosLetivos/',
            type: 'get',
            dataType: 'json',
            success: function(response){
              
              asdf
              if(response['data'] != null){
                len = response['data'].length;
              }
              console.log('len'+leni);
              if(len > 0){
                // Read data and create <option >
                for(var i=0; i<len; i++){

                  var id = response['data'][i].id_ano_letivo;
                  var nome = response['data'][i].ano;

                  var option = "<option value='"+id+"'>"+nome+"</option>"; 

                  $("#anoLetivo").append(option); 
                }
              }

            }
            
        });
      });    
  });   
}*/