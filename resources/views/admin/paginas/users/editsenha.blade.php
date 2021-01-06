@extends('adminlte::page')

@section('title_postfix', 'Rede Educa')

@section('content_header')
    <ol class="breadcrumb">       
        <li class="breadcrumb-item active" >
            <a href="{{ route('users.index') }} " class="">Usuários</a>
        </li> 
        <li class="breadcrumb-item">
            <a href="">Editar</a>
        </li>
    </ol>              
        <h1>Editar Usuário </h1>
@stop

    @section('content')
    <div>@include('admin.includes.alerts')</div>
    <div class="container-fluid">

        <form action="{{ route('users.update', $user->id)}}" class="form" method="POST">
            @csrf
            @method('PUT')
             
                

                <div class="row">
                    <div class="form-group col-sm-4 col-xs-2">
                        <label>Login do usuário:</label>
                        <input type="text" name="email" class="form-control" placeholder="Email" readonly value="{{ $user->email ?? old('email') }} ">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-4 col-xs-2">
                        <label>Nome:</label>
                        <input type="text" name="name" class="form-control" placeholder="Nome" readonly value="{{ $user->name ?? old('name') }}">
                    </div>
                    <div class="form-group col-sm-4 col-xs-2">
                        <label>Senha:</label>
                        <input type="password" name="password" class="form-control" placeholder="Senha:">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-4 col-xs-2">    
                        <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
                    </div>
                </div>
            </div>

        </form>
    
    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>

@endsection