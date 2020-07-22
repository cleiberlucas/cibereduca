@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Frequências Remover')

@section('content_header')
    
    @foreach ($frequencias as $index => $frequencia)      
        @if ($index == 0)
            <ol class="breadcrumb">        
                <li class="breadcrumb-item active" >
                    <a href="#" class="">Pedagógico</a>
                </li>
                <li class="breadcrumb-item active" >
                    <a href="{{url('pedagogico/turmas')}} " class="">Diários</a>
                </li>
                <li class="breadcrumb-item active" >
                    <a href="{{route('turmas.frequencias', $frequencia->id_turma)}}" class="">Frequências</a>
                </li>
                <li class="breadcrumb-item active" >
                    <a href="#">Apagar</a>
                </li>
            </ol>
            
            <h3>Apagar frequência da turma</h3>
            <h4>{{$frequencia->periodo_letivo}} - {{$frequencia->nome_turma}} {{$frequencia->sub_nivel_ensino}} - {{$frequencia->descricao_turno}} </h4>
            
            <?php 
                $idTurmaPeriodoLetivo = $frequencia->fk_id_turma_periodo_letivo;                
                $idDisciplina = $frequencia->fk_id_disciplina;
                $disciplina = $frequencia->disciplina;
                $situacaoPeriodo = $frequencia->situacao;
            ?>
        @endif                
        
    @endforeach
       
@stop

@section('content')
    <hr>

    <div>@include('admin.includes.alerts')</div>
    

    <div class="" style="color: red">
        <strong>Atenção: serão removidas as presenças de todos os alunos da turma, para a data selecionada (somente de uma disciplina).</strong>
    </div>
    <br>
    <div class="container-fluid">
        <h5>Disciplina: <strong>{{$disciplina}}</strong></h5>
        <br>
        <div class="row">
            <div class="form-group col-sm-1 col-xs-1">
                <strong>#</strong>
            </div>
            <div class="form-group col-sm-2 col-xs-2">
                <strong>Data aula</strong>
            </div>
        </div>
        @foreach ($frequenciasDatas as $index => $frequencia)
            
            <div class="row">
                <div class="form-group col-sm-1 col-xs-2">
                    {{$index+1}}
                </div>
                <div class="form-group col-sm-4 col-xs-2">
                    {{date('d/m/Y', strtotime($frequencia->data_aula))}}
                    {{-- Permite exclusão somente se o período estiver aberto --}}
                    @if ($situacaoPeriodo == 1)
                        <a href="{{route('turmas.frequencias.remover', [$idTurmaPeriodoLetivo, $frequencia->data_aula, $idDisciplina])}}" class="btn btn-sm btn-outline-danger"> <i class="fas fa-trash"></i> Apagar</a> 
                    @endif
                
                </div>
            </div>
     
        @endforeach

    </div>  
    <div class="card-footer">
        @if (isset($filtros))
        {!! $frequenciasDatas->appends($filtros)->links()!!}
        @else
            {!! $frequenciasDatas->links()!!}    
        @endif
        
    </div>  
    
    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>

@stop
