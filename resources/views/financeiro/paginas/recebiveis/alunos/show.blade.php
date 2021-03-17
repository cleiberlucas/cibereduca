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
            <a href="#" class=""> Ver</a>
        </li>
    </ol>
    <br>
    <h4>Detalhes de Recebível</h4>

    <h4>{{$recebivel->descricao_conta}} Parcela {{ $recebivel->parcela}} - Situação: 
        
        <?php
            $hoje = date('Y-m-d');
            /* Recebido */
            if ($recebivel->id_situacao_recebivel == 2)
                echo '<font color="green">'.$recebivel->situacao_recebivel.'</font>    ';
            /* Em atraso */
            elseif ($recebivel->id_situacao_recebivel == 1 and strtotime($recebivel->data_vencimento) < strtotime($hoje))
                echo '<font color="red">Em atraso - '.$recebivel->situacao_recebivel.'</font>    ';
            elseif ($recebivel->id_situacao_recebivel == 1)
                echo '<font>'.$recebivel->situacao_recebivel.' (a vencer)</font>    ';
            elseif ($recebivel->id_situacao_recebivel == 3)
                echo '<font>'.$recebivel->situacao_recebivel.'</font>    ';
        ?>
    </h4>

    <h5>Aluno: {{$recebivel->nome_aluno}} - {{$recebivel->nome_turma}}</h5>
    <h5>Responsável: {{$recebivel->nome_resp}}</h5>
    
@stop

@section('content')
    <div class="card">
        @include('admin.includes.alerts')
        <div class="card-header">
            
            {{-- <input type="hidden" class="" id="fk_id_usuario_cadastro" name="fk_id_usuario_cadastro" value="{{Auth::id()}}">          --}}
        
            <div class="row">
                <div class="col-sm-3">
                    <strong>Valor:</strong> R$ {{ number_format($recebivel->valor_principal, 2, ',', '.') }}
                </div>                
                <div class="col-sm-3">
                    <strong>Valor Desconto:</strong> R$ {{number_format($recebivel->valor_desconto_principal, 2, ',', '.')}}
                </div>
                <div class="col-sm-3">
                    <strong>Valor Total: R$ {{ number_format($recebivel->valor_total, 2, ',', '.') }} </strong>
                </div>
                
            </div>   
            <div class="row">
                <div class="col-sm-3">
                    <strong>Data Vencimento:</strong> {{ date('d/m/Y', strtotime($recebivel->data_vencimento)) }}
                </div>
                <div class="col-sm-8">
                    <strong>Observações:</strong> {{ $recebivel->obs_recebivel}}
                </div>  
            </div>
            <div class="row">
                <div class="col-sm-11">
                    <i>Lançado em {{date('d/m/Y H:i:s', strtotime($recebivel->data_cadastro))}} por {{$recebivel->name}}</i>
                </div>
            </div>

            <?php
                $total_recebido = 0;
            ?>
            <hr>
            {{-- Recebimento --}}
            
            @foreach ($formasPagamento as $index => $forma_pagto)
                @if (isset($forma_pagto))
                    @if ($index == 0)
                        <h5>Pagamento em: {{date('d/m/Y', strtotime($recebimento->data_recebimento))}}
                        @if ($forma_pagto->id_forma_pagamento != 2)
                            <a href="javascript:confirmaExcluiRecebimento({{$recebivel->id_recebivel}});" class="btn btn-sm btn-outline-danger"> <i class="fas fa-trash"></i> Remover Pagamento </a>    
                        @endif
                        </h5>
                        <h6>Data de crédito: {{date('d/m/Y', strtotime($recebimento->data_credito))}}</h6>
                        <h6>Número Recibo: {{$recebimento->numero_recibo}}</h6>
                        <h6>Código Validação: {{$recebimento->codigo_validacao}}</h6>        
                    @endif
            
                    <?php
                        $total_recebido += $forma_pagto->valor_recebido;                        
                    ?>  
                    <div class="row">
                        <div class="col-sm-4 col-xs-2 my-0" >  
                            <strong>Forma Pagamento: </strong> {{$forma_pagto->forma_pagamento}} 
                        </div>
                        <div class="col-sm-4 col-xs-2 my-0" >  
                            <strong>Valor Recebido: R$ {{number_format($forma_pagto->valor_recebido, 2, ',', '.')}} </strong>
                        </div>
                    </div>

                    @if ($index == count($formasPagamento)-1)
                        <div class="row py-1">
                            <div class="col-sm-3">
                                <strong>TOTAL RECEBIDO <?php echo number_format($total_recebido, 2, ',', '.'); ?></i></strong>
                            </div>
                            <div class="col-sm-3">
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-11">
                                <i>Recebido por {{$recebimento->name}} - Lançado em {{date('d/m/Y H:i:s', strtotime($recebimento->data_registra_recebimento))}}</i>
                            </div>
                        </div>
                        <hr>
                    @endif
                @endif                
            @endforeach
           
            {{-- Acréscimos --}}
            @foreach ($acrescimos as $ind => $acrescimo)
                @if (isset($acrescimo))
                    @if ($ind == 0)
                        <h5>Acréscimos</h5>
                    @endif
                    <div class="row pt-1">
                        <div class="col-sm-11">
                            <strong>{{$acrescimo->descricao_conta}}</strong> R$ {{number_format($acrescimo->valor_acrescimo, 2, ',', '.')}}                    
                            <strong>&nbsp&nbsp&nbsp Desconto:</strong> R$ {{number_format($acrescimo->valor_desconto_acrescimo, 2, ',', '.')}}  <strong>&nbsp&nbsp&nbsp Total:</strong> R$ {{number_format($acrescimo->valor_total_acrescimo, 2, ',', '.')}}
                        </div>
                    </div>                    
                @endif                
            @endforeach

        </div>
    </div>
        
    <script type="text/javascript" src="{!!asset('/js/confirmaExcluiRecebimento.js')!!}"></script>    

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });  
    </script>

@endsection
