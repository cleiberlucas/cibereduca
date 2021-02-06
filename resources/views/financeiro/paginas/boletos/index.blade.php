@extends('adminlte::page')

@section('title_postfix', ' Boletos')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Financeiro</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="{{ route('financeiro.index') }} " class=""> Recebíveis</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Boletos</a>
        </li>
    </ol>    
@stop

@section('content')    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <div class="container-fluid">
        @include('admin.includes.alerts')
        <div class="card-header">
            <h4>Boletos aluno(a)</h4>
            <div class="row">
                <div class="col-sm my-3">                                
                    <strong>{{$aluno->nome}}</strong>  <a href="{{ route('boleto.create', $aluno->id_pessoa) }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Lançar Boleto</a>                              
                </div>                
            </div> 
            {{-- <form action="{{ route('recebivel.aluno.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">                
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form> --}}
        </div>
        <form action="{{ route('boletos.imprimir')}}" class="form" name="form" method="POST">
            @csrf 
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th scope="col">#</th>
                        <th scope="col">Boleto</th> 
                        <th scope="col">Data Vencimento</th>                               
                        <th scope="col">Valor</th>        
                        <th scope="col">Data Pagamento</th>                        
                        <th scope="col">Situação</th>
                        <th scope="col">Ações</th>                    
                    </thead>
                    <tbody>                                  
                        @foreach ($boletos as $index => $boleto)
                            <?php
                            $hoje = date('Y-m-d');
                            /* Pago */
                            if ($boleto->fk_id_situacao_registro == 4)
                                echo '<tr bgcolor="#cef6d8">';
                            /* Em atraso */
                            elseif ($boleto->fk_id_situacao_registro <= 3 and strtotime($boleto->data_vencimento) < strtotime($hoje))
                                echo '<tr bgcolor="#F8E0E0">';
                            else 
                                echo '<tr>';
                            ?>
                                <th> <input type="checkbox" name="id_boleto[]" value="{{$boleto->id_boleto}}" checked></th>
                                  
                                <td>
                                    <?php 
                                        $textoPopover = '';
                                        foreach ($recebiveis as $recebivel)
                                        {
                                            if ($recebivel->fk_id_boleto == $boleto->id_boleto){
                                                $textoPopover .= $recebivel->descricao_conta. ' Parc. '.$recebivel->parcela.' R$ '.number_format($recebivel->valor_total, 2, ',', '.').', ';
                                            }
                                        }
                                        
                                    ?>
                                    <a href="#" onclick="return false;" class="disabled" data-content="{{$textoPopover}}"{{--  title="Recebíveis" --}} data-toggle="popover" data-trigger="hover" role="button" aria-disabled="true">Detalhes Boleto</a>                                        
                                </td>
                                <td>
                                    {{date('d/m/Y', strtotime($boleto->data_vencimento))}}
                                </td>  
                                <td>
                                    {{number_format($boleto->valor_total, 2, ',', '.')}}    
                                </td>                             
                                <td>
                                    @if (isset($boleto->data_recebimento))
                                        {{date('d/m/Y', strtotime($boleto->data_recebimento))}}    
                                    @endif                                
                                </td>           
                                <td>
                                    {{$boleto->situacao_registro}}
                                </td>  
                                <td>
                                    <a href="{{ route('boleto.imprimir', $boleto->id_boleto) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-print"></i></a>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    {{-- Só permite excluir se não foi pago --}}
                                    @if ($boleto->fk_id_situacao_registro != 4)
                                        <a href="{{ route('boleto.destroy', $boleto->id_boleto) }}" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                                    @endif
                                    
                                    {{-- Link para todos os boletos de um aluno em qq ano letivo --}}
                                    {{-- <a href="{{ route('boleto.indexAluno', $boleto->id_pessoa) }}" class="btn btn-sm btn-outline-dark"><i class="fas fa-barcode"></i></a> --}}
                                </td>                                                                           
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                @if (isset($filtros))
                    {!! $boletos->appends($filtros)->links()!!}
                @else
                    {!! $boletos->links()!!}     
                @endif                    
            </div>

            <div class="row ">
                <div class="form-group col-sm-4 col-xs-2">                         
                    <button type="submit" class="btn btn-info"><i class="fas fa-print"></i> Imprimir boletos selecionados</button>            
                </div>
            </div>
        </form>
        
    </div>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    

        $('[data-toggle="popover"]').popover();  
    </script>
    
@stop
