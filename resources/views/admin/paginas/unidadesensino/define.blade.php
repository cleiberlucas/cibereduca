<div class="d-none">    
    {{!! $unidadesensino = app(App\Http\Controllers\Admin\UnidadeEnsinoController::class)->unidadesEnsino(1) }}
</div>
      
<div class="form-group">    
    @if (count($unidadesensino) > 0)        
        <select name="unidadeensino" id="unidadeensino" class="form-control">
            @foreach ($unidadesensino as $unidadeensino)
                <option value="{{$unidadeensino->id_unidade_ensino}}">{{$unidadeensino->nome_fantasia}}</option>
                @if (count($unidadesensino) == 1)
                    {{ session()->put('id_unidade_ensino', $unidadeensino->id_unidade_ensino)}}
                @endif
            @endforeach
        </select>
    @else
        <h2> ATENÇÃO - Não há Unidade de Ensino ativa!</h2>
    @endif
</div>
