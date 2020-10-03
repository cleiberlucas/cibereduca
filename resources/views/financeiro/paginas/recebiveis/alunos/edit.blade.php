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
            <a href="{{ route('financeiro.indexAluno', $recebivel->id_pessoa) }}" class=""> Aluno</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Editar</a>
        </li>
    </ol>
    <br>
    <h4>Editar Recebível</h4>
    <h5>Aluno: {{$recebivel->nome}} - {{$recebivel->tipo_turma}}</h5>
    <br>
    <h5>{{$recebivel->descricao_conta}}</h5>

@stop

@section('content')
    <div class="container-fluid">
        
        @include('admin.includes.alerts')

        <form action="{{ route('financeiro.update', $recebivel->id_pessoa)}}" class="form" name="form" method="POST">
            @csrf                       
            @method('PUT')            
            <input type="hidden" class="" id="fk_id_usuario_cadastro" name="fk_id_usuario_cadastro" value="{{Auth::id()}}">         
                                    
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Valor:</label>
                        <input type="number" name="valor_principal" step="0.010" required readonly class="form-control" value="{{ $recebivel->valor_principal ?? old('valor_principal') }}">
                    </div>
                
                    <div class="col-sm-3">
                        <label>Valor Desconto:</label>
                    <input type="number" step="0.010" class="form-control" name="valor_desconto_principal" id="valor_desconto_principal" value="{{$recebivel->valor_desconto_principal ?? old('valor_desconto_principal')}}" onBlur="recalcularValorParcela({{$recebivel->valor_principal}}, this.value, 'valor_desconto_principal', 'valor_total')" />
                    </div>
                    <div class="col-sm-2">
                        <label>*Nº Parcela:</label>
                        <input type="text" class="form-control" maxlength="5" size="5" required name="parcela" value="{{ $recebivel->parcela ?? old('parcela') }}" />
                    </div>
                    <div class="col-sm-3">
                        <label>*Data Vencimento:</label>
                        <input type="date" class="form-control" required name="data_vencimento" value="{{ $recebivel->data_vencimento ?? old('data_vencimento') }}" />
                    </div>
                </div>   
                <div class="row pt-3">
                    <div class="col-sm-8">
                        <label>Observações:</label>
                        <input type="text" maxlength="250" class="form-control" name="obs_recebivel" value="{{ $recebivel->obs_recebivel ?? old('obs_recebivel') }}" />
                    </div>    
                    <div class="col-sm-3">
                        <label>Valor Total:</label>
                        <input type="number" step="0.010" class="form-control" required readonly id="valor_total" name="valor_total" value="{{ $recebivel->valor_total ?? old('valor_total') }}"/>
                    </div>
                </div>
                
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
    
    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });  
        
        document.getElementById("valor_principal").addEventListener("change", function(){
            this.value = parseFloat(this.value).toFixed(2);
        });

        document.getElementById("valor_desconto_principal").addEventListener("change", function(){
            this.value = parseFloat(this.value).toFixed(2);
        });

        document.getElementById("valor_total").addEventListener("change", function(){
            this.value = parseFloat(this.value).toFixed(2);
        });
    </script>

@endsection
