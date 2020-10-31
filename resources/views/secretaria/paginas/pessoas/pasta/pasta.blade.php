@extends('adminlte::page')



@section('title_postfix', ' Arquivo do Aluno')

@section('content_header')
<ol class="breadcrumb">        
    <li class="breadcrumb-item active" >           
        <a href="{{ route('pessoas.index', 1) }} " class=""> Alunos </a>        
    </li>
    <li class="breadcrumb-item active" >
        <a href="#" class="">Arquivo do Aluno</a>
    </li>
</ol>
    
@stop

@section('content')
    <div class="container-fluid">
        {{-- <div class="card-header">
            <form action="{{ route('pessoas.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <input type="hidden" name="tipo_pessoa" value="{{$tipo_pessoa}}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div> --}}
        <div>
           
           @if (count($matriculas) == 0)
               Nenhuma matrícula gerada para este aluno.
           @endif
        </div>
            
        <div class="table-responsive">
           

                @foreach ($matriculas as $index => $matricula)
                    @if ($index == 0)
                        <div class="d-flex justify-content-between">
                            <div class="p-2">  
                                <h3>Arquivo do(a) Aluno(a)</h3>                          
                                <h3><b>{{$matricula->aluno->nome}}</b></h3>
                            </div>
                            <div class="p-2">
                                <img src="{{url("storage/".$matricula->aluno->foto)}}" alt="" width="100" heigth="200">
                            </div>
                            <div class="p-2"> </div>
                        </div>
                        @include('admin.includes.alerts')
                        <hr>
                        <div class="row">
                            <div class="form-group col-sm-3">
                                <a href="{{route('matriculas.ficha', $matricula->fk_id_aluno)}}" class="btn btn-outline-primary"><i class="fas fa-print"></i> Ficha de Matrícula</a>
                            </div>
                            <div class="form-group col-sm-3">
                                <a href="{{route('matriculas.documentos_escola', $matricula->fk_id_aluno)}}" class="btn btn-outline-primary"><i class="far fa-folder-open"></i> Declarações</a>
                            </div>
                        </div>

                        <table class="table table-hover">
                            <thead>
                                <td colspan=5>
                                    
                                </td>
                            </thead>                    
                            
                            <th scope="col">#</th>
                            <th scope="col">Ano</th>
                            <th scope="col">Matrícula</th>
                            <th scope="col">Situação</th>                        
                            <th width="270">Ações</th>
                            
                    @endif

                    <tbody>                        
                        <tr>
                            <th scope="row">{{$index+1}}</th>
                            <td>
                                <i class="fas fa-address-book"> </i> {{$matricula->turma->tipoTurma->anoLetivo->ano}}
                            </td>
                            <td>
                                <a href="{{ route('matriculas.edit', $matricula->id_matricula) }}" class="btn btn-link">  {{$matricula->turma->nome_turma}} - {{$matricula->turma->turno->descricao_turno}} </a>                                    
                            </td> 
                            <td>
                                {{$matricula->situacaoMatricula->situacao_matricula}}                                        
                            </td>              
                            <td >
                                <a href="{{ route('matriculas.edit', $matricula->id_matricula) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('matriculas.documentos', $matricula->id_matricula) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-tasks"></i></a>                                    
                                <a href="{{ route('matriculas.show', $matricula->id_matricula) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>                                    
                                <a href="{{ route('matriculas.contrato', $matricula->id_matricula) }}" target="_blank" class="btn btn-sm btn-outline-dark"><i class="fas fa-file-contract"></i></a>
                                <a href="{{ route('matriculas.requerimento', $matricula->id_matricula) }}" target="_blank" class="btn btn-sm btn-outline-dark"><i class="fas fa-file-signature"></i></a>
                                {{-- <a href="" class="btn btn-sm btn-outline-warning"><i class="fas fa-address-book"></i></i></a> --}}
                            </td>
                            
                        </tr>
                @endforeach

                </tbody>
            </table>
                
        </div>
    </div>
    
    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>
@stop
