@extends('adminlte::page')

@section('title_postfix', ' Recebimento')

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
            <a href="#" class=""> Receber</a>
        </li>
    </ol>
    <br>
    <h4>Lançar Recebimento - {{$recebivel->descricao_conta}}</h4>
    <h5>Aluno: {{$recebivel->nome}} - {{$recebivel->tipo_turma}}</h5>
    <h6>Vencimento: {{date('d/m/Y', strtotime($recebivel->data_vencimento))}}</h6>
    <h6>Parcela: {{$recebivel->parcela}} Observações: {{$recebivel->obs_recebivel}} </h6>
    <h6>Valor principal: {{number_format($recebivel->valor_principal, 2, ',', '.')}} Desconto: {{number_format($recebivel->valor_desconto_principal, 2, ',', '.')}} 
        Valor Total: {{number_format($recebivel->valor_total, 2, ',', '.')}}</h6>
@stop

@section('content')
    <div class="container-fluid">
        
        @include('admin.includes.alerts')

        <form action="{{ route('recebimento.store', $recebivel->id_pessoa)}}" class="form" name="form" method="POST">
            @csrf                                            
            <input type="hidden" class="" id="fk_id_recebivel" name="fk_id_recebivel" value="{{$recebivel->id_recebivel}}">         
            <input type="hidden" class="" id="id_pessoa" name="id_pessoa" value="{{$recebivel->id_pessoa}}">         
            <input type="hidden" class="" id="fk_id_usuario_recebimento" name="fk_id_usuario_recebimento" value="{{Auth::id()}}">         
                                    
            <div class="form-group">
                <div class="row">
                    <div class="form-group col-sm-3 col-xs-4">
                        <label>Data Pagamento:</label>
                        <input type="date" class="form-control" required name="data_recebimento" value="{{ $recebivel->data_vencimento ?? old('data_vencimento') }}" />
                    </div>
                    <div class="form-group col-sm-3 col-xs-4">
                        <label>Data Crédito:</label>
                        <input type="date" class="form-control" required name="data_credito" value="{{ $recebivel->data_vencimento ?? old('data_vencimento') }}" />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3 col-xs-4">            
                        <label>Forma de pagamento</label>
                        <select name="fk_id_forma_pagamento" id="fk_id_forma_pagamento" required class="form-control">
                            <option value=""></option>
                            @foreach ($formasPagto as $formaPagto)
                                <option value="{{$formaPagto->id_forma_pagamento }}"
                                    @if (isset($matricula) && $formaPagto->id_forma_pagamento == $matricula->fk_id_forma_pagto_matricula)
                                        selected="selected"
                                    @endif
                                    >                    
                                    {{$formaPagto->forma_pagamento}}</option>
                            @endforeach
                        </select>
                    </div> 
                    <div class="col-sm-3">
                        <label>Valor recebido:</label>
                        <input type="number" name="valor_recebido" required step="0.010"  class="form-control" value="{{ $recebivel->valor_principal ?? old('valor_principal') }}">
                    </div>
                </div>              
                
            </div>
            <div class="row ">
                <div class="form-group col-sm-4 col-xs-2">     
                    Todos os Campos Obrigatórios<br>       
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
