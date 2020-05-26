@extends('adminlte::page')

@section('title_postfix', ' '.$tipo_pessoa )

@section('content_header')
    <h1>Cadastrar {{$tipo_pessoa}}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <form action="{{ route('pessoas.store')}}" class="form" method="POST">
            @csrf
            <div class="container-fluid">
                @include('secretaria.paginas.pessoas._partials.form')
                
                {{-- Endereço apenas para responsáveis --}}
                @if ($tipo_pessoa == 'responsavel')
                    @include('secretaria.paginas.pessoas._partials.form_endereco')    
                @endif

                <div class="row">
                    <div class="form-group col-sm-6 col-xs-12">                
                        <label>*Situação:</label><br>
                        @if (isset($pessoa->situacao_pessoa) && $pessoa->situacao_pessoa == 1)
                            <input type="checkbox" id="situacao_pessoa" name="situacao_pessoa" value="1" checked> 
                        @else
                            <input type="checkbox" id="situacao_pessoa" name="situacao_pessoa" value="0"> 
                        @endif
                        Ativar  
                    </div>
                </div>   

                <div class="row">
                    <div class="form-group col-sm-2 col-xs-6">     
                        <div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>
                        </div>
                    </div>
                </div>
            </div>    
        </form>
    </div>
@endsection