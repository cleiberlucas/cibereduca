

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
    
    <h1>Anos Letivos <a href="{{ route('anosletivos.create') }}" class="btn btn-success"><i class="fas fa-plus-square"> </i> Cadastrar</a></h1>    
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('anosletivos.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Ano" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary"> <i class="fas fa-filter"></i></button>
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
                                        <b>Aberto</b>
                                    @else
                                        Encerrado                                        
                                    @endif
                                    
                                </td>              
                                <td style="width=10px;">
                                    <a href="{{ route('anosletivos.edit', $anoletivo->id_ano_letivo) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('anosletivos.show', $anoletivo->id_ano_letivo) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
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
