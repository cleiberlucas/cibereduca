@extends('adminlte::page')

@section('title_postfix', ' Recebíveis')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Financeiro</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="{{ route('financeiro.index') }} " class=""> Recebíveis</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="{{ route('financeiro.indexAluno', $aluno->id_pessoa) }}" class=""> Aluno</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Lançar</a>
        </li>
    </ol>
    <br>
    <h4>Lançar Recebível</h4>
    <h4>Aluno: {{$aluno->nome}}</h4>
@stop

@section('content')
    <div class="container-fluid">
        
        @include('admin.includes.alerts')

        <form action="{{ route('financeiro.store')}}" class="form" name="form" method="POST">
            @csrf                       
            <input type="hidden" class="" id="fk_id_unidade_ensino" name="fk_id_unidade_ensino" value="{{$unidadeEnsino}}">
            <input type="hidden" class="" id="fk_id_usuario_cadastro" name="fk_id_usuario_cadastro" value="{{Auth::id()}}">
            <input type="hidden" class="" id="fk_id_situacao_recebivel" name="fk_id_situacao_recebivel" value="1">
            <input type="hidden" class="" id="id_pessoa" name="id_pessoa" value="{{$aluno->id_pessoa}}">

            <input type="hidden" class="" id="valor_matricula" name="valor_matricula" value="">
            <input type="hidden" class="" id="valor_curso" name="valor_curso" value="">
            <input type="hidden" class="" id="valor_desconto" name="valor_desconto" value="">
            <input type="hidden" class="" id="qt_parcelas_curso" name="qt_parcelas_curso" value="">
            <input type="hidden" class="" id="data_venc_parcela_um" name="data_venc_parcela_um" value="">
            
            <input type="hidden" class="" id="valor_material_didatico" name="valor_material_didatico" value="">
            <input type="hidden" class="" id="qt_parcelas_mat_didatico" name="qt_parcelas_mat_didatico" value="">
            <input type="hidden" class="" id="data_venc_parcela_um_mat_didatico" name="data_venc_parcela_um_mat_didatico" value="">
                        
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-4">
                        <label>*Matrícula:</label>
                        <select name="fk_id_matricula" id="fk_id_matricula" class="form-control">
                            <option value=""></option>
                            @foreach ($matriculas as $matricula)
                                <option value="{{$matricula->id_matricula}}"> {{$matricula->tipo_turma}}</option>
                            @endforeach
                        </select>        
                    </div>
                
                    <div class="col-sm-4">
                        <label>*Recebível:</label>
                        <select name="fk_id_conta_contabil_principal" id="fk_id_conta_contabil_principal" class="form-control">
                            <option value=""></option>
                            @foreach ($contasContabeis as $contaContabil)
                                <option value="{{$contaContabil->id_conta_contabil}}"> {{$contaContabil->descricao_conta}}</option>
                            @endforeach
                        </select> 
                    </div>
                </div>   
                <div class="row pt-3">
                    <div class="form-group col-sm-12" id="valores"></div>
                </div> 
                <hr>
                <div class="row" id="campos">  </div>
            </div>
            <div class="row ">
                <div class="form-group col-sm-4 col-xs-2">     
                    * Campos Obrigatórios<br>       
                    <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript" src="{!!asset('/js/utils.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('/js/valoresRecebiveis.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('/js/camposRecebiveis.js')!!}"></script>
    
    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });  
        
       /*  document.getElementById("valor_principal").addEventListener("change", function(){
            this.value = parseFloat(this.value).toFixed(2);
        }); */
    </script>

@endsection
