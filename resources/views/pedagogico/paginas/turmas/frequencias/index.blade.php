@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Frequências')

@section('content_header')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{url('pedagogico/turmas')}} " class="">Diários</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Frequências</a>
        </li>
    </ol>
    @foreach ($turmaPeriodosLetivos as $index => $turma)
        @if ($index == 0)
            <h2>Frequências - {{$turma->nome_turma}} {{$turma->sub_nivel_ensino}} - {{$turma->descricao_turno}} </h2>    
            @break            
        @endif
    @endforeach
@stop

@section('content')

    <div class="container-fluid">
        
        @include('admin.includes.alerts')

        <div class="card-header">          
            <i class="fas fa-pencil-alt"></i> - Período aberto &nbsp&nbsp&nbsp 
            <i class="fas fa-ban"></i> - Período fechado
        </div>
       
        {{-- Abas de Períodos --}}
        <ul class="nav nav-tabs nav-pills nav-fill justify-content-center" role="tablist">
            @foreach ($turmaPeriodosLetivos as $index => $turmaPeriodoLetivo)                
                <li role="presentation" class="nav-item">
                    <a class="nav-link " href="#{{$turmaPeriodoLetivo->id_periodo_letivo}}" aria-controls="{{$turmaPeriodoLetivo->id_periodo_letivo}}" role="tab" data-toggle="tab">{{$turmaPeriodoLetivo->periodo_letivo}}
                        {{-- Informação se o período está aberto ou fechado --}}
                        @if ($turmaPeriodoLetivo->situacao == 0)
                            <i class="fas fa-ban"></i>
                        @else
                            <i class="fas fa-pencil-alt"></i>
                        @endif
                    </a>                    
                </li>                        
            @endforeach
        </ul>

        <div class="tab-content">
            {{-- Abas de Períodos --}}
            @foreach ($turmaPeriodosLetivos as $index => $turmaPeriodoLetivo)   
                <div role="tabpanel" 
                    @if (isset($selectPeriodoLetivo) && $selectPeriodoLetivo == $turmaPeriodoLetivo->id_periodo_letivo)
                        class="tab-pane active"         
                    @else
                        class="tab-pane" 
                    @endif
                    id="{{$turmaPeriodoLetivo->id_periodo_letivo}}">     

                    {{-- Abas das disciplinas: Todas a grade curricular da turma --}}
                    <ul class="nav nav-tabs nav-pills nav-fill justify-content-center" role="tablist">
                        @foreach ($disciplinasTurma as $index => $disciplinaTurma)                
                            <li role="presentation" class="nav-item ">
                                <a class="nav-link" href="#{{$turmaPeriodoLetivo->id_periodo_letivo}}{{$disciplinaTurma->fk_id_disciplina}}" aria-controls="{{$turmaPeriodoLetivo->id_periodo_letivo}}{{$disciplinaTurma->fk_id_disciplina}}" role="tab" data-toggle="tab">{{$disciplinaTurma->disciplina}}</a>
                            </li>                        
                        @endforeach
                    </ul> 

                    {{-- Conteúdo aba disciplinas --}}
                    <div class="tab-content">
                        @foreach ($disciplinasTurma as $index => $disciplinaTurma)

                            <div role="tabpanel" 
                                @if (isset($selectDisciplina) && $selectDisciplina == $disciplinaTurma->fk_id_disciplina)
                                    class="tab-pane active"         
                                @else
                                    class="tab-pane" 
                                @endif
                                id="{{$turmaPeriodoLetivo->id_periodo_letivo}}{{$disciplinaTurma->fk_id_disciplina}}">
                                
                                {{-- Listando frequências lançados de uma disciplina --}}
                                <div class="table-responsive">
                                    {{-- Listagem de alunos e frequencias --}}
                                    <form action="{{ route('turmas.frequencias.store', 1)}}" method="POST">
                                        @csrf 
                                        <input type="hidden" name="fk_id_periodo_letivo" value={{$turmaPeriodoLetivo->id_periodo_letivo}}>
                                        <input type="hidden" name="fk_id_disciplina" value={{$disciplinaTurma->fk_id_disciplina}}>
                                        <input type="hidden" name="fk_id_user" value={{Auth::id()}}>
                                        <input type="hidden" name="fk_id_turma" value={{$turmaPeriodoLetivo->fk_id_turma}}>
                                        <input type="hidden" name="id_periodo_letivo" value={{$turmaPeriodoLetivo->id_periodo_letivo}}>
                                        <br>
                                        <div class="row">
                                            <div class="form-group col-sm-7 col-xs-1" align="center">
                                                <h5><strong>Lançar Frequências <font color="blue">{{$turmaPeriodoLetivo->periodo_letivo}} - {{$disciplinaTurma->disciplina}}</font></strong></h5>
                                            </div>

                                            {{-- permite apagar frequência somente se o período estiver aberto --}}
                                            @if ($turmaPeriodoLetivo->situacao == 1)
                                                <div class="form-group col-sm-5 col-xs-1" align="center">
                                                    <h5>
                                                    <a href="{{route('turmas.frequencias.delete', [$turmaPeriodoLetivo->fk_id_turma, $turmaPeriodoLetivo->id_periodo_letivo, $disciplinaTurma->fk_id_disciplina])}}" class="btn btn-outline-danger"> <i class="fas fa-trash"></i>
                                                       Apagar Frequências {{$turmaPeriodoLetivo->periodo_letivo}} - {{$disciplinaTurma->disciplina}}   
                                                    </a>
                                                    
                                                    </h5>
                                                </div>
                                            @endif
                                        </div>                                        
                                        
                                        @foreach ($turmaMatriculas as $index => $turmaMatricula)

                                            {{-- Cabeçalho da tabela --}}
                                            @if ($index == 0)
                                                <div class="row">
                                                    <div class="form-group col-sm-1 col-xs-1">
                                                        <strong>N°</strong>
                                                    </div>
                                                    <div class="form-group col-sm-4 col-xs-2">
                                                        <strong>Nome do(a) Aluno(a)</strong>                                                        
                                                    </div>     
                                                    {{-- Libera lançamento de frequencia somente se o período estiver aberto --}}
                                                    @if ($turmaPeriodoLetivo->situacao == 1)                                                                                                                       
                                                        <div class="form-group col-sm-3 col-xs-2">
                                                            <input type="date" name="data_aula" autofocus min="{{$turmaPeriodoLetivo->data_inicio}}" max="{{$turmaPeriodoLetivo->data_fim}}" class="form-control" required>
                                                        </div>                                                        
                                                    @endif
                                                </div>  
                                            @endif {{-- fim cabeçalho tabela --}}
                                            
                                            <input type="hidden" name="fk_id_matricula[]" value="{{$turmaMatricula->id_matricula}}">                                                            
                                                    
                                            <div class="row">
                                                <div class="form-group col-sm-1 col-xs-2">
                                                    <strong>{{$index+1}}  </strong>                                                                  
                                                </div>
                                                <div class="form-group col-sm-4 col-xs-2">                                                    
                                                    <a href="{{route('turmas.frequencias.showaluno', [$turmaPeriodoLetivo->id_periodo_letivo, $turmaMatricula->id_matricula])}}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                                    {{$turmaMatricula->nome}}
                                                </div>                                                                
                                                
                                                {{-- Libera lançamento de frequencia somente se o período estiver aberto --}}
                                                @if ($turmaPeriodoLetivo->situacao == 1)   
                                                    <div class="form-group col-sm-2 col-xs-2">
                                                        <select name="fk_id_tipo_frequencia[]" id="" class="form-control" required>                                                                        
                                                            @foreach ($tiposFrequencia as $tipoFrequencia)
                                                                <option value="{{$tipoFrequencia->id_tipo_frequencia }}"
                                                                    @if ($tipoFrequencia->padrao == 1)
                                                                        selected="selected"
                                                                    @endif
                                                                    >                    
                                                                    {{$tipoFrequencia->tipo_frequencia}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>  
                                                @endif     

                                            </div>  
                                        
                                        @endforeach {{-- fim listagem alunos --}}
                                        
                                        <div class="row">
                                            <div class="form-group col-sm-12 col-xs-1" align="left">
                                                <font color='#0080FF'>
                                                    Utilize os ícones <i class="fas fa-edit"></i> para alterar as frequências de um aluno.
                                                </font>
                                            </div>
                                        </div>

                                        @if ($turmaPeriodoLetivo->situacao == 1)                                                                                                                                                                   
                                            <div class="form-group col-sm-2 col-xs-2">
                                                <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>
                                            </div>
                                        @endif
                                    </form>                                            
                                </div>                                    
                            </div>
                        @endforeach {{-- fim aba disciplinas --}}
                    </div>
                </div>
                
            @endforeach {{-- fim periodos letivos --}}
        </div> {{-- Fim nav períodos letivos --}}
                {{-- <div class="card-footer">
                    @if (isset($filtros))
                    {!! $conteudosLecionados->appends($filtros)->links()!!}
                    @else
                        {!! $conteudosLecionados->links()!!}    
                    @endif
                    
                </div> --}}
       {{--  </div> --}}
    </div>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>
@stop
