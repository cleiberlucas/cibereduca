<div class="container-fluid">

    @include('admin.includes.alerts')
    @csrf

    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">    
            <input type="hidden" name="fk_id_user" value="{{Auth::id()}}">        
            <label>Ano Letivo:</label>
            <select name="fk_id_ano_letivo" id="" class="form-control">
                <option value=""></option>
                @foreach ($anosLetivos as $anoletivo)
                    
                    <option value="{{$anoletivo->id_ano_letivo}}" 
                        @if (isset($periodoLetivo) && $anoletivo->id_ano_letivo == $periodoLetivo->fk_id_ano_letivo)
                            selected="selected"
                        @endif
                    >{{$anoletivo->ano}}</option>
                    
                @endforeach
            </select>
        </div>
    
        <div class="form-group col-sm-3 col-xs-2">
            <label>Período Letivo:</label>
            <input type="text" name="periodo_letivo" class="form-control" required placeholder="1º Bimestre" value="{{ $periodoLetivo->periodo_letivo ?? old('periodo_letivo') }}">        
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <label>Data início:</label>
            <input type="date" name="data_inicio" class="form-control" required value="{{ $periodoLetivo->data_inicio ?? old('data_inicio') }}">
        </div>
        <div class="form-group col-sm-3 col-xs-2">
            <label>Data fim:</label>
            <input type="date" name="data_fim" class="form-control" required value="{{ $periodoLetivo->data_fim ?? old('data_fim') }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <label>Quantidade dias letivos:</label>
            <input type="number" name="quantidade_dias_letivos" class="form-control" value="{{ $periodoLetivo->quantidade_dias_letivos ?? old('quantidade_dias_letivos') }}">
        </div>
    </div>

        <div class="form-group col-sm-3 col-xs-2">
                <label>Situação:</label><br>
                @if (isset($periodoLetivo->situacao) && $periodoLetivo->situacao == 1)
                    <input type="checkbox" id="situacao" name="situacao" value="1" checked> 
                @else
                    <input type="checkbox" id="situacao" name="situacao" value="0"> 
                @endif
                Abrir            
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">            
            * Todos os Campos Obrigatórios<br>
            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>
        </div>
    </div>

</div>
