<div class="container-fluid">

    @include('admin.includes.alerts')
    @csrf

    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Ano:</label>
            <input type="text" name="ano" class="form-control" placeholder="" value="">        
        </div>
    
        <div class="form-group col-sm-4 col-xs-2">
            <label>Período Letivo:</label>
            <input type="text" name="periodo_letivo" class="form-control" placeholder="1º Bimestre" value="{{ $periodoletivo->periodo_letivo ?? old('periodo_letivo') }} ">        
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <label>Data início:</label>
            <input type="date" name="data_inicio" class="form-control" value="{{ $periodoletivo->data_inicio ?? old('data_inicio') }}">
        </div>
        <div class="form-group col-sm-3 col-xs-2">
            <label>Data fim:</label>
            <input type="date" name="data_fim" class="form-control" value="{{ $periodoletivo->data_fim ?? old('data_fim') }}">
        </div>

        <div class="form-group col-sm-3 col-xs-2">
                <label>Situação:</label><br>
                @if (isset($periodoletivo->situacao) && $periodoletivo->situacao == 1)
                    <input type="checkbox" id="situacao" name="situacao" value="1" checked> 
                @else
                    <input type="checkbox" id="situacao" name="situacao" value="0"> 
                @endif
                Abrir            
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">            
            <button type="submit" class="btn btn-success">Enviar</button>            
        </div>
    </div>

</div>
