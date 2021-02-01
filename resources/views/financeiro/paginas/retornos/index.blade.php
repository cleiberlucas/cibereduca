@extends('adminlte::page')

@section('title_postfix', ' Retornos')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Financeiro</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="{{ route('retorno.index') }} " class=""> Retornos</a>
        </li>
    </ol>    
@stop

@section('content')
    <div class="container-fluid">
        @include('admin.includes.alerts')
        <div class="card-header">
            <h4>Retornos recebidos</h4>
            <br>
            <a href="{{ route('retorno.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Processar retorno</a>
            
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
                        <th scope="col">Sequencial Banco</th>
                        <th scope="col">Data Retorno</th>
                        <th scope="col">Data Processamento</th>
                        <th scope="col">Arquivo</th>
                        <th scope="col">Log</th>
                        <th scope="col">Situação</th>
                        {{-- <th scope="col">Ações</th>                --}}     
                    </thead>
                    <tbody>                                  
                        @foreach ($retornos as $index => $retorno)                            
                            <tr>
                                <th>{{$retorno->id_retorno}}</th>
                                <td>{{$retorno->sequencial_retorno_banco}}</td>
                                <td>
                                    {{date('d/m/Y', strtotime($retorno->data_retorno))}}
                                </td>    
                                <td>
                                    {{date('d/m/Y H:i:s', strtotime($retorno->data_processamento))}}
                                </td>
                                <td>                                    
                                    <a href="{{'/storage/boletos/retornos/processar/'.$retorno->nome_arquivo}}" download="{{$retorno->nome_arquivo}}" class="href">Arquivo</a>
                                </td>                                                             
                                <td><a href="#" download="" class="href">Log</a></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                @if (isset($filtros))
                    {!! $retornos->appends($filtros)->links()!!}
                @else
                    {!! $retornos->links()!!}     
                @endif                    
            </div>
        
    </div>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>
    
@stop
