

@extends('adminlte::page')


@section('title_postfix', ' Padrão de Turmas')

@section('content_header')
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposturmas.index') }} " class="">Padrões de Turmas</a>
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
        @include('admin.includes.alerts')
        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th>Ano</th>
                        <th>Padrão de Turma</th>
                        <th>Nível de Ensino</th>
                        <th>Valor Curso</th>
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
                                    R$ {{number_format($tipoturma->valor_curso, 2, ',', '.')}}
                                </td>                                                                                    
                                <td style="width=10px;">
                                    <a href="{{ route('tiposturmas.disciplinas', $tipoturma->id_tipo_turma) }}" class="btn btn-sm btn-outline-success"><i class="far fa-list-alt"></i></a>
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
    
<script>
    $(document).ready(function(){
          $(".alert").slideDown(300).delay(5000).slideUp(300);
    });    
</script>

@stop
