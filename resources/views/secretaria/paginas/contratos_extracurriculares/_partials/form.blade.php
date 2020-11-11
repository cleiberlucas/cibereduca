
<div class="container-fluid">

    @include('admin.includes.alerts')
    @csrf
    
    <input type="hidden" name="fk_id_matricula" value="{{$fk_id_matricula}}">
    <input type="hidden" name="fk_id_usuario_cadastro" value="{{Auth::id()}}">

    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">
            <label>* Atividade ExtraCurricular:</label>
            <select name="fk_id_tipo_atividade_extracurricular" id="" class="form-control" required 
                @if (isset($contratoExtraCurricular->fk_id_tipo_atividade_extracurricular))
                    disabled        
                @endif
            >
                <option value=""></option>
                @foreach ($atividadesExtraCurriculares as $atividadeExtraCurricular)                    
                    <option value="{{$atividadeExtraCurricular->id_tipo_atividade_extracurricular}}" 
                        @if (isset($contratoExtraCurricular) && $atividadeExtraCurricular->id_tipo_atividade_extracurricular == $contratoExtraCurricular->fk_id_tipo_atividade_extracurricular)
                            selected="selected" 
                        @endif
                    >{{$atividadeExtraCurricular->tipo_atividade_extracurricular}}</option>
                    
                @endforeach
            </select>
        </div>    

        <div class="form-group col-sm-3 col-xs-2">            
            <label>* Contratação</label>
            <input type="date" name="data_contratacao" class="form-control"  required value="{{ $contratoExtraCurricular->data_contratacao ?? old('data_contratacao') }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">                        
            <label>Valor Total Curso:</label>
            <input type="number" id="valor_curso" name="valor_curso" step="0.010" class="form-control" placeholder="" value="{{ $contratoExtraCurricular->valor_curso ?? old('valor_atividade') }}">                            
        </div>
        <div class="form-group col-sm-4 col-xs-2">            
            <label>Forma de Pagamento Curso</label>
            <select name="fk_id_forma_pagto_ativ" id="fk_id_forma_pagto_ativ" class="form-control" >
                <option value=""></option>
                @foreach ($formasPagto as $formaPagto)
                    <option value="{{$formaPagto->id_forma_pagamento }}"
                        @if (isset($contratoExtraCurricular) && $formaPagto->id_forma_pagamento == $contratoExtraCurricular->fk_id_forma_pagto_ativ)
                            selected="selected"
                        @endif
                        >                    
                        {{$formaPagto->forma_pagamento}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">

        <div class="form-group col-sm-3 col-xs-2">                        
            <label>Valor Total Material:</label>
            <input type="number" id="valor_material" name="valor_material" step="0.010" class="form-control" placeholder="" value="{{ $contratoExtraCurricular->valor_material ?? old('valor_atividade') }}">                            
        </div>

        <div class="form-group col-sm-4 col-xs-2">            
            <label>Forma de Pagamento Material</label>
            <select name="fk_id_forma_pagto_material" id="fk_id_forma_pagto_material" class="form-control" >
                <option value=""></option>
                @foreach ($formasPagto as $formaPagto)
                    <option value="{{$formaPagto->id_forma_pagamento }}"
                        @if (isset($contratoExtraCurricular) && $formaPagto->id_forma_pagamento == $contratoExtraCurricular->fk_id_forma_pagto_material)
                            selected="selected"
                        @endif
                        >                    
                        {{$formaPagto->forma_pagamento}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Quantidade parcelas</label> 
            <input type="number" name="quantidade_parcelas" class="form-control" value="{{ $contratoExtraCurricular->quantidade_parcelas ?? old('quantidade_parcelas') }}">
        </div>  
        <div class="form-group col-sm-3 col-xs-2">            
            <label>Vencimento 1ª parcela</label>
            <input type="date" name="data_venc_parcela_um" class="form-control" value="{{ $contratoExtraCurricular->data_venc_parcela_um ?? old('data_venc_parcela_um') }}">
        </div> 
    </div>
    
    <div class="row">
        <div class="form-group col-sm-12 col-xs-2"> 
            <label for="">Observações</label>
            <textarea class="form-control" name="observacao" id="" cols="100" rows="5">{{$contratoExtraCurricular->observacao ?? old('observacao')}}</textarea>
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
