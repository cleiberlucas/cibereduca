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
    <div class="container-fluid">
        @include('admin.includes.alerts')

        <form action="{{ route('boleto.store')}}" class="form" name="form" method="POST">
            @csrf 

        <div class="card-header">
            <h4>Lançar Boletos</h4>
            <h6>Antes de lançar os boletos é necessário cadastrar os recebíveis.</h6>
            <div class="row">
                <div class="col-sm my-3">                                
                    <strong>Aluno(a): {{$aluno->nome}}</strong>
                </div>                
            </div> 
        </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th scope="col">#</th>
                        <th scope="col">Recebível</th>                        
                        <th scope="col">Parcela</th>                        
                        <th scope="col">Valor R$</th>
                        <th scope="col">Vencimento</th>
                        <th scope="col">Ações</th>                    
                    </thead>
                    <tbody>     
                        @foreach ($recebiveis as $index => $recebivel)
                            <tr>
                                <td>
                                    <input type="checkbox" name="fk_id_recebivel[]" value="{{$recebivel->id_recebivel}}" checked>
                                </td>
                                <td>{{$recebivel->descricao_conta}} - {{$recebivel->tipo_turma}} - {{$recebivel->ano}}</td>
                                <td>{{$recebivel->parcela}}</td>
                                <td>{{number_format($recebivel->valor_total, 2, ',', '.')}}</td>
                                <td>{{date('d/m/Y', strtotime($recebivel->data_vencimento))}}</td>
                                <td></td>
                            </tr>                            
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                @if (isset($filtros))
                    {!! $recebiveis->appends($filtros)->links()!!}
                @else
                    {!! $recebiveis->links()!!}     
                @endif                    
            </div>

            <div class="row">
                <div class="col-sm-6">
                    Forma de lançamento:
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="forma_geracao" id="boletos_vencimento" value="boletos_vencimento" required checked>
                    <label for="boletos_vencimento" class="form-check-label">Boletos mensais agrupados: curso e material didático, com mesmo vencimento, em 1 boleto</label>
                </div>
            </div>
            <br>
            {{-- <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="forma_geracao" id="boleto_unico" value="boleto_unico" required>
                    <label for="boleto_unico" class="form-check-label">Boleto único - todos recebíveis selecionados</label>
                </div>
            </div> --}}

            <div class="row ">
                <div class="form-group col-sm-4 col-xs-2">                         
                    <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Lançar</button>            
                </div>
            </div>
        </form>

    </div>
    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });          
    </script>
@stop