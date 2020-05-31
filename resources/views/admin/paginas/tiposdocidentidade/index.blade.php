

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Tipo Documento Identidade')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposdocidentidade.index') }} " class="">Tipo Documento Identidade</a>
        </li>
    </ol>
    
    <h1>Tipo Documento Identidade <a href="{{ route('tiposdocidentidade.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('tiposdocidentidade.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit"class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
        <div class="card-body">
                <table class="table table-condensed">
                    <thead>
                        <th>Tipo Documento Identidade</th>                        
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($tiposDocIdentidade as $tipoDocIdentidade)
                            <tr>
                                <td>
                                    {{$tipoDocIdentidade->tipo_doc_identidade}}
                                </td>                                 
                                <td style="width=10px;">
                                    <a href="{{ route('tiposdocidentidade.edit', $tipoDocIdentidade->id_tipo_doc_identidade) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('tiposdocidentidade.show', $tipoDocIdentidade->id_tipo_doc_identidade) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $tiposDocIdentidade->appends($filtros)->links()!!}
                    @else
                        {!! $tiposDocIdentidade->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
