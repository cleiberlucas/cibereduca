@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Frequências')

@section('content_header')
    
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{url('pedagogico/turmas')}} " class="">Diários</a>
        </li>
        <li class="breadcrumb-item active" >
        <a href="{{route('turmas.frequencias', $id_turma)}}" class="">Frequências</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Alterar</a>
        </li>
    </ol>
    @foreach ($frequenciasAlunoPeriodo as $index => $frequenciaAlunoPeriodo)
        @if ($index == 0)
            <h2>Aluno(a): {{$frequenciaAlunoPeriodo->nome}}</h2>
            <h3>Frequências {{$frequenciaAlunoPeriodo->periodo_letivo}} - {{$frequenciaAlunoPeriodo->nome_turma}} {{$frequenciaAlunoPeriodo->sub_nivel_ensino}} - {{$frequenciaAlunoPeriodo->descricao_turno}} </h3>
            @break
        @endif
    @endforeach
@stop

@section('content')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <div class="container-fluid">
        
        {{-- Separando as frequencias em abas de meses --}}
        <ul class="nav nav-tabs " role="tablist">
            @foreach ($frequenciasAlunoMesesPeriodo as $frequenciasAlunoMesPeriodo)
                <li role="presentation" class="nav-item ">
                    <a class="nav-link" href="#{{nomeMes($frequenciasAlunoMesPeriodo->mes)}}" aria-controls="{{nomeMes($frequenciasAlunoMesPeriodo->mes)}}" role="tab" data-toggle="tab">{{nomeMes($frequenciasAlunoMesPeriodo->mes)}}</a>
                </li>
            @endforeach
        </ul>
    
        {{-- Abas Meses --}}
        <div class="tab-content">
            @foreach ($frequenciasAlunoMesesPeriodo as $frequenciasAlunoMesPeriodo)
                <div role="tabpanel" class="tab-pane" id="{{nomeMes($frequenciasAlunoMesPeriodo->mes)}}">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <th>#</th>
                                <th>Disciplina</th> 
                                {{-- Criando cabeçalho colunas com as datas de frequencia --}}
                                @foreach ($frequenciasAlunoDatasPeriodo as $frequenciaAlunoDatasPeriodo)
                                    {{-- Listando somente aos dias de frequencias de um mês --}}
                                    @if (date('n', strtotime($frequenciaAlunoDatasPeriodo->data_aula)) == $frequenciasAlunoMesPeriodo->mes)
                                        <th>
                                            {{date('d', strtotime($frequenciaAlunoDatasPeriodo->data_aula))}}                                            
                                        </th>
                                    @endif
                                @endforeach    {{-- fim colunas datas frequencias --}}                

                            </thead>
                            <tbody>         
                                {{-- criando linhas das disciplinas que o aluno frequentou --}}  
                                @foreach ($frequenciasAlunoDisciplinasPeriodo as $ind => $frequenciaAlunoDisciplinasPeriodo)
                                    {{-- listando somente linhas de disciplinas de um mês --}}
                                {{--  @if (date('n', strtotime($frequenciasAlunoDisciplinasPeriodo->data_aula)) == $frequenciasAlunoMesPeriodo->mes) --}}
                                        <tr>
                                            <td>{{$ind+1}}</td>
                                            <td>{{$frequenciaAlunoDisciplinasPeriodo->disciplina}}</td>

                                            {{-- criando as colunas de datas p receber informação da frequencia --}}
                                            @foreach ($frequenciasAlunoDatasPeriodo as $ind => $frequenciaAlunoDatasPeriodo)     
                                                @if (date('n', strtotime($frequenciaAlunoDatasPeriodo->data_aula)) == $frequenciasAlunoMesPeriodo->mes)
                                                    <td>                                        
                                                @endif
                                                    {{-- carregando dados da frequencia do aluno Disciplina X DATA AULA --}}
                                                    @foreach ($frequenciasAlunoPeriodo as $index => $frequenciaAlunoPeriodo)
                                                        @if ($frequenciaAlunoPeriodo->fk_id_disciplina == $frequenciaAlunoDisciplinasPeriodo->id_disciplina 
                                                                and $frequenciaAlunoPeriodo->data_aula == $frequenciaAlunoDatasPeriodo->data_aula
                                                                and date('n', strtotime($frequenciaAlunoDatasPeriodo->data_aula)) == $frequenciasAlunoMesPeriodo->mes)
                                                            
                                                            {{-- Mostrando a frequencia do aluno --}}
                                                            {{$frequenciaAlunoPeriodo->sigla_frequencia}}
                                                            @break;

                                                        @endif
                                                    @endforeach
                                                
                                                @if (date('n', strtotime($frequenciaAlunoDatasPeriodo->data_aula)) == $frequenciasAlunoMesPeriodo->mes)
                                                    </td>                                        
                                                @endif                                       
                                            @endforeach {{-- fim colunas frequencias --}}
                                        </tr>
                                    {{-- @endif --}} {{-- fim verificacao linhas disciplinas de um mês --}}

                                @endforeach {{-- fim linhas disciplinas --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach {{-- fim abas meses --}}
        </div>
    </div

@stop