@extends('adminlte::page')

@section('title', 'Rede Educa')


@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="#" class="">Alteração Senha Acesso</a>
        </li> 
    </ol>
@stop


@section('content')
    <div class="container-fluid">
        <form action="{{ route('users.updatesenha')}}" class="form" method="POST">
            @csrf
            @method('PUT')

            <h4>Alteração de senha de acesso</h4>
            <div class="card-header">
                {{$usuario->name}} - {{$usuario->email}}
            </div>
            
            @include('admin.includes.alerts')

            <div class="row mt-3">
                <div class="form-group col-sm-4 col-xs-2">
                    <label>Senha Atual:</label>
                    <input type="password" name="senhaAtual" class="form-control" placeholder="" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-4 col-xs-2">
                    <label>Nova Senha:</label>
                    <input type="password" name="password" class="form-control" placeholder="" required>
                </div>
                <div class="form-group col-sm-4 col-xs-2">
                    <label>Confirme Nova Senha:</label>
                    <input type="password" name="password_2" class="form-control" placeholder="" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-4 col-xs-2">    
                    <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function(){
            $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>

@endsection
