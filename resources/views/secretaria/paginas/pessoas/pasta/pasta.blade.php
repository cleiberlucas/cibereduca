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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
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
                                @if (isset($matricula->aluno->foto))
                                    <img src="{{url("storage/".$matricula->aluno->foto)}}" alt="" width="100" heigth="200">
                                @endif
                            </div>
                            <div class="p-2"> </div>
                        </div>
                        
                        <hr>
                        <div class="row">
                            <div class="form-group col-sm-3">
                                <a href="{{route('matriculas.ficha', $matricula->fk_id_aluno)}}" class="btn btn-outline-primary"><i class="fas fa-print"></i> Ficha de Matrícula</a>
                            </div>
                            <div class="form-group col-sm-3">
                                <a href="{{route('matriculas.documentos_escola', $matricula->fk_id_aluno)}}" class="btn btn-outline-primary"><i class="far fa-folder-open"></i> Declarações</a>
                            </div>
                        </div>
                        @include('admin.includes.alerts')
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
                                <a href="{{ route('contratos.extracurricular.create', $matricula->id_matricula) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-caret-right"></i><i class="fas fa-caret-right"></i>  Atividades ExtraCurriculares </a>
                                
                            </td> 
                            <td>
                                {{$matricula->situacaoMatricula->situacao_matricula}}                                        
                            </td>              
                            <td >
                                <?php $hash = preg_replace("/[^a-zA-Z0-9\s]/", "", crypt($matricula->id_matricula, 'cs'));?>  
                                <a href="{{ route('matriculas.edit', $matricula->id_matricula) }}" data-content="Editar Matrícula" data-toggle="popover" data-trigger="hover" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('financeiro.indexAluno', $matricula->fk_id_aluno) }}" data-content="Financeiro" data-toggle="popover" data-trigger="hover" class="btn btn-sm btn-outline-primary"> <i class="fas fa-dollar-sign"></i></a>                                    
                                <a href="{{ route('matriculas.documentos', $matricula->id_matricula) }}" data-content="Entrega documentos" data-toggle="popover" data-trigger="hover" class="btn btn-sm btn-outline-success"><i class="fas fa-tasks"></i></a>                                    
                                <a href="{{ route('matriculas.show', $matricula->id_matricula) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>                                    
                                <a href="{{ route('matriculas.contrato', [$matricula->id_matricula, $hash]) }}" data-content="Contrato" data-toggle="popover" data-trigger="hover" target="_blank" class="btn btn-sm btn-outline-dark"><i class="fas fa-file-contract"></i></a>
                                <a href="{{ route('matriculas.requerimento', $matricula->id_matricula) }}" data-content="Requerimento" data-toggle="popover" data-trigger="hover" target="_blank" class="btn btn-sm btn-outline-dark"><i class="fas fa-file-signature"></i></a>
                                {{-- <a href="" class="btn btn-sm btn-outline-warning"><i class="fas fa-address-book"></i></i></a> --}}
                            </td>
                            
                        </tr>
                        
                        {{-- Verificando se a matrícula tem contrato de atividade extracurricular --}}
                        @if (count($contratosExtraCurriculares) > 0)
                            <tr>
                                <td colspan=2>                                                                                       
                                <td><strong>Atividades Extracurriculares</strong> </td>
                                <td></td>
                            </tr>
                            {{-- Imprimindo atividades extracurriculares --}}
                            @foreach ($contratosExtraCurriculares as $i => $contratoExtraCurricular)    
                                {{-- Verificando as atividades de uma matrícula, ou seja, somente de um ano letivo --}}
                                @if ($contratoExtraCurricular->fk_id_matricula == $matricula->id_matricula)                            
                                    <tr>
                                        <td colspan=2></td>
                                        <td >
                                            {{$i+1}} - {{$contratoExtraCurricular->tipo_atividade_extracurricular}}
                                            <a href="{{ route('contratos.extracurricular.edit', $contratoExtraCurricular->id_contrato_atividade_extracurricular) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                        </td>
                                        @if ($contratoExtraCurricular->data_cancelamento == '')
                                            <td>Ativo</td>                                        
                                        @else
                                            <td>Cancelado</td>                                    
                                        @endif
                                        
                                    </tr>  
                                @endif                              
                            @endforeach                        
                        @endif
                @endforeach

                </tbody>
            </table>
                
        </div>
    </div>
    
    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
        $('[data-toggle="popover"]').popover();  
    </script>
@stop
