

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Turmas')

@section('content_header')
<ol class="breadcrumb">
    <li class="breadcrumb-item active" >
        <a href="{{ route('turmas.index') }} " class="">Turmas</a>
    </li>
    <li class="breadcrumb-item active" >
        <a href="#" class="">Professores</a>
    </li>
</ol>
    
    <div class="row"> 
        <div class="form-group col-sm-9 col-sx-2">
            <h5>Disciplinas X Professores</h5>    
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
            
                <h5>Ano Letivo - {{$turma->tipoTurma->anoLetivo->ano}}</h5>
                <h5>{{$turma->nome_turma}} - {{$turma->turno->descricao_turno}}</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>                        
                    <th>Disciplina</th>                        
                    <th>Professor</th>                    
                    <th>Ações</th>
                </thead>
                <tbody>
                    @foreach ($gradeCurricular as $index => $disciplina)
                        <tr>
                            <td>
                                {{$disciplina->disciplina->disciplina}}
                            </td>
                            <td>
                                <select name="fk_id_professor[".$index."]" id="fk_id_professor" class="form-control">            
                                    @foreach ($professores as $professor)
                                        <option value="{{$professor->id }}"> {{$professor->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td></td>
                            
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@stop