@extends('adminlte::page')

@section('title_postfix', ' '.$tipo_pessoa )

@section('content_header')
    <h1>Cadastrar {{$tipo_pessoa}}</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('pessoas.store')}}" class="form" method="POST">
            @csrf
            
            @include('secretaria.paginas.pessoas._partials.form')
            
            {{-- Endereço apenas para responsáveis --}}
            @if ($tipo_pessoa == 'responsavel')
                @include('secretaria.paginas.pessoas._partials.form_endereco')    
            @endif

            <div class="row">
                <div class="form-group col-sm-2 col-xs-6">     
                    <div>
                        <button type="submit" class="btn btn-success">Enviar</button>
                    </div>
                </div>
            </div>
            
        </form>
    </div>
@endsection