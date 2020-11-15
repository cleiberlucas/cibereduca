<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="favicons/favicon.ico" >
    <title>Rede Educa - Agendamento On-line</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>

<div class="container-fluid">
    <div class="container">

        <div class="row mt-1">        
            <div class="col-sm-2 col-xs-2 my-1 py-1" align="center"> 
                <img src="/vendor/adminlte/dist/img/logo.png" width="60%" alt="logo">
            </div>
            <div class="col-sm-10 col-xs-2 my1 py-1 mx-auto" align="center"> 
                
                <div class="row mt-4">
                    @foreach ($unidadesEnsino as $indunid => $unidadeEnsino)      
                        <div class="col-sm-{{12/count($unidadesEnsino)}} text-align-center">                                    
                            <h5>{{$unidadeEnsino->nome_fantasia}}</h5>
                            <h6>{{mascaraTelefone("(##) ####-####", $unidadeEnsino->telefone)}} e {{mascaraTelefone("(##) #####-####", $unidadeEnsino->telefone_2)}}</h6>
                            {{-- <h6>Email: {{$unidadeEnsino->email}}</h6> --}}
                        </div>                    
                    @endforeach                
                </div>            
                <div class="row mt-4">
                    <div class="col-sm-12 text-align-center">  
                        <h5>Seja bem-vindo(a)!</h5>           
                        <h5>É uma alegria lhe atender.</h5>
                        <br>
                        <h5>AGENDAMENTO DE VISITA</h5>
                    </div>
             </div>
            </div>        
        </div>

        <hr>
    
        <form action="{{ route('agendamento.store')}}" class="form" method="POST">
            @csrf           
            @include('admin.includes.alerts')
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            @if ($error == 'validation.captcha')
                                <li> Verificação de captcha inválida.</li>
                            @else
                                <li>{{ $error }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div><br />
            @endif

            <div class="row">
            
                <div class="form-group col-sm-6 col-xs-2">            
                    <label>* Nome:</label>
                    <input type="text" name="nome" minlength="5" maxlength="100" class="form-control" maxlength="100" placeholder="Nome completo" value="{{old('nome')}}">              
                </div> 

                <div class="form-group col-sm-4 col-xs-2">            
                    <label>* Tipo Cliente:</label>
                    <select name="fk_id_tipo_cliente"  class="form-control" required>
                        <option value=""></option>
                        @foreach ($tiposCliente as $tipoCliente)                    
                            <option value="{{$tipoCliente->id_tipo_cliente}}">{{$tipoCliente->tipo_cliente}}</option>                        
                        @endforeach
                    </select>
                </div> 
            </div>

            <div class="row">
                {{-- Se tiver só 1 unidade --}}
                @if (count($unidadesEnsino) == 1)
                    <input type="hidden" name="fk_id_unidade_ensino" value={{$unidadesEnsino[0]->id_unidade_ensino}}> 
                {{-- Se tiver mais de uma unidade --}}
                @elseif (count($unidadesEnsino) > 1)
                    <div class="form-group col-sm-4 col-xs-2">            
                        <label>* Qual Unidade deseja visitar:</label>
                        <select name="fk_id_unidade_ensino"  class="form-control" required>
                            <option value=""></option>
                            @foreach ($unidadesEnsino as $unidadeEnsino)                    
                                <option value="{{$unidadeEnsino->id_unidade_ensino}}">{{$unidadeEnsino->nome_fantasia}}</option>                        
                            @endforeach
                        </select>
                    </div>
                @elseif (count($unidadesEnsino) < 1)

                @endif
            </div>

            <div class="row">
                <div class="form-group col-sm-2 col-xs-12">
                    <label>* Telefone:</label>
                    <input type="text" name="telefone_1" id="telefone_1" class="form-control" placeholder="DDD e Fone" value="{{old('telefone_1') }}" required>
                </div>

                <div class="form-group col-sm-4 col-xs-12">        
                    <label>E-mail:</label>
                    <input type="email" name="email_1"  maxlength="100" class="form-control" value="{{ old('email_1') }}">
                </div>
            </div>
            
            <div class="row">
                <div class="form-group col-sm-5 col-xs-2">
                    <label>Nome Aluno:</label>
                    <input type="text" name="aluno" minlength="5" maxlength="100" class="form-control" value="{{ old('aluno') }}">        
                </div>

                <div class="form-group col-sm-3 col-xs-2">
                    <label>Série Pretendida:</label>
                    <input type="text" name="serie_pretendida" class="form-control" minlength="5" maxlength="45" value="{{ old('serie_pretendida') }}">        
                </div>        
            </div>

            <div class="row">
                <div class="form-group col-sm-3 col-xs-2">
                    <label>* Agendamento para:</label>
                    <input type="date" name="data_agenda" min={{date('Y-m-d')}} class="form-control" value="{{ old('data_agenda') }}" required>   
                    <small>De segunda a sexta-feira</small>     
                </div>   
                <div class="form-group col-sm-2 col-xs-2">
                    <label>* Horário:</label>
                    <input type="time" name="hora_agenda" min="08:00" max="17:00" class="form-control" value="{{ old('hora_agenda') }}" required>     
                    <small>Das 8h às 17h.</small>   
                </div>   

                <div class="form-group col-sm-4 col-xs-2">            
                    <label>Como conheceu a escola:</label>
                    <select name="fk_id_tipo_descoberta"  class="form-control">
                        <option value=""></option>
                        @foreach ($tiposDescoberta as $tipoDescoberta)                    
                            <option value="{{$tipoDescoberta->id_tipo_descoberta}}">{{$tipoDescoberta->tipo_descoberta}}</option>                        
                        @endforeach
                    </select>
                </div> 

            </div>
        
            <div class="row">
                <div class="form-group col-sm-12 col-xs-2">
                    <label>Outras informações:</label>
                    <textarea name="observacao"  rows="3" maxlength="1000" class="form-control">{{old('observacao')}}</textarea>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-3 mt-4 mb-4">
                    <div class="captcha">
                        <span>{!! captcha_img() !!}</span>
                        <button type="button" class="btn btn-danger" class="reload" id="reload">
                            &#x21bb;
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="form-group col-sm-2 mb-4">
                    <input id="captcha" type="text" class="form-control" placeholder="" name="captcha" required>
                </div>
            </div>
                
            <div class="row">
                <div class="form-group col-sm-4 col-xs-2">            
                    <label for="">* Campos Obrigatórios</label><br >
                    <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Agendar</button>            
                </div>
            </div>

        </form>
    </div>
</div>

<footer>
    <br>
    <div class="row justify-content-center">            
        <img width="3%" src="/vendor/cibersys/img/cubo_magico.gif"  alt="">            
    </div>
    
    <div class="row justify-content-center">
        <div class="form-group col-sm-12 col-xs-2" align="center">
            CiberEduca - Plataforma de Gestão Escolar                     
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="form-group col-sm-12 col-xs-2" align="center">
            CiberSys - Sistemas Inteligentes - cibersys.sistemas@gmail.com
        </div>
    </div>

    <div class="row justify-content-center" align="center">
        <div class="form-group col-sm-12 col-xs-2">
            Copyright &copy; 2020-{{date('Y')}}
        </div>
    </div>
</footer>

<script>
    $(document).ready(function ($) { 
       var $fone1 = $("#telefone_1");
       $fone1.mask('(00) 00000-0000', {reverse: false});
   });

  /*  $(document).ready(function(){
         $(".alert").slideDown(300).delay(5000).slideUp(300);
   });  */ 
</script>

<script type="text/javascript">
    $('#reload').click(function () {
        $.ajax({
            type: 'GET',
            url: 'reload-captcha',
            success: function (data) {
                $(".captcha span").html(data.captcha);
            }
        });
    });

</script>
</html>

