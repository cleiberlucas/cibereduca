/**
 * Popula combo de turmas a partir do 
 * Turma escolhido
 */
$(document).ready(function(){
      
    // Turma selecionado
    $('#turma').change(function(){

        // Turma id
        var id = $(this).val();

        // Empty the dropdown
        $('#mes').find('option').not(':first').remove();

              // Read data and create <option >
                var option = "<option value='1'>Janeiro</option>"; 
                $("#mes").append(option); 
                var option = "<option value='2'>Fevereiro</option>"; 
                $("#mes").append(option); 
                var option = "<option value='3'>Março</option>"; 
                $("#mes").append(option); 
                var option = "<option value='4'>Abril</option>"; 
                $("#mes").append(option); 
                var option = "<option value='5'>Maio</option>"; 
                $("#mes").append(option); 
                var option = "<option value='6'>Junho</option>"; 
                $("#mes").append(option); 
                var option = "<option value='7'>Julho</option>"; 
                $("#mes").append(option); 
                var option = "<option value='8'>Agosto</option>"; 
                $("#mes").append(option); 
                var option = "<option value='9'>Setembro</option>"; 
                $("#mes").append(option); 
                var option = "<option value='10'>Outubro</option>"; 
                $("#mes").append(option); 
                var option = "<option value='11'>Novembro</option>"; 
                $("#mes").append(option); 
                var option = "<option value='12'>Dezembro</option>"; 
                $("#mes").append(option); 

    });    
});  
