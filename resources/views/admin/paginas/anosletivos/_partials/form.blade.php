@include('admin.includes.alerts')
@csrf

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-2 col-xs-2">
            <div class="form-group">
                <label>Unidade de Ensino:</label>
                <input type="text" name="ano" class="form-control" placeholder="" value="{{ $ano_letivo->ano ?? old('ano') }} ">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2 col-xs-2">
            <div class="form-group">
                <label>Ano Letivo:</label>
                <input type="text" name="ano" class="form-control" placeholder="2020" value="{{ $ano_letivo->ano ?? old('ano') }} ">
            </div>
        </div>
        <div class="col-sm-2 col-xs-2">
            <div class="form-group">
                <label>Média Mínima Aprovação:</label>
                <input type="text" name="media_minima_aprovacao" class="form-control" placeholder="60" value="{{ $ano_letivo->media_minima_aprovacao ?? old('media_minima_aprovacao') }}">
            </div>
        </div>
        <div class="col-sm-2 col-xs-2">
            <div class="form-group">
                <label>Situação:</label><br>
                @if (isset($ano_letivo->situaca) && $ano_letivo->situacao == 1)
                    <input type="checkbox" id="situacao" name="situacao" value="1" checked> 
                @else
                    <input type="checkbox" id="situacao" name="situacao" value="0"> 
                @endif
                Ativar  
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2 col-xs-2">
            <div>
                <button type="submit" class="btn btn-dark">Enviar</button>
            </div>
        </div>
    </div>

</div>
