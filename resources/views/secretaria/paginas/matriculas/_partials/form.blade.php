<div class="container-fluid">

    @include('admin.includes.alerts')
    @csrf
    
    <div class="row">
       
            <fieldset  
                {{-- Desabilitando a escolha de aluno no caso de edição 
                    Evitar problema com troca de aluno em uma matrícula
                    --}}
                @if (isset($matricula))
                    disabled
                @endif
            >
            <div class="form-group col-sm-4 col-xs-2">
                <input type="hidden" name="fk_id_turma" value="{{$matricula->fk_id_turma ?? $turma->id_turma}}">
                <label>* Aluno:</label>
                <select name="fk_id_aluno" class="form-control" required>
                    <option value=""></option>
                    @foreach ($alunos as $aluno)
                        <option value="{{$aluno->id_pessoa }}"
                            @if (isset($matricula) && $aluno->id_pessoa == $matricula->fk_id_aluno)
                                selected="selected" 
                            @endif
                            >                    
                            {{$aluno->nome}}</option>
                    @endforeach
                </select>
            </div>
        </fieldset>
    </div>

    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">
            <label>* Responsável:</label>
            <select name="fk_id_responsavel" id="fk_id_responsavel" class="form-control" required>
                <option value=""></option>
                @foreach ($responsaveis as $responsavel)
                    <option value="{{$responsavel->id_pessoa }}"
                        @if (isset($matricula) && $responsavel->id_pessoa == $matricula->fk_id_responsavel)
                            selected="selected"
                        @endif
                        >                    
                        {{$responsavel->nome}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <h3>Matrícula</h3>
    <div class="row">
          
        <div class="form-group col-sm-3 col-xs-2">            
            <label>* Matrícula</label>
            <input type="date" name="data_matricula" class="form-control"  required value="{{ $matricula->data_matricula ?? old('data_matricula') }}">
        </div>  
        
        <div class="form-group col-sm-3 col-xs-2">            
            <label>* Limite desistência</label>
            <input type="date" name="data_limite_desistencia" class="form-control"  required value="{{ $matricula->data_limite_desistencia ?? old('data_limite_desistencia') }}">
        </div> 
              
    </div>

    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">            
            <label>* Valor Matrícula</label>
            <input type="number"  name="valor_matricula" id="valor_matricula" step="0.010" class="form-control"  required value="{{ $matricula->valor_matricula ?? old('valor_matricula') }}">
        </div>

        <div class="form-group col-sm-3 col-xs-2">            
            <label>Pagamento Matrícula</label>
            <input type="date" name="data_pagto_matricula" class="form-control"  value="{{ $matricula->data_pagto_matricula ?? old('data_pagto_matricula') }}">
        </div> 

        <div class="form-group col-sm-3 col-xs-4">            
            <label>Forma de pagamento</label>
            <select name="fk_id_forma_pagto_matricula" id="fk_id_forma_pagto_matricula" class="form-control">
                <option value=""></option>
                @foreach ($formasPagto as $formaPagto)
                    <option value="{{$formaPagto->id_forma_pagamento }}"
                        @if (isset($matricula) && $formaPagto->id_forma_pagamento == $matricula->fk_id_forma_pagto_matricula)
                            selected="selected"
                        @endif
                        >                    
                        {{$formaPagto->forma_pagamento}}</option>
                @endforeach
            </select>
        </div> 

    </div>

    <h3>Curso</h3>
    <div class="row">        
        <div class="form-group col-sm-3 col-xs-2">            
            <label>Valor Total Desconto</label>
            <input type="number" name="valor_desconto" id="valor_desconto" step="0.010" class="form-control"  value="{{ $matricula->valor_desconto ?? old('valor_desconto') }}">
        </div>  
        <div class="form-group col-sm-3 col-xs-2">            
            <label>Tipo Desconto Curso</label>
            <select name="fk_id_tipo_desconto_curso" id="fk_id_tipo_desconto_curso" class="form-control">
                <option value=""></option>
                @foreach ($tiposDesconto as $tipoDesconto)
                    <option value="{{$tipoDesconto->id_tipo_desconto_curso }}"
                        @if (isset($matricula) && $tipoDesconto->id_tipo_desconto_curso == $matricula->fk_id_tipo_desconto_curso)
                            selected="selected"
                        @endif
                        >                    
                        {{$tipoDesconto->tipo_desconto_curso}}</option>
                @endforeach
            </select>
        </div> 
    </div>

    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">            
            <label>* Quantidade parcelas</label>
            <input type="number" name="qt_parcelas_curso" class="form-control" required value="{{ $matricula->qt_parcelas_curso ?? old('qt_parcelas_curso') }}">
        </div>   
        <div class="form-group col-sm-3 col-xs-2">            
            <label>* Vencimento 1ª parcela</label>
            <input type="date" name="data_venc_parcela_um" class="form-control" required value="{{ $matricula->data_venc_parcela_um ?? old('data_venc_parcela_um') }}">
        </div> 
        <div class="form-group col-sm-3 col-xs-2">            
            <label>* Forma de Pagamento</label>
            <select name="fk_id_forma_pagto_curso" id="fk_id_forma_pagto_curso" class="form-control" required>
                <option value=""></option>
                @foreach ($formasPagto as $formaPagto)
                    <option value="{{$formaPagto->id_forma_pagamento }}"
                        @if (isset($matricula) && $formaPagto->id_forma_pagamento == $matricula->fk_id_forma_pagto_curso)
                            selected="selected"
                        @endif
                        >                    
                        {{$formaPagto->forma_pagamento}}</option>
                @endforeach
            </select>
        </div> 
    </div>

    <h3>Material Didático</h3>
    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">            
            <label>Valor Material Didático</label>
            <input type="number" name="valor_material_didatico" id="valor_material_didatico" step="0.010" class="form-control"  value="{{ $matricula->valor_material_didatico ?? old('valor_material_didatico') }}">
        </div>   
        <div class="form-group col-sm-3 col-xs-2">            
            <label>Quantidade parcelas</label>
            <input type="number" name="qt_parcelas_mat_didatico" class="form-control"  value="{{ $matricula->qt_parcelas_mat_didatico ?? old('qt_parcelas_mat_didatico') }}">
        </div>
        <div class="form-group col-sm-3 col-xs-2">            
            <label>Pagto Material Didático</label>
            <input type="date" name="data_pagto_mat_didatico" class="form-control"  value="{{ $matricula->data_pagto_mat_didatico ?? old('data_pagto_mat_didatico') }}">
        </div> 
        <div class="form-group col-sm-3 col-xs-2">            
            <label>Forma de Pagamento</label>
            <select name="fk_id_forma_pagto_mat_didatico" id="fk_id_forma_pagto_mat_didatico" class="form-control">
                <option value=""></option>
                @foreach ($formasPagto as $formaPagto)
                    <option value="{{$formaPagto->id_forma_pagamento }}"
                        @if (isset($matricula) && $formaPagto->id_forma_pagamento == $matricula->fk_id_forma_pagto_mat_didatico)
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
            <label>Atendimento Especializado</label>
            <select name="fk_id_atendimento_especializado" id="fk_id_atendimento_especializado" class="form-control">
                <option value=""></option>
                @foreach ($tiposAtendimentoEspecializado as $tipoAtendimentoEspecializado)
                    <option value="{{$tipoAtendimentoEspecializado->id_atendimento_especializado }}"
                        @if (isset($matricula) && $tipoAtendimentoEspecializado->id_atendimento_especializado == $matricula->fk_id_atendimento_especializado)
                            selected="selected"
                        @endif
                        >                    
                        {{$tipoAtendimentoEspecializado->atendimento_especializado}}</option>
                @endforeach
            </select>
        </div> 
    </div>

    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">            
            <label>* Situação Matrícula</label>
            <select name="fk_id_situacao_matricula" id="fk_id_situacao_matricula" class="form-control" required>
                <option value=""></option>
                @foreach ($situacoesMatricula as $situacaoMatricula)
                    <option value="{{$situacaoMatricula->id_situacao_matricula }}"
                        @if (isset($matricula) && $situacaoMatricula->id_situacao_matricula == $matricula->fk_id_situacao_matricula)
                            selected="selected"
                        @endif
                        >                    
                        {{$situacaoMatricula->situacao_matricula}}</option>
                @endforeach
            </select>
        </div> 
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-xs-2"> 
            <label for="">Observações</label>
            <textarea class="form-control" name="obs_matricula" id="" cols="100" rows="5">{{$matricula->obs_matricula ?? old('obs_matricula')}}</textarea>
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
    document.getElementById("valor_matricula").addEventListener("change", function(){
        this.value = parseFloat(this.value).toFixed(2);
    });

    document.getElementById("valor_desconto").addEventListener("change", function(){
        this.value = parseFloat(this.value).toFixed(2);
    });

    document.getElementById("valor_material_didatico").addEventListener("change", function(){
        this.value = parseFloat(this.value).toFixed(2);
    });
</script>
