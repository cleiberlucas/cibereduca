

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Períodos Letivos')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('periodosletivos.index') }} " class="">Períodos Letivos</a>
        </li>
    </ol>
    
    <h1>Períodos Letivos <a href="{{ route('periodosletivos.create') }}" class="btn btn-success">Cadastrar</a></h1>    
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <form action="{{ route('periodosletivos.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Período" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-info">Filtrar</button>
            </form>
        </div>
        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th>Ano</th>
                        <th>Período Letivo</th>
                        <th>Data Início</th>
                        <th>Data Fim</th>
                        <th>Situação</th>
                        <th>Data Alteração</th>
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($periodosletivos as $periodoletivo)
                            <tr>
                                <td>
                                    {{$periodoletivo->anoLetivo->ano}}
                                </td> 
                                <td>
                                    {{$periodoletivo->periodo_letivo}}
                                </td>                   
                                <td>
                                    {{$periodoletivo->data_inicio}}
                                </td>                   
                                <td>
                                    {{$periodoletivo->data_fim}}
                                </td>                   
                                <td>
                                    @if ($periodoletivo->situacao == 1)
                                        <b>Aberto</b>
                                    @else
                                        Encerrado                                        
                                    @endif                                    
                                </td>     
                                <td>
                                    {{$periodoletivo->data_alteracao}}
                                </td>                            
                                <td style="width=10px;">
                                    <a href="{{ route('periodosletivos.edit', $periodoletivo->id_periodo_letivo) }}" class="btn btn-sm btn-primary">Editar</a>
                                    <a href="{{ route('periodosletivos.show', $periodoletivo->id_periodo_letivo) }}" class="btn btn-sm btn-info">VER</a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $periodosletivos->appends($filtros)->links()!!}
                    @else
                        {!! $periodosletivos->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
