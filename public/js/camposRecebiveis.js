/**
 * Cria campos para lançamento de recebíveis
 * conforme conta contábil escolhida
 */
$(document).ready(function(){
      
    // Conta selecionado
    $('#fk_id_conta_contabil').change(function(){
      
      var id_matricula = $('#fk_id_matricula').val();
      //caso não tenha escolhido a turma
      if (id_matricula < 1){
        alert('Escolha uma turma.');
        document.getElementById('fk_id_conta_contabil').value = 0;
        return;
      }

        // ID conta contábil
        var id_conta_contabil = $('#fk_id_conta_contabil').val();
       
        console.log("fk_id_conta_contabil "+id_conta_contabil);

        //Escolheu lançar matrícula
        if (id_conta_contabil == 1){
            var qt_parcelas = 1;
            var valor_principal = document.getElementById('valor_matricula').value;
            var valor_desconto = 0;
        }
        //escolheu lançar curso
        else if (id_conta_contabil == 2){
            var qt_parcelas = document.getElementById('qt_parcelas_curso').value;
            var valor_principal = document.getElementById('valor_curso').value;
            var valor_desconto = document.getElementById('valor_desconto').value;
            var data_venc = document.getElementById('data_venc_parcela_um').value;
//            console.log('campos rec data venc = '+data_venc);        
        }
         //escolheu lançar material didático
         else if (id_conta_contabil == 3){
          var qt_parcelas = document.getElementById('qt_parcelas_mat_didatico').value;
          var valor_principal = document.getElementById('valor_material_didatico').value;
          //var valor_desconto = document.getElementById('valor_desconto').value;
          var data_venc = document.getElementById('data_venc_parcela_um').value;
//            console.log('campos rec data venc = '+data_venc);        
      }

         if(qt_parcelas < 1){
          alert("Defina a quantidade de parcelas no contrato do aluno.");
          return;
        }
        
        for (var i=1; i<= qt_parcelas; i++){
            $('#campos').append('<div class="row pt-5">');
              $('#campos').append('<div class="form-group col-sm-3 col-xs-2"> <label>Valor:</label> <input type="number" step="0.010" class="form-control" required name="valor['+i+']" value="'+valor_principal/qt_parcelas+'" /> </div>');
              $('#campos').append('<div class="form-group col-sm-3 col-xs-2"> <label>Valor desconto:</label> <input type="number" step="0.010" class="form-control" required name="valor_desconto_principal['+i+']" value="'+valor_desconto/qt_parcelas+'"/> </div>');
              $('#campos').append('<div class="form-group col-sm-2 col-xs-2"> <label>N° Parcela:</label> <input type="text" class="form-control" maxlength="5" size="5" required name="parcela['+i+']" value="'+i+'/'+qt_parcelas+'" /> </div>');
              
              //calculando datas dos próximos vencimentos
              if (i> 1){              
                data_venc = somarMesData(data_venc, 1);             
                //console.log(data_venc);
              }
            // console.log(data_venc);
              $('#campos').append('<div class="col-sm-3 col-xs-2"> <label>Data Vencimento:</label> <input type="date" class="form-control" required name="data_vencimento['+i+']" value="'+data_venc+'" /> </div>');
              
              $('#campos').append('<div class="row">');
                $('#campos').append('<div class="form-group col-sm-8 col-xs-2"> <label>Observações:</label> <input type="text" maxlength="250" class="form-control" name="obs_recebivel['+i+']" value="" /> </div>');
                $('#campos').append('<div class="form-group col-sm-3 col-xs-2"> <label>Valor Total:</label> <input type="number" step="0.010" class="form-control" required name="valor_principal['+i+']" value="'+(valor_principal - valor_desconto)/qt_parcelas+'"/> </div>');

              $('#campos').append(' </div>');
            $('#campos').append(' </div>');
            $('#linha').append(' <hr>');

        }
                
    });    
});  
