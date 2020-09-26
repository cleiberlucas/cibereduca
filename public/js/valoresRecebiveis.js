/**
 * lê  valores de matrícula, curso e material didático de uma matrícula escolhida
 * mostra os valores na tela p facilitar o lançamento dos recebíveis.
 */
$(document).ready(function(){
      
    // Conta selecionado
    $('#fk_id_matricula').change(function(){
      
        // ID matrícula
        var id_matricula = $('#fk_id_matricula').val();
        console.log("id matrícula "+id_matricula);
        // Empty the dropdown
        document.getElementById('valores').innerHTML = '';
        //$('#valores').innerHTML = '';
            
        // AJAX request 
        $.ajax({
          url: '/secretaria/matriculas/getValores/'+id_matricula,
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

                var valor_matricula = response['data'][i].valor_matricula;                
                var valor_curso = response['data'][i].valor_curso;
                var valor_desconto = response['data'][i].valor_desconto;
                var valor_contrato = valor_curso - valor_desconto;

                document.getElementById('valor_matricula').value = valor_matricula;
                document.getElementById('valor_curso').value = valor_curso;
                document.getElementById('valor_desconto').value = valor_desconto;
                
                console.log(document.getElementById('valor_matricula').value);
                /* $('#form').append('<input type="hidden" id="valor_matricula" name="valor_matricula" value="'+valor_matricula+'">'); */

                valor_matricula = valor_matricula.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});
                valor_curso = valor_curso.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});
                valor_desconto = valor_desconto.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});
                valor_contrato = valor_contrato.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});

                var qt_parcelas_curso = response['data'][i].qt_parcelas_curso;
                document.getElementById('qt_parcelas_curso').value = qt_parcelas_curso;
                
                var data_venc_parc_um = '';
                if (typeof response['data'][i].data_venc_parc_um != "undefined"){
                   data_venc_parc_um = response['data'][i].data_venc_parc_um;
                }

                var valor_material_didatico = response['data'][i].valor_material_didatico;
                valor_material_didatico = valor_material_didatico.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});

                var qt_parcelas_mat_didatico = response['data'][i].qt_parcelas_mat_didatico;
                var obs_matricula = response['data'][i].obs_matricula;
                
                var informacoes = '<b>Dados do contrato:</b><br>';

                informacoes += '<b>Matrícula</b>: '+valor_matricula+'<br> ';
                informacoes += '<b>Valor contrato</b>: '+ (valor_contrato) +' - Curso '+valor_curso+' Desconto '+valor_desconto+' - ';
                informacoes += 'Parcelas: '+qt_parcelas_curso+' - 1° Vencimento '+data_venc_parc_um +'<br>';

                informacoes += '<b>Material Didático</b>: '+valor_material_didatico+' Parcelas: '+qt_parcelas_mat_didatico+'<br>';

                informacoes += '<b>Observações</b>: '+obs_matricula;

                $("#valores").append(informacoes); 
              }
            }

          }
      });
    });    
});  
