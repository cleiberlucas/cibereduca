

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
            <a href="#" class="">Conteúdos Lecionados</a>
        </li>
    </ol>
    @foreach ($turmaPeriodosLetivos as $index => $turma)
        @if ($index == 0)
            <h2>Contéudos Lecionados - {{$turma->nome_turma}} {{$turma->sub_nivel_ensino}} - {{$turma->descricao_turno}} </h2>    
        @endif
    @endforeach
@stop

@section('content')

   {{--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <div class="container-fluid">
        <div class="card-header">
          {{--   <form action="{{ route('turmas.conteudoslecionados.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Conteúdo" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form> --}}
        </div>
       
            {{-- Abas de Períodos --}}
            <ul class="nav nav-tabs nav-pills nav-fill justify-content-center" role="tablist">
                @foreach ($turmaPeriodosLetivos as $index => $periodoLetivo)                
                    <li role="presentation" class="nav-item">
                        <a class="nav-link " href="#{{$periodoLetivo->id_periodo_letivo}}" aria-controls="{{$periodoLetivo->id_periodo_letivo}}" role="tab" data-toggle="tab">{{$periodoLetivo->periodo_letivo}}</a>
                    </li>                        
                @endforeach
            </ul>

            <div class="tab-content">
                {{-- Abas de Períodos --}}
                @foreach ($turmaPeriodosLetivos as $index => $periodoLetivo)   
                    <div role="tabpanel" class="tab-pane " id="{{$periodoLetivo->id_periodo_letivo}}">     

                        {{-- Abas das disciplinas --}}
                        <ul class="nav nav-tabs nav-pills nav-fill justify-content-center" role="tablist">
                            @foreach ($disciplinasTurma as $index => $disciplinaTurma)                
                                <li role="presentation" class="nav-item">
                                    <a class="nav-link " href="#{{$periodoLetivo->id_periodo_letivo}}{{$disciplinaTurma->id_disciplina}}" aria-controls="{{$periodoLetivo->id_periodo_letivo}}{{$disciplinaTurma->id_disciplina}}" role="tab" data-toggle="tab">{{$disciplinaTurma->disciplina}}</a>
                                </li>                        
                            @endforeach
                        </ul> 

                        {{-- Conteúdo aba disciplinas --}}
                        <div class="tab-content">
                            @foreach ($disciplinasTurma as $index => $disciplinaTurma)

                                <div role="tabpanel" class="tab-pane " id="{{$periodoLetivo->id_periodo_letivo}}{{$disciplinaTurma->id_disciplina}}">
                                    
                                    {{-- Lançar Conteúdo lecionado --}}
                                    <form action="{{ route('conteudoslecionados.store')}}" class="form" method="POST">
                                        @csrf                                        
                                        <input type="hidden" name="fk_id_turma_periodo_letivo" value={{$periodoLetivo->id_turma_periodo_letivo}}>
                                        <input type="hidden" name="fk_id_disciplina" value={{$disciplinaTurma->id_disciplina}}>
                                        <input type="hidden" name="fk_id_user" value={{Auth::id()}}>
                                        <input type="hidden" name="fk_id_turma" value={{$periodoLetivo->fk_id_turma}}>

                                        <div class="row">
                                            <div class="form-group col-sm-2 col-xs-2">
                                                <label>Data aula:</label>
                                                <input type="date" name="data_aula" class="form-control" required>
                                            </div>
                                            <div class="form-group col-sm-9 col-xs-2">
                                                <label>Lançar Conteúdo Lecionado:</label><br>            
                                                <textarea name="conteudo_lecionado" id="" cols="110" rows="2">{{old('conteudo_lecionado')}}</textarea>
                                            </div>
                                            <div class="form-group col-sm-1 col-xs-2">                                                
                                                <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-forward"></i> Salvar</button>
                                            </div>
                                        </div>
                                    </form>
                                
                                    {{-- Listando conteúdos lançados de uma disciplina --}}
                                    <div class="table-responsive">

                                        <table class="table table-hover">
                                            <thead>                                              
                                                <th widht="5">Dias</th>                        
                                                <th>Alterar Conteúdos Lecionados</th>                        
                                                <th >Ações</th>
                                            </thead>
                                            <tbody>                        
                                                
                                                @foreach ($conteudosLecionados as $index => $conteudoLecionado)
                                                    {{-- Listar conteúdo somente da aba/disciplina selecionada --}}
                                                    @if ($conteudoLecionado->fk_id_disciplina == $disciplinaTurma->id_disciplina)
                                                        <form action="{{ route('conteudoslecionados.store')}}" class="form" method="POST">
                                                            @csrf 
                                                            <tr>                                                   
                                                                <td>
                                                                    {{date('d/m', strtotime($conteudoLecionado->data_aula))}}
                                                                </td>
                                                                <td>
                                                                    <textarea name="conteudo_lecionado" id="" cols="130" rows="2">{{$conteudoLecionado->conteudo_lecionado ?? old('conteudo_lecionado')}}</textarea>                                                                    
                                                                </td> 
                                                                
                                                                <td style="width=10px;">                                                                    
                                                                    <a href="{{ route('conteudoslecionados.store') }}" class="btn btn-sm btn-outline-success"><i class="fas fa-save"></i></a>
                                                                    <a href="{{ route('conteudoslecionados.store') }}" class="btn btn-sm btn-outline-success"><i class="fas fa-save"></i></a>
                                                                    {{--<a href="{{ route('turmas.show', $conteudoLecionado->id_turma) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a> --}}
                                                                </td>
                                                                
                                                            </tr>
                                                        </form>
                                                    @endif
                                                @endforeach
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
