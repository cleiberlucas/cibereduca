

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Anos Letivos')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('anosletivos.index') }} " class="">Anos Letivos</a>
        </li>
    </ol>
    
    <h1>Anos Letivos <a href="{{ route('anosletivos.create') }}" class="btn btn-dark">Cadastrar</a></h1>    
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('anosletivos.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button class="submit" class="btn btn-dark">Filtrar</button>
            </form>
        </div>
        <div class="card-body">
                <table class="table table-condensed">
                    <thead>
                        <th>Ano</th>
                        <th>Média aprovação</th>
                        <th>Situação</th>
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($anosletivos as $anoletivo)
                            <tr>
                                <td>
                                    {{$anoletivo->ano}}
                                </td> 
                                <td>
                                    {{$anoletivo->media_minima_aprovacao}}
                                </td>                   
                                <td>
                                    @if ($anoletivo->situacao == 1)
                                        <b>Em andamento</b>
                                    @else
                                        Encerrado                                        
                                    @endif
                                    
                                </td>              
                                <td style="width=10px;">
                                    <a href="{{ route('anosletivos.edit', $anoletivo->id_ano_letivo) }}" class="btn btn-info">Editar</a>
                                    <a href="{{ route('anosletivos.show', $anoletivo->id_ano_letivo) }}" class="btn btn-warning">VER</a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $anosletivos->appends($filtros)->links()!!}
                    @else
                        {!! $anosletivos->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
