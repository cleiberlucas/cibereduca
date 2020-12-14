/**
 * lê  valores de matrícula, curso e material didático de uma matrícula escolhida
 * mostra os valores na tela p facilitar o lançamento dos recebíveis.
 */
$(document).ready(function(){
      
    // Conta selecionado
    $('#fk_id_matricula').change(function(){
      
      //Limpando a div dos campos
      document.getElementById("campos").innerHTML = "";

        // ID matrícula
        var id_matricula = $('#fk_id_matricula').val();
        //console.log("id matrícula "+id_matricula);
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
              //console.log(response);
              for(var i=0; i<len; i++){

                var valor_matricula = response['data'][i].valor_matricula;                
                var valor_curso = response['data'][i].valor_curso;
                var valor_desconto = response['data'][i].valor_desconto;
                var valor_contrato = valor_curso - valor_desconto;

                var valor_entrada_mat_didatico = response['data'][i].valor_entrada_mat_didatico;
                var data_pagto_mat_didatico = response['data'][i].data_pagto_mat_didatico;

                var qt_parcelas_mat_didatico = response['data'][i].qt_parcelas_mat_didatico;
                var valor_material_didatico = response['data'][i].valor_material_didatico;

                document.getElementById('valor_matricula').value = valor_matricula;
                document.getElementById('valor_curso').value = valor_curso;
                document.getElementById('valor_desconto').value = valor_desconto;
                
                document.getElementById('valor_entrada_mat_didatico').value = valor_entrada_mat_didatico;
                document.getElementById('data_pagto_mat_didatico').value = response['data'][i].data_pagto_mat_didatico;

                document.getElementById('qt_parcelas_mat_didatico').value = qt_parcelas_mat_didatico;
                document.getElementById('valor_material_didatico').value = valor_material_didatico;
                document.getElementById('data_venc_parcela_um_mat_didatico').value = response['data'][i].data_venc_parcela_um_mat_didatico;

               // data_venc = new Date(response['data'][i].data_venc_parcela_um);
                document.getElementById('data_venc_parcela_um').value = response['data'][i].data_venc_parcela_um;
                //console.log('DATA VENC 1 = '+response['data'][i].data_venc_parcela_um);

                var data_venc_parcela_um = '';
                if (typeof response['data'][i].data_venc_parcela_um != "undefined"){
                  data_venc_parcela_um = response['data'][i].data_venc_parcela_um;                  
                }
                else{
                  alert("Defina o vencimento da primeira parcela do curso no contrato do aluno.");
                  //return;
                }

                var data_venc_parcela_um_mat_didatico = '';
                if (typeof response['data'][i].data_venc_parcela_um_mat_didatico != "undefined"){
                  data_venc_parcela_um_mat_didatico = response['data'][i].data_venc_parcela_um_mat_didatico;                  
                }
                else{
                  alert("Defina o vencimento da primeira parcela do MATERIAL DIDÁTICO no contrato do aluno.");
                  //return;
                }

               // console.log(document.getElementById('valor_matricula').value);
                /* $('#form').append('<input type="hidden" id="valor_matricula" name="valor_matricula" value="'+valor_matricula+'">'); */

                valor_matricula = valor_matricula.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});
                valor_curso = valor_curso.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});
                valor_desconto = valor_desconto.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});
                valor_contrato = valor_contrato.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});

                var qt_parcelas_curso = response['data'][i].qt_parcelas_curso;
                if(qt_parcelas_curso < 1){
                  alert("Defina a quantidade de parcelas do curso no contrato do aluno.");
                  return;
                }
                document.getElementById('qt_parcelas_curso').value = qt_parcelas_curso;

                var valor_material_didatico = response['data'][i].valor_material_didatico;
                valor_material_didatico = valor_material_didatico.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});

                var qt_parcelas_mat_didatico = response['data'][i].qt_parcelas_mat_didatico;
                var obs_matricula = response['data'][i].obs_matricula;
                
                var informacoes = '<b>Dados do contrato:</b><br>';

                informacoes += '<b>Matrícula</b>: '+valor_matricula+'<br> ';
                informacoes += '<b>Valor contrato</b>: '+ (valor_contrato) +' (curso '+valor_curso+' e desconto '+valor_desconto+')';
                informacoes += ' | '+qt_parcelas_curso+' Parcelas  com 1° Vencimento em '+formataData(data_venc_parcela_um) +'<br>';

                informacoes += '<b>Material Didático</b>: '+valor_material_didatico+' em '+qt_parcelas_mat_didatico+' ';
                informacoes += 'Parcelas  com 1° Vencimento em '+formataData(data_venc_parcela_um_mat_didatico) +'<br>';

                informacoes += '<b>Observações</b>: '+obs_matricula;

                $("#valores").append(informacoes); 
              }
            }

          }
      });
    });    
});  
