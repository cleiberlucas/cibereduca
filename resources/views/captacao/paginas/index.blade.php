@extends('adminlte::page')

@section('title_postfix', ' Captações')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Captações</a>
        </li>
    </ol>
    
    <h1>Captações <a href="{{ route('captacao.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
@stop

@section('content')

    @include('admin.includes.alerts')
    
    <div class="card">
        <div class="card-header">
            <form action="{{ route('captacao.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" size="30" placeholder="Informe interessado ou aluno" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit"class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
        <div class="card-body">
                <table class="table table-responsive table-condensed">
                    <table class="table table-hover">
                        <thead>
                            <th>Ano Letivo</th>                        
                            <th>Interessado</th>                        
                            <th>Aluno</th>
                            <th>Série</th>
                            <th>Situação</th>                        
                            <th width="170">Ações</th>
                        </thead>
                        <tbody>   
                            @foreach ($captacoes as $captacao)                       
                                <tr>
                                    <td>
                                        {{$captacao->ano}}
                                    </td> 
                                    <td>
                                        {{$captacao->nome}}
                                    </td>                   
                                    <td>
                                        {{$captacao->aluno}}                                      
                                    </td>              
                                    <td>{{$captacao->serie_pretendida}}</td>
                                    <td>{{$captacao->tipo_negociacao}}</td>
                                    <td style="width=10px;">
                                        <a href="{{ route('historico.create', $captacao->id_captacao) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-paste"></i></a>
                                        <a href="{{ route('captacao.edit', $captacao->id_captacao) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('captacao.show', $captacao->id_captacao) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $captacoes->appends($filtros)->links()!!}
                    @else
                        {!! $captacoes->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
          $(".alert").slideDown(300).delay(5000).slideUp(300);
        });  
    </script>
@stop
