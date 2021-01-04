@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <h4>CiberEduca - Plataforma de Gestão Escolar</h4>
        </div>
    </div>
    <div class="row">
        
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Identifique-se') }}</div>

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-2">        
                                <img src="vendor/adminlte/dist/img/logo.png" alt="">                            
                            </div>
                        
                            <div class="col-md-10">        
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Login*') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <span>*Caso seja pai/responsável, informe o CPF.</span>                                      
                                        </div>
                                        
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Senha') }}</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="form-check-label" for="remember">
                                                    {{ __('Lembrar-me') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Acessar') }}
                                            </button>

                                            {{-- @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif --}}
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    
</div>


<footer>
    <div class="row justify-content-center">            
        <img width="5%" src="vendor/cibersys/img/cubo_magico.gif" alt="">            
    </div>
    
    <div class="row justify-content-center">
        <div class="">
            <a href="cibereduca.cibersys.com.br"> 
            CiberSys - Sistemas Inteligentes </a>
        </div>
    </div>
</footer>

@endsection