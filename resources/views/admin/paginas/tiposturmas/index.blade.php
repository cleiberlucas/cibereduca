

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Padrão de Turmas')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposturmas.index') }} " class="">Padrão de Turmas</a>
        </li>
    </ol>
    
    <h1>Padrão de Turmas <a href="{{ route('tiposturmas.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <form action="{{ route('tiposturmas.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Turma" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th>Ano</th>
                        <th>Padrão de Turma</th>
                        <th>Nível de Ensino</th>
                        <th>Valor padrão mensalidade</th>
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($tiposturmas as $tipoturma)
                            <tr>
                                <td>
                                    {{$tipoturma->anoLetivo->ano}}
                                </td> 
                                <td>
                                    {{$tipoturma->tipo_turma}}
                                </td>                   
                                <td>
                                    {{$tipoturma->subNivelEnsino->sub_nivel_ensino}}
                                </td>                   
                                <td>
                                    R$ {{number_format($tipoturma->valor_padrao_mensalidade, 2, ',', '.')}}
                                </td>                                                                                    
                                <td style="width=10px;">
                                    <a href="{{ route('tiposturmas.edit', $tipoturma->id_tipo_turma) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('tiposturmas.show', $tipoturma->id_tipo_turma) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $tiposturmas->appends($filtros)->links()!!}
                    @else
                        {!! $tiposturmas->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
