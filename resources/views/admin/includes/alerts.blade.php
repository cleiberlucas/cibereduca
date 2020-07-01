@if ($errors->any())
    <div class="alert alert-warning">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

@if (session('sucesso'))
    <div class="alert alert-success" role="alert">
        {{ session('sucesso') }}
    </div>
@endif

@if (session('erro'))
    <div class="alert alert-danger" role="alert">
        {{ session('erro') }}
    </div>
@endif

@if (session('atencao'))
    <div class="alert alert-warning" role="alert">
        <strong>{{ session('atencao') }}</strong>
    </div>
@endif


@if (session('info'))
    <div class="alert alert-info" role="alert">
        <strong>{{ session('info') }}</strong>
    </div>
@endif
