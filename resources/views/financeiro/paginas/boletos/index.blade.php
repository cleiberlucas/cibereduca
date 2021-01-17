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
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <th scope="col">#</th>
                    <th scope="col">Data Vencimento</th>        
                    <th scope="col">Valor</th>        
                    <th scope="col">Data Pagamento</th>                        
                    <th scope="col">Situação</th>
                    <th scope="col">Ações</th>                    
                </thead>
                <tbody>                                  
                    @foreach ($boletos as $index => $boleto)
                        <tr>
                            <th scope="row">{{$index+1}}</th>
                            <td>
                                {{date('d/m/Y', strtotime($boleto->data_vencimento))}}
                            </td>    
                            <td>
                                {{number_format($boleto->valor_total, 2, ',', '.')}}    
                            </td>                             
                            <td>
                                @if (isset($boleto->data_pagamento))
                                    {{date('d/m/Y', strtotime($boleto->data_pagamento))}}    
                                @endif                                
                            </td>           
                            <td>
                                
                            </td>  
                            <td>
                                {{-- Link para todos os boletos de um aluno em qq ano letivo --}}
                                {{-- <a href="{{ route('boleto.indexAluno', $boleto->id_pessoa) }}" class="btn btn-sm btn-outline-dark"><i class="fas fa-barcode"></i></a> --}}
                            </td>                                                                           
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                @if (isset($filtros))
                    {!! $boletos->appends($filtros)->links()!!}
                @else
                    {!! $boletos->links()!!}     
                @endif                    
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>
    
@stop
