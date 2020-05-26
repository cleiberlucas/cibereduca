<div class="container-fluid">

    @include('admin.includes.alerts')
    @csrf

    <div class="row">
        <input type="hidden" name="fk_id_user" value="{{Auth::id()}}">
        <div class="form-group col-sm-1 col-xs-2">            
            <label>Ano letivo:</label>
            <select name="fk_id_ano_letivo" id="" class="form-control">
                <option value=""></option>
                @foreach ($anosLetivos as $anoletivo)
                    
                    <option value="{{$anoletivo->id_ano_letivo}}" 
                        @if (isset($tipoTurma) && $anoletivo->id_ano_letivo == $tipoTurma->fk_id_ano_letivo)
                            selected="selected"
                        @endif
                    >{{$anoletivo->ano}}</option>
                    
                @endforeach
            </select>
        </div>        
    </div>

    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <label>Nome padrão da turma:</label>
            <input type="text" name="tipo_turma" class="form-control" placeholder="1º Ano" value="{{ $tipoTurma->tipo_turma ?? old('tipo_turma') }}">        
        </div>
        
        <div class="form-group col-sm-3 col-xs-2">
        <label>Nível de Ensino:</label>
            <select name="fk_id_sub_nivel_ensino" id="" class="form-control">
                <option value=""></option>
                @foreach ($subNiveisEnsino as $subNivelEnsino)

                    <option value="{{$subNivelEnsino->id_sub_nivel_ensino}}" 
                        @if (isset($tipoTurma) && $subNivelEnsino->id_sub_nivel_ensino == $tipoTurma->fk_id_sub_nivel_ensino)
                            selected="selected"
                        @endif
                    >{{$subNivelEnsino->sub_nivel_ensino}}</option>
                    
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">
            <label>Valor padrão da mensalidade:</label>
            <input type="text" name="valor_padrao_mensalidade" class="form-control" placeholder="" value="{{ $tipoTurma->valor_padrao_mensalidade ?? old('valor_padrao_mensalidade') }}">        
        </div>
    </div>
    
    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">            
            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
        </div>
    </div>
</div>
