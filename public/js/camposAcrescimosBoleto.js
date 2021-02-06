/**
 * Cria campos para lançamento de ACRÉSCIMOS aos boletos
 * Conforme data de recebimento informada
 * Somente se a data de recebimento for maior que a data do vencimento
 * Os campos são criados automaticamente, conforme quantidade configuração dos acréscimos 
 */
function calcularAcrescimos(index){
      
  //Limpando a div dos acréscimos
  document.getElementById("multa"+index).innerHTML = "";
  document.getElementById("juro"+index).innerHTML = "";
  document.getElementById("total_boleto"+index).innerHTML = "";

    var indice_multa = document.getElementById("indice_correcao[0]").value;
    var indice_juro = document.getElementById("indice_correcao[1]").value;

    var aplicar_multa = document.getElementById("aplica_correcao[0]").value;
    var aplicar_juro = document.getElementById("aplica_correcao[1]").value;

    var valor_total_principal = document.getElementById("valor_total["+index+"]").value;
    console.log('valor total principal '+valor_total_principal);

    //console.log('conta multa '+id_conta_contabil_multa);
    
    var data_venc = new Date(document.getElementById("data_vencimento["+index+"]").value);
    var data_novo_vencimento = new Date(document.getElementById("novo_vencimento").value);
    data_novo_vencimento = new Date(data_novo_vencimento.getTime() + data_novo_vencimento.getTimezoneOffset() * 510000);
    data_novo_vencimento = data_novo_vencimento;
    hoje = new Date();
    hoje = new Date(hoje.getTime());
    console.log('data novo vencimento '+document.getElementById("novo_vencimento").value);
    console.log('data novo vencimento convertida '+data_novo_vencimento);
    if (data_novo_vencimento == 'Invalid Date' || hoje > data_novo_vencimento){
      alert('Informe a data do novo vencimento maior ou igual a hoje.');
      document.getElementById("fk_id_recebivel["+index+"]").checked = false;
      //window.location.reload();
    }

    var diff = Math.abs(data_novo_vencimento.getTime() - data_venc.getTime());
    var dias_atraso = Math.ceil(diff / (1000 * 60 * 60 * 24));
    
    console.log('DIAS ATRASO '+dias_atraso);

    //selecionou um recebível
    console.log('marcou '+document.getElementById("fk_id_recebivel["+index+"]").checked)
    if (document.getElementById("fk_id_recebivel["+index+"]").checked){
      //aplicando multa
      if (aplicar_multa == 1 && dias_atraso > 0){
        var valor_multa = (valor_total_principal * indice_multa / 100).toFixed(2);      
          $('#multa'+index).append('<div class="form-group col-sm-1">&nbsp;</div>');
          $('#multa'+index).append('<div class="form-group col-sm-3 col-xs-2"> <label>Multa: ('+indice_multa+'%)</label> <input type="number" step="0.010" class="form-control" readonly required name="valor_multa'+index+'" id="valor_multa'+index+'" readonly value="'+valor_multa+'" /> </div>');
          $('#multa'+index).append('<div class="form-group col-sm-3 col-xs-2"> <label>Desconto Multa:</label> <input type="number" step="0.010" class="form-control" name="valor_desconto_multa'+index+'" id="valor_desconto_multa'+index+'" value="" onBlur="recalcularValor(\'valor_multa'+index+'\', \'valor_desconto_multa'+index+'\', \'valor_desconto_multa'+index+'\', \'valor_total_multa'+index+'\'); somarRecebiveis('+index+');" /> </div>');
          $('#multa'+index).append('<div class="form-group col-sm-3 col-xs-2"> <label>Valor Multa:</label> <input type="number" step="0.010" class="form-control" readonly name="valor_total_multa'+index+'" id="valor_total_multa'+index+'" value="'+valor_multa+'" /> </div>');      
      // document.getElementById("fk_id_recebivel["+index+"]").checked = true;     
      }

      //aplicando juro
      if (aplicar_juro == 1 && dias_atraso > 0){         
          var valor_juro = (valor_total_principal / 30 * indice_juro / 100 * dias_atraso).toFixed(2);     
          $('#juro'+index).append('<div class="form-group col-sm-1">&nbsp;</div>');
          $('#juro'+index).append('<div class="form-group col-sm-3 col-xs-2"> <label>Juros: ('+indice_juro+'% ao mês)</label> <input type="number" step="0.010" class="form-control" readonly required name="valor_juro'+index+'" id="valor_juro'+index+'" readonly value="'+valor_juro+'" /> </div>');
          $('#juro'+index).append('<div class="form-group col-sm-3 col-xs-2"> <label>Desconto Juros:</label> <input type="number" step="0.010" class="form-control" name="valor_desconto_juro'+index+'" id="valor_desconto_juro'+index+'" value="" onBlur="recalcularValor(\'valor_juro'+index+'\', \'valor_desconto_juro'+index+'\', \'valor_desconto_juro'+index+'\', \'valor_total_juro'+index+'\'); somarRecebiveis('+index+');" /> </div>');
          $('#juro'+index).append('<div class="form-group col-sm-3 col-xs-2"> <label>Valor Juros: ('+dias_atraso+' dias)</label> <input type="number" step="0.010" class="form-control" readonly name="valor_total_juro'+index+'" id="valor_total_juro'+index+'" value="'+valor_juro+'" /> </div>');      
          /* $('#juro'+index).append('</div>'); */
      // document.getElementById("fk_id_recebivel["+index+"]").checked = true;  
      } 
    }
    //se desmarcar o recebível
    //limpar juros e multa
    else
    {
      //Limpando a div dos acréscimos
      document.getElementById("multa"+index).innerHTML = "";
      document.getElementById("juro"+index).innerHTML = "";
      document.getElementById("total_boleto"+index).innerHTML = "";
    }
          
   
}  

function somarRecebiveis(index){
  document.getElementById("total_boleto"+index).innerHTML = "";
  valor_multa = 0;
  valor_juro = 0;

  valor_total_principal = parseFloat(document.getElementById("valor_total["+index+"]").value);
  
  if (document.getElementById("valor_total_multa"+index))
    valor_multa = parseFloat(document.getElementById("valor_total_multa"+index).value);

  if (document.getElementById("valor_total_juro"+index))
    valor_juro = parseFloat(document.getElementById("valor_total_juro"+index).value);

  var valor_total_boleto = (valor_total_principal + valor_multa + valor_juro).toFixed(2);

  var data_novo_vencimento = new Date(document.getElementById("novo_vencimento").value);

  if (document.getElementById("fk_id_recebivel["+index+"]").checked && data_novo_vencimento != 'Invalid Date'){
    $('#total_boleto'+index).append('<div class="form-group col-sm-9">&nbsp;</div>');
    $('#total_boleto'+index).append('<div class="form-group col-sm-3 col-xs-2 align=right"> <label>TOTAL A PAGAR:</label> <input type="number" step="0.010" class="form-control" readonly name="valor_total_boleto'+index+'" id="valor_total_boleto'+index+'" value="'+valor_total_boleto+'" /> </div>');
  }
}
