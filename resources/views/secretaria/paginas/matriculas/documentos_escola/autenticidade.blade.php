<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rede Educa - Autenticidade Documento</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid">
       {{--  @include('secretaria.paginas._partials.cabecalho') --}}
        
        <br><br>
       <div class="row justify-content-center">            
        
            <h3>CiberEduca - Plataforma de Gestão Escolar</h3>            
       </div>
        <hr>
       <div class="row justify-content-center">            
            <strong><h4>Verificação de Autenticidade de Documento</h4></strong>            
       </div>

        <hr>
       {{--  
        @include('admin.includes.alerts') --}}

        
        @if (!empty($sucesso))
            <div class="alert alert-success"> 
                <b>{{$sucesso}}</b>
                <br>
                {{$dados}}
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


        <form action="{{ route('matriculas.documentos_escola.verifica_autenticidade')}}" class="form" method="POST" >
            @csrf
            <div class="row">
                <div class="form-group col-sm-3 col-xs-2">
                    <label>Data do Documento</label>
                    <input type="date" name="data_geracao" class="form-control" required value="{{old('data_geracao')}}">
                </div>
                <div class="form-group col-sm-3 col-xs-2">
                    <label>Código de Verificação</label>
                    <input type="text" name="codigo_validacao" class="form-control" maxlength="255" required value="{{old('codigo_validacao')}}">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-4 col-xs-2">
                    <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Consultar</button>
                </div>
            </div>

        </form>
    </div>
    
</body>


<footer>
    <br><br><br>
    <div class="row justify-content-center">            
        <img width="3%" src="/vendor/cibersys/img/cubo_magico.gif"  alt="">            
    </div>
    
    <div class="row justify-content-center">
        <div class="">
            CiberEduca - Plataforma de Gestão Escolar            
          
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="">
            <a href="http://www.cibersys.com.br">CiberSys - Sistemas Inteligentes </a>
        </div>
    </div>

    <div class="row justify-content-center">
        Copyright &copy; 2020-{{date('Y')}}
    </div>
</footer>


<script>
    $(document).ready(function(){
          $(".alert").slideDown(300).delay(5000).slideUp(300);
    });    
</script>

</html>