@extends('adminlte::page')

@section('title_postfix', ' Captações')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('captacao.index') }} " class="">Captações</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Cadastrar</a>
        </li>
    </ol>
    
@stop

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
    
    <div class="card">
        <form action="{{ route('captacao.store')}}" class="form" method="POST">
            @csrf
            <input type="hidden" name="fk_id_usuario_captacao" value={{Auth::id()}}>            
            @include('captacao.paginas._partials.form')
        </form>
    </div>
@endsection