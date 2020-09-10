@extends('adminlte::page')

@section('title_postfix', ' Notas')

@section('content_header')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{route('turmas.index.notas')}} " class="">Turmas</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Notas</a>
        </li>
    </ol>
    @foreach ($turmaPeriodosLetivos as $index => $turma)
        @if ($index == 0)
            <h4>Lançamento de Notas - {{$turma->nome_turma}} {{$turma->sub_nivel_ensino}} - {{$turma->descricao_turno}} </h4>    
            @break            
        @endif
    @endforeach
@stop

@section('content')
    
    <div class="container-fluid">
        <div class="card-header">       
            <font size="2px">
                <i class="fas fa-pencil-alt"></i> - Período aberto &nbsp&nbsp&nbsp 
                <i class="fas fa-ban"></i> - Período fechado
            </font>   
        </div>
        <div>@include('admin.includes.alerts')</div>
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
                                
                                {{-- Listando alunos para lançamento das notas --}}
                                <div class="table-responsive">
                                    {{-- Listagem de alunos e notas --}}
                                    <form action="{{ route('turmas.notas.store', 1)}}" method="POST">
                                        @csrf 
                                        <input type="hidden" name="fk_id_periodo_letivo" value={{$turmaPeriodoLetivo->id_periodo_letivo}}>
                                        <input type="hidden" name="fk_id_disciplina" value={{$disciplinaTurma->fk_id_disciplina}}>
                                        <input type="hidden" name="fk_id_user" value={{Auth::id()}}>
                                        <input type="hidden" name="fk_id_turma" value={{$turmaPeriodoLetivo->fk_id_turma}}>
                                        <input type="hidden" name="id_periodo_letivo" value={{$turmaPeriodoLetivo->id_periodo_letivo}}>
                                        <br>
                                        <div class="row">
                                           
                                            <div class="form-group col-sm-5 col-xs-1" align="center">
                                                <br>
                                                <h5><strong>Lançar Notas: <font color="green">{{$turmaPeriodoLetivo->periodo_letivo}} - {{$disciplinaTurma->disciplina}}</font></strong></h5>
                                            </div>
                                            {{-- Mostra avaliações somente para períodos abertos --}}
                                            @if ($turmaPeriodoLetivo->situacao == 1)   
                                                
                                                <div class="form-group col-sm-3 col-xs-2">
                                                    <label for="">Avaliação</label>
                                                    <select name="fk_id_avaliacao[]" id="" class="form-control" required>        
                                                        <option value=""></option>
                                                        @foreach ($avaliacoes as $avaliacao)
                                                            
                                                            {{-- Mostra avaliações somente do período e da disciplina selecionada --}}
                                                            @if ($turmaPeriodoLetivo->id_periodo_letivo == $avaliacao->periodoLetivo->id_periodo_letivo && 
                                                                $disciplinaTurma->fk_id_disciplina == $avaliacao->fk_id_disciplina)
                                                                
                                                                <option value="{{$avaliacao->id_avaliacao }}">{{$avaliacao->tipoAvaliacao->tipo_avaliacao}} - Valor {{number_format($avaliacao->valor_avaliacao, 1, ',', '.')}}</option>
                                                            @endif
                                                        @endforeach

                                                    </select>
                                                </div>  

                                                <div class="form-group col-sm-3 col-xs-2">
                                                    <label for="">Data aplicação</label>
                                                    <input type="date" name="data_avaliacao" class="form-control">
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
                                                    <div class="form-group col-sm-2 col-xs-2"><strong>Nota</strong></div>  
                                                    
                                                </div>  
                                            @endif {{-- fim cabeçalho tabela --}}
                                            
                                            <input type="hidden" name="fk_id_matricula[]" value="{{$turmaMatricula->id_matricula}}">                                                            
                                                    
                                            <div class="row">
                                                <div class="form-group col-sm-1 col-xs-2">
                                                    <strong>{{$index+1}}  </strong>                                                                  
                                                </div>
                                                <div class="form-group col-sm-4 col-xs-2">                                                    
                                                    <a href="{{route('turmas.notas.showaluno', [$turmaMatricula->id_matricula])}}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                                    {{$turmaMatricula->nome}}
                                                </div>                                                                
                                                
                                                {{-- Libera lançamento de notas somente se o período estiver aberto --}}
                                                @if ($turmaPeriodoLetivo->situacao == 1)   
                                                    <div class="form-group col-sm-2 col-xs-2">
                                                        <input type="number" name="nota[]" id="nota[{{$turmaPeriodoLetivo->id_periodo_letivo}}{{$disciplinaTurma->fk_id_disciplina}}{{$turmaMatricula->id_matricula}}]" step="0.010" min=0 max=10 class="form-control">
                                                    </div>  
                                                @endif     

                                            </div>
                                        @endforeach {{-- fim listagem alunos --}}
                                        <div class="row">
                                            <div class="form-group col-sm-12 col-xs-1" align="left">
                                                <font color='green'>
                                                    Utilize os ícones <i class="fas fa-edit"></i> para alterar as notas de um aluno.
                                                </font>
                                            </div>
                                        </div>
                                        
                                        {{-- Libera lançamento de notas somente se o período estiver aberto --}}
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
