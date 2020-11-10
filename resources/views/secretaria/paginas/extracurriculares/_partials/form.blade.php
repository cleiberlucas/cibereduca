<div class="container-fluid">

    @include('admin.includes.alerts')
    @csrf
    
    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">
            <input type="hidden" name="fk_id_usuario" value="{{Auth::id()}}">

            <label>* Ano letivo:</label>
            <select name="fk_id_ano_letivo" id="" class="form-control" required 
                @if (isset($extraCurricular->fk_id_ano_letivo))
                    disabled        
                @endif
            >
                <option value=""></option>
                @foreach ($anosLetivos as $anoletivo)                    
                    <option value="{{$anoletivo->id_ano_letivo}}" 
                        @if (isset($extraCurricular) && $anoletivo->id_ano_letivo == $extraCurricular->fk_id_ano_letivo)
                            selected="selected" 
                        @endif
                    >{{$anoletivo->ano}}</option>
                    
                @endforeach
            </select>
        </div>    
    
        <div class="form-group col-sm-5 col-xs-2">
            <label>* Tipo Atividade ExtraCurricular:</label>
            <input type="text" name="tipo_atividade_extracurricular" class="form-control" maxlength="60" required  value="{{ $extraCurricular->tipo_atividade_extracurricular ?? old('tipo_atividade_extracurricular') }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-7 col-xs-2">
            <label>* Título para Contrato Matrícula:</label>
            <input type="text" name="titulo_contrato" class="form-control" maxlength="200" required  value="{{ $extraCurricular->titulo_contrato ?? old('titulo_contrato') }}">
            <small>Texto para título da atividade extracurricular no contrato.</small>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">                        
            <label>Valor Atividade:</label>
            <input type="number" id="valor_padrao_atividade" name="valor_padrao_atividade" step="0.010" class="form-control" placeholder="" value="{{ $extraCurricular->valor_padrao_atividade ?? old('valor_padrao_atividade') }}">            
            <small>Informe o valor total anual.</small>
        </div>

        <div class="form-group col-sm-3 col-xs-2">                        
            <label>Valor Material:</label>
            <input type="number" id="valor_padrao_material" name="valor_padrao_material" step="0.010" class="form-control" placeholder="" value="{{ $extraCurricular->valor_padrao_material ?? old('valor_padrao_atividade') }}">            
            <small>Informe o valor total anual.</small>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <label>Situação:</label><br>
            @if (isset($extraCurricular->situacao_atividade) && $extraCurricular->situacao_atividade == 1)
                <input type="checkbox" id="situacao_atividade" name="situacao_atividade" value="1" checked> 
            @else
                <input type="checkbox" id="situacao_atividade" name="situacao_atividade" value="0"> 
            @endif
            Ativar            
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">   
            * Campos obrigatórios.<br>
            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>
        </div>
    </div>
    
</div>

<script>
    document.getElementById("valor_padrao_atividade").addEventListener("change", function(){
        this.value = parseFloat(this.value).toFixed(2);
    });    
</script>
