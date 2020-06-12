

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Desconto Curso')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('descontoscursos.index') }} " class="">Desconto Curso</a>
        </li>
    </ol>
    
    <h1>Desconto Curso <a href="{{ route('descontoscursos.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('descontoscursos.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit"class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
        <div class="card-body">
                <table class="table table-condensed">
                    <thead>
                        <th>Desconto Curso</th>                        
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($descontosCursos as $descontoCurso)
                            <tr>
                                <td>
                                    {{$descontoCurso->tipo_desconto_curso}}
                                </td>                                 
                                <td style="width=10px;">
                                    <a href="{{ route('descontoscursos.edit', $descontoCurso->id_tipo_desconto_curso) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('descontoscursos.show', $descontoCurso->id_tipo_desconto_curso) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $descontosCursos->appends($filtros)->links()!!}
                    @else
                        {!! $descontosCursos->links()!!}    
                    @endif                    
                </div>
        </div>
    </div>
@stop
