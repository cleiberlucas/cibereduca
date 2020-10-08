@extends('adminlte::page')



@section('title_postfix', ' Grades Curriculares')

@section('content_header')
<ol class="breadcrumb">    
    <li class="breadcrumb-item active" >
        <a href="{{ route('tiposturmas.index') }} " class="">Padrões de Turmas</a>
    </li>
    <li class="breadcrumb-item">
        <a href="#" class="">Grade Curricular</a>
    </li>
</ol>

    <div align="center"><h1><strong>Grade Curricular</strong></h1></div>
    <h1><strong>Ano Letivo: </strong>{{$tipoTurma->anoLetivo->ano}}</h1>
    <h1><Strong>Padrão de Turma: </Strong>{{$tipoTurma->tipo_turma}} - {{$tipoTurma->subNivelEnsino->sub_nivel_ensino}}</h1>
    
    <h1><a href="{{ route('tiposturmas.disciplinas.add', $tipoTurma->id_tipo_turma) }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Adicionar disciplina</a></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            {{-- <form action="{{ route('gradescurriculares.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit"class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form> --}}
        </div>
        <div class="card-body">
            @include('admin.includes.alerts')
                <table class="table table-condensed">
                    <thead>
                        <th>#</th>
                        <th width="300px">Disciplina</th>
                        <th width="250px">Carga horária anual</th>                        
                        <th width="550px" >Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($disciplinas as $index => $disciplina)
                            <tr>
                                <th scope="row">{{$index+1}}</th>
                                <td>
                                    {{$disciplina->disciplina}}
                                </td> 
                                <form action="{{ route('tiposturmas.disciplinas.update', $disciplina->id_grade_curricular)}}" class="form" method="POST">
                                    @csrf
                                    <td>
                                        <input type="number" name="carga_horaria_anual" required class="form-control" value="{{$disciplina->carga_horaria_anual ?? old('carga_horaria_anual') }}">                                                                         
                                    </td>                                                                 
                                    <td>                                    
                                        <button type="submit" class="btn btn-outline-success"><i class="fas fa-save"></i></button>
                                    
                                </form>
                                    &nbsp&nbsp&nbsp&nbsp

                                    <a href="{{ route('tiposturmas.disciplinas.remover', [$tipoTurma->id_tipo_turma, $disciplina->id_disciplina]) }}" class="btn btn-outline-danger"><i class="far fa-trash-alt"></i></a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $disciplinas->appends($filtros)->links()!!}
                    @else
                        {!! $disciplinas->links()!!}    
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
