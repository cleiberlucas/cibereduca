@extends('adminlte::page')

@section('title_postfix', ' Usuários')

@section('content_header')
    <ol class="breadcrumb">       
        <li class="breadcrumb-item active" >
            <a href="{{ route('users.index') }} " class="">Usuários</a>
        </li> 
        <li class="breadcrumb-item">
            <a href="">Vínculos Unidade Ensino</a>
        </li>
    </ol>              
    <h3>Vínculo com Unidades de Ensino </h3>
    <h3><strong>{{$user->name}}</strong>     </h3>
    
    {{-- <a href="{{ route('matriculas.permissoes.add', $user->id_matricula) }}" class="btn btn-dark">ADD permissão</a></h1> --}}

@stop

@section('content')
    {{-- Unidades vinculadas --}}
    <div class="card">
        {{-- <div class="card-header">
            <form action="{{ route('matriculas.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button class="submit" class="btn btn-dark">Filtrar</button>
            </form>
        </div> --}}
        <div class="card-body">
            <table class="table table-condensed">
                <thead>
                    <th>Unidades de Ensino vinculadas</th>    
                    <th>Situação vínculo</th>                    
                    <th width="570">Ações</th>
                </thead> 
                <tbody>                        
                    @foreach ($unidadesEnsino as $unidadeEnsino)
                        <form action="{{ route('users.unidadesensino.update', $unidadeEnsino->id_usuario_unidade_ensino) }}" method="POST">
                            @csrf
                            <tr>
                                <td>
                                    <font color="green">
                                        {{$unidadeEnsino->nome_fantasia}}
                                    </font>
                                </td>         
                                <td>                                
                                    @if (isset($unidadeEnsino->situacao_vinculo) && $unidadeEnsino->situacao_vinculo == 1)
                                        <input type="checkbox"  id="situacao_vinculo" name="situacao_vinculo" value="1" checked> 
                                    @else
                                        <input type="checkbox"  id="situacao_vinculo" name="situacao_vinculo" value="0"> 
                                    @endif
                                    Ativar                                 
                                </td>                       
                                <td style="width=10px;">       
                                    <button type="submit" class="btn btn-outline-success"><i class="fas fa-save"></i></button>                                                             
                                    {{-- <a href="{{ route('users.unidadesensino.update', $unidadeEnsino->id_usuario_unidade_ensino) }}" class="btn btn-outline-success"><i class="fas fa-save"></i></a>                                  --}}
                                </td>                            
                            </tr>
                        </form>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Unidades não vinculadas --}}
    @if (count($unidadesEnsinoLivres) > 0)
        <div class="card">        
            <div class="card-body">
                    <table class="table table-condensed">
                        <thead>
                            <th width="50px">#</th>
                            <th>Unidades Não vinculadas</th>                                                
                        </thead>
                        <tbody>                        
                            <form action="{{ route('users.unidadesensino.vincular', $user->id) }}" method="POST">
                                @csrf

                                @foreach ($unidadesEnsinoLivres as $unidadeEnsino)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="unidadesEnsino[]" value={{$unidadeEnsino->id_unidade_ensino}}>
                                        </td>                                   
                                        <td>
                                            {{$unidadeEnsino->nome_fantasia}}    
                                        </td>                
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan=500>
                                        @include('admin.includes.alerts')
                                        <button type="submit" class="btn btn-success"> Vincular Usuário</button>
                                    </td>
                                </tr>
                            </form>
                        </tbody>
                    </table>  
                </div>              
            </div>
        </div>
        @endif
@stop