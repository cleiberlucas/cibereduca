/**
 * Cria campos para lançamento de recebíveis
 * conforme conta contábil escoolhida
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
        }

        if(qt_parcelas < 1){
          alert("Defina a quantidade de parcelas no contrato do aluno.");
          return;
        }
        
        for (var i=1; i<= qt_parcelas; i++){
          $('#campos').append('<br><br>');
            $('#campos').append('<div class="col-sm-3 col-xs-2"> <label>Valor:</label> <input type="number" step="0.010" class="form-control" required name="valor['+i+']" value="'+valor_principal/qt_parcelas+'" /> </div>');
            $('#campos').append('<div class="col-sm-3 col-xs-2"> <label>Valor desconto:</label> <input type="number" step="0.010" class="form-control" required name="valor_desconto['+i+']" value="'+valor_desconto/qt_parcelas+'"/> </div>');
            $('#campos').append('<div class="col-sm-2 col-xs-2"> <label>N° Parcela:</label> <input type="text" class="form-control" required name="parcela['+i+']" value="'+i+'/'+qt_parcelas+'" /> </div>');
            $('#campos').append('<div class="col-sm-3 col-xs-2"> <label>Data Vencimento:</label> <input type="date" class="form-control" required name="data_vencimento['+i+']" value="" /> </div>');

        }
                
    });    
});  
