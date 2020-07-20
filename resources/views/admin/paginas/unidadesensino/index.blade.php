@extends('adminlte::page')

@section('title', 'Rede Educa')

    @section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >                
            <a href="{{ route('unidadesensino.index') }} " class="">Unidades Ensino</a>
        </li>
    </ol> 

        <h1>Unidade Ensino <a href="{{ route('unidadesensino.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>
    @stop

    @section('content')
    
    <div class="container-fluid">
        
            <div class="box-header">
                <form action="{{ route('unidadesensino.search') }}" method="POST" class="form form-inline">
                    @csrf
                <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
                </form>
            </div>
            <div class="card-body">
                @include('admin.includes.alerts')
                <table class="table table-condensed">
                    <thead>
                        <th>Campus/Unidade</th>
                        <th>Telefone</th>
                        <th>Situação</th>
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>
                        @foreach ($unidadesensino as $unidadeEnsino)
                            <tr>
                                <td>
                                    {{$unidadeEnsino->nome_fantasia}}
                                </td>
                                <td>
                                    {{$unidadeEnsino->telefone}}
                                </td>
                                <td>
                                    @if ($unidadeEnsino->situacao == 1)
                                        <b>Ativo</b>
                                    @else
                                        Inativo                                        
                                    @endif
                                </td>
                                <td style="width=10px;">
                                    <a href="{{ route('unidadesensino.edit', $unidadeEnsino->id_unidade_ensino, false)}}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('unidadesensino.show', $unidadeEnsino->id_unidade_ensino, false) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>                                    
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $unidadesensino->appends($filtros)->links()!!}
                    @else
                        {!! $unidadesensino->links()!!}    
                    @endif
                        
                </div>
         
    </div>
@stop

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}

<script>
    $(document).ready(function(){
          $(".alert").slideDown(300).delay(5000).slideUp(300);
    });    
</script>