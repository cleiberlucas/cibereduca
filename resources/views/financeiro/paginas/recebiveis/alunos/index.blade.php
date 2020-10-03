

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Pessoas')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Financeiro</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="{{ route('financeiro.index') }} " class=""> Recebíveis</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Aluno</a>
        </li>
    </ol>
    
@stop

@section('content')
    <div class="container-fluid">
        @include('admin.includes.alerts')
        <div class="card-header">
           
            <form action="{{ route('pessoas.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">                
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
        <div class="row">
            <div class="col-sm my-3">            
                <strong>Aluno(a): {{$aluno->nome}}</strong>  <a href="{{ route('financeiro.create', $aluno->id_pessoa) }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Lançar recebível</a>  
            </div>                
        </div>       
        </div>
        <div class="table-responsive">
            <table class="table  table-hover">
                <thead>
                    <th scope="col">#</th>
                    <th scope="col">Recebível</th>                        
                    <th scope="col">Parcela</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Vencimento</th>
                    <th scope="col">Pagamento</th>                    
                    <th scope="col">Ações</th>
                </thead>
                <tbody>
                    @foreach ($recebiveis as $index => $recebivel)                        
                        <tr>
                            <th scope="row">{{$index+1}}</th>
                            <td>{{$recebivel->descricao_conta}} - {{$recebivel->tipo_turma}} - {{$recebivel->ano}}</td>
                            <td>{{$recebivel->parcela}}</td>
                            <td>{{number_format($recebivel->valor_total, 2, ',', '.')}}</td>
                            <td>{{date('d/m/Y', strtotime($recebivel->data_vencimento))}}</td>                            
                            <td>{{isset($recebivel->valor_recebido) ?? ''}}</td>                                                                
                            <td >                                
                                <a href="{{ route('recebimento.create', $recebivel->id_recebivel) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-hand-holding-usd"></i></a>
                                <a href="{{ route('financeiro.edit', $recebivel->id_recebivel) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                
                                {{-- <a href="{{ route('pessoas.edit', $pessoa->id_pessoa) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a> --}}
                                {{-- <a href="{{ route('pessoas.show', $pessoa->id_pessoa) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a> --}}
                            </td>                                
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                @if (isset($filtros))
                    {!! $recebiveis->appends($filtros)->links()!!}
                @else
                    {!! $recebiveis->links()!!}     
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
