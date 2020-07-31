
@extends('adminlte::page')

@section('title_postfix', ' Turmas')

@section('content_header')
    
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="#" class="">Secretaria</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="" class="">Gerar Declaração</a>
        </li>
    </ol>
    <h4>Gerar Declaração</h4>
@stop

@section('content')
    <div class="container-fluid">

        @include('admin.includes.alerts')

        <form action="{{ route('matriculas.documentos_escola.gerar')}}" class="form" method="POST">
            @csrf            
            <input type="hidden" name="fk_id_user" value={{Auth::id()}}>

            <div class="row">
                <div class="form-group col-sm-2 col-xs-1">
                    Ano Letivo
                    <select class="form-control" name="anoLetivo" id="anoLetivo" required>
                        <option value=""></option>
                        @foreach ($anosLetivos as $anoLetivo)
                            <option value="{{$anoLetivo->id_ano_letivo}}">{{$anoLetivo->ano}}</option>
                            
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-3 col-xs-2">
                    Aluno
                    <select name="fk_id_matricula" id="fk_id_matricula" class="form-control" required > 
                        <option value=""></option>
                    </select>
                </div>            
            </div>            

            <hr>

            <div class="row">
                <h5>Escolha o tipo de Declaração</h5>             
            </div>
            
            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="declaracao" id="declaracao_cursando" value="declaracao_cursando">
                    <label for="declaracao_cursando" class="form-check-label">Cursando</label>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="declaracao" id="declaracao_transferencia" value="declaracao_transferencia" disabled>
                    <label for="declaracao_transferencia" class="form-check-label">Transferência</label>
                </div>
            </div>

            <br>

            <hr>
            <div class="row">
                <button type="submit" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir</button>
            </div>
        </form>
    </div>
    
    <script type="text/javascript" src="{!!asset('/js/populaAlunos.js')!!}"></script>
        
    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>
    
@stop