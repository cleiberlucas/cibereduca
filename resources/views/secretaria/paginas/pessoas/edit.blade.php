@extends('adminlte::page')

@section('title_postfix', ' '.$tipoPessoa)

@section('content_header')
    <h1>Editar {{$tipoPessoa}} </h1>
@stop

@section('content')
    <div class="container-fluid">
        <form action="{{ route('pessoas.update', $pessoa->id_pessoa)}}" class="form" method="POST">            
            @csrf
            @method('PUT')
            @include('secretaria.paginas.pessoas._partials.form')

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

        </form>
    </div>
@endsection