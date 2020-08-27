@if ($errors->any())
    <div class="alert alert-warning">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

@if (session('sucesso') or isset($sucesso))
    <div class="alert alert-success" role="alert">
        {{ session('sucesso') }}
        {{$sucesso ?? ''}}
    </div>
@endif

@if (session('erro') or isset($erro))
    <div class="alert alert-danger" role="alert">
        {{ session('erro') }}
        {{$erro ?? ''}}
    </div>
@endif

@if (session('atencao') or isset($atencao))
    <div class="alert alert-warning" role="alert">
        <strong>{{ session('atencao') }}</strong>
        <strong>{{$atencao ?? ''}}</strong>
    </div>
@endif


@if (session('info') or isset($info))
    <div class="alert alert-info" role="alert">
        <strong>{{ session('info') }}</strong>
        <strong>{{$info ?? ''}}</strong>
    </div>
@endif
