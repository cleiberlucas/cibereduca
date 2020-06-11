

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Períodos Letivos')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="{{ route('periodosletivos.index') }} " class="">Períodos Letivos</a>
        </li>
    </ol>
    
    <h1>Períodos Letivos <a href="{{ route('periodosletivos.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <form action="{{ route('periodosletivos.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Período" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
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
                        @foreach ($periodosLetivos as $periodoLetivo)
                            <tr>
                                <td>
                                    {{$periodoLetivo->anoLetivo->ano}}
                                </td> 
                                <td>
                                    {{$periodoLetivo->periodo_letivo}}
                                </td>                   
                                <td>
                                    {{date('d/m/Y', strtotime($periodoLetivo->data_inicio))}}
                                </td>                   
                                <td>
                                    {{date('d/m/Y', strtotime($periodoLetivo->data_fim))}}
                                </td>                   
                                <td>
                                    @if ($periodoLetivo->situacao == 1)
                                        <b>Aberto</b>
                                    @else
                                        Encerrado                                        
                                    @endif                                    
                                </td>     
                                <td>
                                    {{date('d/m/Y H:m:i', strtotime($periodoLetivo->data_alteracao))}}
                                </td>                            
                                <td style="width=10px;">
                                    <a href="{{ route('periodosletivos.edit', $periodoLetivo->id_periodo_letivo) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('periodosletivos.show', $periodoLetivo->id_periodo_letivo) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $periodosLetivos->appends($filtros)->links()!!}
                    @else
                        {!! $periodosLetivos->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
