@extends('adminlte::page')

@section('title_postfix', ' Tipos de Documentos')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposdocumentos.index') }} " class="">Tipos de Documentos</a>
        </li>
    </ol>
    
    <h1>Tipos de Documentos <a href="{{ route('tiposdocumentos.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('tiposdocumentos.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Documento" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit"class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
        <div class="card-body">
                <table class="table table-condensed">
                    <thead>
                        <th>Nome Documento</th>                        
                        <th>Obrigatório</th>
                        <th>Situação</th>
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($tiposDocumentos as $tipoDocumento)
                            <tr>
                                <td>
                                    {{$tipoDocumento->tipo_documento}}
                                </td> 
                                <td>
                                    @if ($tipoDocumento->obrigatorio == 1)
                                    <b>Sim</b>
                                @else
                                    Não
                                @endif
                                </td>                   
                                <td>
                                    @if ($tipoDocumento->situacao == 1)
                                        <b>Ativo</b>
                                    @else
                                        Inativo                                        
                                    @endif                                    
                                </td>              
                                <td style="width=10px;">
                                    <a href="{{ route('tiposdocumentos.edit', $tipoDocumento->id_tipo_documento) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('tiposdocumentos.show', $tipoDocumento->id_tipo_documento) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $tiposDocumentos->appends($filtros)->links()!!}
                    @else
                        {!! $tiposDocumentos->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
