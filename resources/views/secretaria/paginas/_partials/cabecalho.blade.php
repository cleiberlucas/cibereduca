    <div class="row mt-1">        
        <div class="col-sm-2 col-xs-2 my-1 py-1"> 
                <img src="/vendor/adminlte/dist/img/logo.png" width="60%" alt="logo">
        </div>
        <div class="col-sm-10 col-xs-2 my1 py-1 mx-auto" align="center"> 
            <div class="row mx-auto">
                <div class="col-sm-12 col-xs-2 my-0 py-0 mx-auto" align="center"> 
                    <font size="4"> <b>{{mb_strToUpper($unidadeEnsino->razao_social)}} </b> </font>
                </div>
            </div>
            <font size="2">
            <div class="row mx-auto">        
                <div class="col-sm-12 col-xs-2 my-0 py-0 mx-auto" align="center"> 
                     CNPJ: {{mascaraCpfCnpj('##.###.###/####-##', $unidadeEnsino->cnpj)}}
                </div>
            </div>
            <div class="row mx-auto">
                <div class="col-sm-12 col-xs-2 my-0 py-0 mx-auto" align="center"> 
                    Fone: 
                    @if (strlen($unidadeEnsino->telefone) == 10)
                        {{mascaraTelefone('(##) ####-####', $unidadeEnsino->telefone)}} - E-mail: {{$unidadeEnsino->email}}
                    @else
                        {{mascaraTelefone('(##) #####-####', $unidadeEnsino->telefone)}} - E-mail: {{$unidadeEnsino->email}}    
                    @endif
                   
                </div>
            </div>
            <div class="row mx-auto">        
                <div class="col-sm-12 col-xs-2 my-0 py-0 mx-auto" align="center"> 
                    {{$unidadeEnsino->endereco}}
                </div>
            </div>
            
            </font>
        </div>        
    </div>
