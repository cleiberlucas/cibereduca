@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Matrículas')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('turmas.index') }} " class="">Turmas</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Matrículas</a>
        </li>
    </ol>
    <div class="row">
       
        <div class="col-sm-8 col-xs-6">
            <h1>Ano Letivo - {{$turma->ano}}</h1>
            <h1>Alunos Matriculados - {{$turma->nome_turma}} - {{$turma->descricao_turno}}</h1>
            <a href="{{ route('matriculas.create', $turma->id_turma) }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Nova Matrícula</a></h1>    
        </div>

        <div class="col-sm-4 col-xs-6">
            <h6>Vagas </h6>
            <h6>Limite: {{$turma->limite_alunos}}</h6>
            <h6>Matriculados: {{$quantMatriculas}}</h6>
            <h6><strong>Disponíveis: 
                @if ($quantVagasDisponiveis > 0)
                    <font color="green">
                @elseif ($quantVagasDisponiveis == 0)
                    <font color="#DF7401">
                @else
                    <font color="red"> 
                @endif
                {{$quantVagasDisponiveis}}
                </font>
            </strong>
            </h6>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @include('admin.includes.alerts')
       {{--  <div class="card-header">
            <form action="{{ route('matriculas.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Aluno" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div> --}}
        <div class="table-responsive ">
                <table class="table-sm  table-hover">
                    <thead>
                        <th>N°</th>
                        <th width="50%">Aluno</th>                                                
                        <th width="15%">Situação</th>
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($matriculas as $index => $matricula)
                            <tr>
                                <th scope="row">{{$index+1}}</th>                                
                                <td>
                                    <a href="{{ route('matriculas.edit', $matricula->id_matricula) }}" class="btn btn-link"> {{$matricula->aluno->nome}}</a>
                                </td> 
                                <td>
                                    {{$matricula->situacao_matricula}}
                                </td>                                                   
                                                                    
                                <td style="width=10px;">                                
                                    <a href="{{ route('matriculas.edit', $matricula->id_matricula) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('matriculas.documentos', $matricula->id_matricula) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-tasks"></i></a>                                    
                                    <a href="{{ route('matriculas.show', $matricula->id_matricula) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>                                    
                                    <a href="{{ route('matriculas.pasta', $matricula->fk_id_aluno) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-archive"></i></a>    
                                    {{-- <a href="{{ route('matriculas.contrato', $matricula->id_matricula) }}" target="_blank" class="btn btn-sm btn-outline-dark"><i class="fas fa-file-contract"></i></a>
                                    <a href="" class="btn btn-sm btn-outline-warning"><i class="fas fa-address-book"></i></i></a> --}}
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $matriculas->appends($filtros)->links()!!}
                    @else
                        {!! $matriculas->links()!!}    
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
