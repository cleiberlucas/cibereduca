@extends('adminlte::page')



@section('title_postfix', ' Turmas')

@section('content_header')
<ol class="breadcrumb">
    <li class="breadcrumb-item active" >
        <a href="{{ route('turmas.index') }} " class="">Turmas</a>
    </li>
    <li class="breadcrumb-item active" >
        <a href="{{ route('turmasprofessor', $turmaProfessor->turma->id_turma) }}" > Professores</i></a>
    </li>
    <li class="breadcrumb-item active" >
        <a href="#" class="">Professor</a>
    </li>
</ol>
    
    <div class="row"> 
        <div class="form-group col-sm-9 col-sx-2">
            <h5>Editar Vínculo Disciplina X Professor</h5>    
        </div>        
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            {{-- <form action="{{ route('turmas.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Turma" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form> --}}
            
                <h5>Ano Letivo - {{$turmaProfessor->turma->tipoTurma->anoLetivo->ano}}</h5>
                <h5>{{$turmaProfessor->turma->nome_turma}} - {{$turmaProfessor->turma->turno->descricao_turno}}</h5>
                <h5>Disciplina: {{$turmaProfessor->GradeCurricular->disciplina->disciplina}}</h5>
                <h5>Professor: {{$turmaProfessor->professor->name}} </h5>
        </div>
        
        @include('admin.includes.alerts')
        @csrf
        
        <div class="container">
            <form action="{{ route('turmasprofessor.update', $turmaProfessor->id_turma_disciplina_professor)}}" class="form" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="fk_id_grade_curricular" value="{{$turmaProfessor->fk_id_grade_curricular}}">
                <input type="hidden" name="fk_id_turma" value="{{$turmaProfessor->fk_id_turma}}">
                <input type="hidden" name="fk_id_professor" value="{{$turmaProfessor->fk_id_professor}}">
                
                <div class="row mt-3">
                    <div class="form-group col-sm-4 col-xs-2">
                        <label>Data Entrada</label>
                        <input type="date" name="data_entrada" class="form-control" value="{{ $turmaProfessor->data_entrada ?? old('data_entrada') }}">
                    </div>

                    <div class="form-group col-sm-4 col-xs-2">
                        <label>Data Saída</label>
                        <input type="date" name="data_saida" class="form-control" value="{{ $turmaProfessor->data_saida ?? old('data_saida') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-4 col-xs-2">
                        @if (isset($turmaProfessor->situacao_disciplina_professor) && $turmaProfessor->situacao_disciplina_professor == 1)
                        <input type="checkbox" id="situacao_disciplina_professor" name="situacao_disciplina_professor" value="1" checked> 
                    @else
                        <input type="checkbox" id="situacao_disciplina_professor" name="situacao_disciplina_professor" value="0"> 
                    @endif
                    Ativar 
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-4 col-xs-2">                          
                        <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection