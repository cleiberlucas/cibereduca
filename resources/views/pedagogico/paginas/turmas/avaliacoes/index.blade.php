

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Conteúdos Lecionados')

@section('content_header')

    

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
        @endif
    @endforeach
@stop

@section('content')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <div class="container-fluid">
        <div class="card-header">          
            <i class="fas fa-pencil-alt"></i> - Período aberto &nbsp&nbsp&nbsp 
            <i class="fas fa-ban"></i> - Período fechado
        </div>
       
            {{-- Abas de Períodos --}}
            <ul class="nav nav-tabs nav-pills nav-fill justify-content-center" role="tablist">
                @foreach ($turmaPeriodosLetivos as $index => $turmaPeriodoLetivo)                
                    <li role="presentation" class="nav-item">
                        <a class="nav-link " href="#{{$turmaPeriodoLetivo->id_periodo_letivo}}" aria-controls="{{$turmaPeriodoLetivo->id_periodo_letivo}}" role="tab" data-toggle="tab">{{$turmaPeriodoLetivo->periodo_letivo}}
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
                            class="tab-pane " 
                        @endif
                        id="{{$turmaPeriodoLetivo->id_periodo_letivo}}">     

                        {{-- Abas das disciplinas: Todas a grade curricular da turma --}}
                        <ul class="nav nav-tabs nav-pills nav-fill justify-content-center" role="tablist">
                            @foreach ($disciplinasTurma as $index => $disciplinaTurma)                
                                <li role="presentation" class="nav-item ">
                                    <a class="nav-link " href="#{{$turmaPeriodoLetivo->id_periodo_letivo}}{{$disciplinaTurma->fk_id_disciplina}}" aria-controls="{{$turmaPeriodoLetivo->id_periodo_letivo}}{{$disciplinaTurma->fk_id_disciplina}}" role="tab" data-toggle="tab">{{$disciplinaTurma->disciplina}}</a>
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
                                        class="tab-pane " 
                                    @endif
                                    id="{{$turmaPeriodoLetivo->id_periodo_letivo}}{{$disciplinaTurma->fk_id_disciplina}}">
                                    
                                    {{-- Libera lançamento de conteúdo somente se o período estiver aberto --}}
                                    @if ($turmaPeriodoLetivo->situacao == 1)
                                        {{-- Lançar Conteúdo lecionado --}}
                                        <form action="{{ route('turmas.frequencias.store')}}" class="form" method="POST">
                                            @csrf                                        
                                            <input type="hidden" name="fk_id_turma_periodo_letivo" value={{$turmaPeriodoLetivo->id_turma_periodo_letivo}}>
                                            <input type="hidden" name="fk_id_disciplina" value={{$disciplinaTurma->fk_id_disciplina}}>
                                            <input type="hidden" name="fk_id_user" value={{Auth::id()}}>
                                            <input type="hidden" name="fk_id_turma" value={{$turmaPeriodoLetivo->fk_id_turma}}>
                                            <input type="hidden" name="id_periodo_letivo" value={{$turmaPeriodoLetivo->id_periodo_letivo}}>
                                            <div class="container-fluid">
                                                {{-- Listagem de alunos --}}
                                                {{-- <div class="row">
                                                    <div class="form-group col-sm-2 col-xs-2">
                                                        <label>Data aula:</label>
                                                        <input type="date" name="data_aula" min="{{$turmaPeriodoLetivo->data_inicio}}" max="{{$turmaPeriodoLetivo->data_fim}}"  class="form-control" required>
                                                    </div>
                                                    <div class="form-group col-sm-9 col-xs-2">
                                                        <label>Lançar Conteúdo Lecionado - <font color="blue">{{$turmaPeriodoLetivo->periodo_letivo}} - {{$disciplinaTurma->disciplina}}:</font></label><br>            
                                                        <textarea name="conteudo_lecionado" id="" cols="110" rows="2">{{old('conteudo_lecionado')}}</textarea>
                                                    </div>
                                                    <div class="form-group col-sm-1 col-xs-2 ">   
                                                        <br><br><br>                                             
                                                        <button type="submit" class="btn btn-outline-success"><i class="fas fa-save"></i></button>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </form>
                                    
                                    @endif
                                
                                    {{-- Listando conteúdos lançados de uma disciplina --}}
                                    <div class="table-responsive">

                                        <table class="table table-hover">
                                            <thead>                                              
                                                <th style="width=5px;">Dias</th>                        
                                                <th>Conteúdos Lecionados</th>                        
                                                <th >Ações</th>
                                            </thead>
                                            <tbody>                        
                                                
                                               {{--  @foreach ($conteudosLecionados as $index => $conteudoLecionado) --}}
                                                    {{-- Listar conteúdo somente da aba/disciplina selecionada --}}
                                                    {{-- @if ($turmaPeriodoLetivo->id_turma_periodo_letivo == $conteudoLecionado->fk_id_turma_periodo_letivo && $conteudoLecionado->fk_id_disciplina == $disciplinaTurma->fk_id_disciplina)
                                                        <form action="{{ route('turmas.frequencias.update', $conteudoLecionado->id_conteudo_lecionado)}}" method="POST">
                                                            @csrf 
                                                            @method('PUT')
                                                            <input type="hidden" name="fk_id_turma_periodo_letivo" value={{$turmaPeriodoLetivo->id_turma_periodo_letivo}}>
                                                            <input type="hidden" name="fk_id_disciplina" value={{$disciplinaTurma->fk_id_disciplina}}>
                                                            <input type="hidden" name="fk_id_user" value={{Auth::id()}}>
                                                            <input type="hidden" name="fk_id_turma" value={{$turmaPeriodoLetivo->fk_id_turma}}>
                                                            <input type="hidden" name="id_periodo_letivo" value={{$turmaPeriodoLetivo->id_periodo_letivo}}>

                                                            <tr>                                                   
                                                                <td>
                                                                    {{date('d/m', strtotime($conteudoLecionado->data_aula))}}
                                                                </td>
                                                                <td>
                                                                    @if ($turmaPeriodoLetivo->situacao == 1)                                                                    
                                                                        <textarea name="conteudo_lecionado" id="" cols="130" rows="1">{{$conteudoLecionado->conteudo_lecionado ?? old('conteudo_lecionado')}}</textarea>                                                                      
                                                                    @else
                                                                        {{$conteudoLecionado->conteudo_lecionado}}
                                                                    @endif
                                                                </td> 
                                                                
                                                                <td style="width=10px;">   
                                                                    @if ($turmaPeriodoLetivo->situacao == 1)                                                                 
                                                                        <button type="submit" class="btn btn-sm btn-outline-success"><i class="fas fa-save"></i></button> &nbsp&nbsp&nbsp
                                                                        <a href="{{ route('turmas.frequencias.remover', [$conteudoLecionado->id_conteudo_lecionado]) }}" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a> 
                                                                        {{-- <a href="{{ route('turmas.frequencias.update', $conteudoLecionado->id_conteudo_lecionado) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-save"></i></a> --}}
                                                                        {{-- <a href="{{ route('frequencias.store') }}" class="btn btn-sm btn-outline-success"><i class="fas fa-save"></i></a> --}}
                                                                    {{--@endif
                                                                    
                                                                </td>
                                                                
                                                            </tr>
                                                        </form>
                                                    @endif
                                                @endforeach --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
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
@stop
