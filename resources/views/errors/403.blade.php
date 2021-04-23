@extends('adminlte::page')

@section('title_postfix', ' - Não Autorizado' )

@section('content')

<div class="container">
    <div class="alert alert-info">
        <h3>Você não possui acesso a esta operação.</h3>
    </div>
</div>

@endsection
