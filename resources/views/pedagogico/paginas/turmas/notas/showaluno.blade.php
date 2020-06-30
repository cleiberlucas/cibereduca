@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Notas')

@section('content_header')
    
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{route('turmas.index.notas')}} " class="">Turmas</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{route('turmas.notas', $id_turma)}}" class="">Notas</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Aluno</a>
        </li>
        
    </ol>
    
    <h3>Aluno(a): {{$dadosAluno->aluno->nome}}</h3>
    <h4>{{$dadosAluno->turma->tipoTurma->anoLetivo->ano}} - {{$dadosAluno->turma->nome_turma}} - {{$dadosAluno->turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}} - {{$dadosAluno->turma->turno->descricao_turno}} </h3>
    Clique na nota para alterá-la, ou no ícone <i class="far fa-trash-alt"></i> para excluir.
    @stop

@section('content')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <div class="container-fluid">
        
        {{-- Separando as notas em abas de períodos letivos --}}
        <ul class="nav nav-tabs " role="tablist">
            @foreach ($periodosTurma as $periodoTurma)
                <li role="presentation" class="nav-item ">
                    <a class="nav-link" href="#{{$periodoTurma->id_periodo_letivo}}" aria-controls="{{$periodoTurma->id_periodo_letivo}}" role="tab" data-toggle="tab">{{$periodoTurma->periodo_letivo}}</a>
                </li>
            @endforeach
        </ul>
    
        {{-- Abas Períodos --}}
        <div class="tab-content">
            @foreach ($periodosTurma as $periodoTurma)
                <div role="tabpanel" class="tab-pane" id="{{$periodoTurma->id_periodo_letivo}}">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <th>#</th>
                                <th>Disciplina</th> 
                                {{-- Criando cabeçalho colunas com as avaliações --}}
                                @foreach ($avaliacoesTurma as $avaliacaoTurma)
                                    {{-- Listando todas avaliacoes da turma em um periodo letivo --}}
                                     @if ($avaliacaoTurma->periodo_letivo == $periodoTurma->periodo_letivo)
                                        <th>
                                            {{$avaliacaoTurma->tipo_avaliacao}}                                            
                                        </th>
                                    @endif
                                @endforeach {{-- fim colunas avaliações --}}    
                            </thead>

                            <tbody>         
                                {{-- criando linhas das disciplinas da grade curricular --}}  
                                @foreach ($gradeCurricular as $ind => $disciplina)                                    
                                    <tr>
                                        <td>{{$ind+1}}</td>
                                        <td>{{$disciplina->disciplina}}</td>

                                        {{-- criando as colunas de avaliações p receber informação da nota --}}                                            
                                        @foreach ($avaliacoesTurma as $avaliacaoTurma)
                                            {{-- somente avaliações de um período --}}
                                            @if ($avaliacaoTurma->periodo_letivo == $periodoTurma->periodo_letivo)
                                                <td>                                
                                                @foreach ($notasAluno as $notaAluno)                                                        
                                                    {{-- Mostrando nota de uma avaliação de uma disciplina --}}
                                                    @if ($notaAluno->fk_id_periodo_letivo == $periodoTurma->id_periodo_letivo
                                                        and $notaAluno->tipo_avaliacao == $avaliacaoTurma->tipo_avaliacao
                                                        and $notaAluno->fk_id_disciplina == $disciplina->fk_id_disciplina)
                                                        <a class="btn btn-sm btn-outline-danger" href="{{route('turmas.nota.remover', $notaAluno->id_nota_avaliacao)}}"><i class="far fa-trash-alt"></i></a>
                                                        &nbsp&nbsp&nbsp&nbsp&nbsp
                                                        <a href="{{route('turmas.nota.edit', $notaAluno->id_nota_avaliacao)}}">{{number_format($notaAluno->nota, 2, ',', '.')}}</a>
                                                        
                                                        @break                                                        
                                                    @endif                                                    
                                                @endforeach                                            
                                                </td> {{-- fechando coluna avaliação --}} 
                                            @endif                                                                                                
                                        @endforeach {{-- fim colunas avaliações --}}
                                    </tr>                                    
                                @endforeach {{-- fim linhas disciplinas --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach 
        </div> {{-- fim abas períodos --}}
    </div

@stop