<div class="d-none">    
    {{!! $unidadeEnsino = app(App\Http\Controllers\Admin\UnidadeEnsinoController::class)->unidadeEnsino(session()->get('id_unidade_ensino')) }}
</div>
      
<div class="form-group">    
    
    @if (isset($unidadeEnsino))
        Unidade de Trabalho: {{$unidadeEnsino->nome_fantasia}}   
        <a href="{{route('home')}}" class=""> &nbsp&nbsp Alterar</a>
    @else
        <h2> ATENÇÃO! Selecione a Unidade de Ensino!</h2> 
        <a href="{{route('home')}}" class="">Definir Unidade de Ensino</a>        
    @endif
</div>
