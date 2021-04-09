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
        <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-8 col-xs-2">
                    <h4>Remessas geradas</h4> 
                    <br>
                    <a href="{{ route('remessa.bancoob.gerar') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Gerar remessa</a>
                    &nbsp;&nbsp;&nbsp;* Todos os boletos, na situação "lançado", serão incluídos na remessa.
                </div>
                <div class="col-sm-4 col-xs-2">
                    <br>
                    @if ($boletosLancados == 0)
                        <strong>Não há novos boletos lançados.</strong>
                    @else
                        <strong>Há <?=$boletosLancados?> boletos lançados para geração de remessa.</strong>
                    @endif
                </div>
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
                        <th scope="col">Nº</th>
                        <th scope="col">Data Geração</th>                                
                        <th scop="col">Arquivo</th>
                        <th scope="col">Situação</th>
                        {{-- <th scope="col">Ações</th>                --}}     
                    </thead>
                    <tbody>                                  
                        @foreach ($remessas as $index => $remessa)                            
                            <tr>
                                <th>{{$remessa->id_remessa}}</th>
                                <td>                                    
                                    {{date('d/m/Y - H:i:s', strtotime($remessa->data_remessa))}}
                                </td>
                                <td>
                                    <a href="/storage/boletos/remessas/{{$remessa->nome_arquivo}}" download="{{$remessa->nome_arquivo}}">Baixar</a>    
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
