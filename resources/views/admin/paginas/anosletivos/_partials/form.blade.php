@include('admin.includes.alerts')
@csrf

<div class="container-fluid">
    
    <div class="row">        
        <div class="col-sm-5 col-xs-2">
            <input type="hidden" name="fk_id_user" value="{{Auth::id()}}">
            <div class="form-group">
                <label>Unidade de Ensino:</label>
                <select name="fk_id_unidade_ensino" id="" class="form-control">
                    <option value=""></option>
                    @foreach ($unidadesEnsino as $unidadeEnsino)
                        
                        <option value="{{$unidadeEnsino->id_unidade_ensino}}" 
                            @if (isset($anoLetivo) && $unidadeEnsino->id_unidade_ensino == $anoLetivo->fk_id_unidade_ensino)
                                selected="selected"
                            @endif
                        >{{$unidadeEnsino->nome_fantasia}}</option>
                        
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2 col-xs-2">
            <div class="form-group">
                <label>Ano Letivo:</label>
                <input type="number" name="ano" class="form-control" required placeholder="" value="{{ $anoLetivo->ano ?? old('ano') }}">
            </div>
        </div>
        <div class="col-sm-3 col-xs-2">
            <div class="form-group">
                <label>Média Mínima Aprovação:</label>
                <input type="number" name="media_minima_aprovacao" class="form-control" required placeholder="" value="{{ $anoLetivo->media_minima_aprovacao ?? old('media_minima_aprovacao') }}">
            </div>
        </div>
        <div class="col-sm-2 col-xs-2">
            <div class="form-group">
                <label>Situação:</label><br>
                @if (isset($anoLetivo->situacao) && $anoLetivo->situacao == 1)
                    <input type="checkbox" id="situacao" name="situacao" value="1" checked> 
                @else
                    <input type="checkbox" id="situacao" name="situacao" value="0"> 
                @endif
                Abrir
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-xs-2">
            <div class="form-group">
                * Todos os Campos Obrigatórios<br>
                <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>
            </div>
        </div>
    </div>

</div>
