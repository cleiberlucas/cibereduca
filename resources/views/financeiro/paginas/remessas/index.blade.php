@extends('adminlte::page')

@section('title_postfix', ' Boletos')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Financeiro</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="{{ route('remessa.index') }} " class=""> Remessas</a>
        </li>
    </ol>    
@stop

@section('content')
    <div class="container-fluid">
        @include('admin.includes.alerts')
        <div class="card-header">
            <h4>Remessas geradas</h4> 
            <br>
            <a href="{{ route('remessa.bancoob.gerar') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Gerar remessa</a>
            &nbsp;&nbsp;&nbsp;* Todos os boletos, na situação "lançado", serão incluídos na remessa.
            
            {{-- <form action="{{ route('recebivel.aluno.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">                
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form> --}}
        </div>        
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th scope="col">Nº</th>
                        <th scope="col">Data Geração</th>                                
                        <th scope="col">Situação</th>
                        {{-- <th scope="col">Ações</th>                --}}     
                    </thead>
                    <tbody>                                  
                        @foreach ($remessas as $index => $remessa)                            
                            <tr>
                                <th>{{$remessa->id_remessa}}</th>
                                <td>
                                    {{date('d/m/Y H:i:s', strtotime($remessa->data_remessa))}}
                                </td>    
                                <td>           
                                    {{$remessa->situacao_remessa}}                         
                                </td>                                                             
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                @if (isset($filtros))
                    {!! $remessas->appends($filtros)->links()!!}
                @else
                    {!! $remessas->links()!!}     
                @endif                    
            </div>
        
    </div>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>
    
@stop
