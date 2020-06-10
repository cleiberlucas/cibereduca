@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
<ol class="breadcrumb">        
    <li class="breadcrumb-item active" >        
        <a href="{{ route('unidadesensino.index') }} " class="">Unidades Ensino</a>
    </li>
    <li class="breadcrumb-item active" >
        <a href="#" class="">Nova</a>
    </li>
</ol> 

    <h1>Cadastrar Unidade Ensino </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('unidadesensino.store')}}" class="form" method="POST">
            @csrf
            
            @include('admin.paginas.unidadesensino._partials.form')
        </form>
    </div>
@endsection