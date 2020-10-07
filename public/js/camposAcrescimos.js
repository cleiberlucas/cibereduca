/**
 * Cria campos para lançamento de ACRÉSCIMOS aos recebíveis
 * Conforme data de recebimento informada
 * Somente se a data de recebimento for maior que a data do vencimento
 * Os campos são criados automaticamente, conforme quantidade configuração dos acréscimos 
 */
function calcularAcrescimos(){
      
  //Limpando a div dos acréscimos
  document.getElementById("multa").innerHTML = "";
  document.getElementById("juro").innerHTML = "";
  
    // ID conta contábil
    var id_conta_contabil_multa = document.getElementById("fk_id_conta_contabil_acrescimo[0]").value;
    var id_conta_contabil_juro = document.getElementById("fk_id_conta_contabil_acrescimo[1]").value;

    var indice_multa = document.getElementById("indice_correcao[0]").value;
    var indice_juro = document.getElementById("indice_correcao[1]").value;

    var aplicar_multa = document.getElementById("aplica_correcao[0]").value;
    var aplicar_juro = document.getElementById("aplica_correcao[1]").value;

    var valor_total_principal = document.getElementById("valor_total").value;

    //console.log('conta multa '+id_conta_contabil_multa);
    //setando data crédito = data recebimento
    document.getElementById("data_credito").value = document.getElementById('data_recebimento').value;

    var data_venc = new Date(document.getElementById('data_vencimento').value);
    var data_recebto = new Date(document.getElementById('data_recebimento').value);
    
    if (data_recebto <= data_venc)
      var dias_atraso = 0;
    else{
      var diff = Math.abs(data_recebto.getTime() - data_venc.getTime());
      var dias_atraso = Math.ceil(diff / (1000 * 60 * 60 * 24));
    }
    // console.log('DIAS ATRASO '+dias_atraso);

    //aplicando multa
    if (aplicar_multa == 1 && dias_atraso > 0){
      var valor_multa = (valor_total_principal * indice_multa / 100).toFixed(2);
      $('#multa').append('<div class="row mt-5 pt-5">');
        $('#multa').append('<div class="form-group col-sm-3 col-xs-2"> <label>Multa: ('+indice_multa+'%)</label> <input type="number" step="0.010" class="form-control" readonly required name="valor_acrescimo[0]" readonly value="'+valor_multa+'" /> </div>');
        $('#multa').append('<div class="form-group col-sm-3 col-xs-2"> <label>Desconto Multa:</label> <input type="number" step="0.010" class="form-control" name="valor_desconto_acrescimo[0]" id="valor_desconto_acrescimo[0]" value="" onBlur="recalcularValor('+valor_multa+', this.value, \'valor_desconto_acrescimo[0]\', \'valor_total_acrescimo[0]\'); somarRecebiveis();" /> </div>');
        $('#multa').append('<div class="form-group col-sm-3 col-xs-2"> <label>Valor Multa:</label> <input type="number" step="0.010" class="form-control" readonly name="valor_total_acrescimo[0]" id="valor_total_acrescimo[0]" value="'+valor_multa+'" /> </div>');
      $('#multa').append(' </div>');       
    }

    //aplicando juro
    if (aplicar_juro == 1 && dias_atraso > 0){         
        var valor_juro = (valor_total_principal / 30 * indice_juro / 100 * dias_atraso).toFixed(2);
      $('#juro').append('<div class="row mt-5 pt-5">');
        $('#juro').append('<div class="form-group col-sm-3 col-xs-2"> <label>Juros: ('+indice_juro+'% ao mês)</label> <input type="number" step="0.010" class="form-control" readonly required name="valor_acrescimo[1]" readonly value="'+valor_juro+'" /> </div>');
        $('#juro').append('<div class="form-group col-sm-3 col-xs-2"> <label>Desconto Juros:</label> <input type="number" step="0.010" class="form-control" name="valor_desconto_acrescimo[1]" id="valor_desconto_acrescimo[1]" value="" onBlur="recalcularValor('+valor_juro+', this.value, \'valor_desconto_acrescimo[1]\', \'valor_total_acrescimo[1]\'); somarRecebiveis();" /> </div>');
        $('#juro').append('<div class="form-group col-sm-3 col-xs-2"> <label>Valor Juros: ('+dias_atraso+' dias)</label> <input type="number" step="0.010" class="form-control" readonly name="valor_total_acrescimo[1]" id="valor_total_acrescimo[1]" value="'+valor_juro+'" /> </div>');
      $('#juro').append(' </div>');        
    }       
   
}  

function somarRecebiveis(){
  valor_multa = 0;
  valor_juro = 0;

  valor_total_principal = parseFloat(document.getElementById("valor_total").value);
  //console.log('VALOR TOTAL PRINCIPAL '+valor_total_principal);
  if (document.getElementById("valor_total_acrescimo[0]"))
    valor_multa = parseFloat(document.getElementById("valor_total_acrescimo[0]").value);

  if (document.getElementById("valor_total_acrescimo[1]"))
    valor_juro = parseFloat(document.getElementById("valor_total_acrescimo[1]").value);

  var valor_total_recebido = (valor_total_principal + valor_multa + valor_juro).toFixed(2);
  document.getElementById('valor_recebido[0]').value = valor_total_recebido;
  document.getElementById('valor_recebido[1]').value = 0;
  document.getElementById("fk_id_forma_pagamento[1]").removeAttribute('required', 'required');
}
