<div class="container-fluid">

    @include('admin.includes.alerts')

    <div class="row">
        <div class="form-group col-sm-6 col-xs-12">
            <label>* Razão Social:</label>
            <input type="text" name="razao_social" class="form-control" required placeholder="Razão Social" value="{{ $unidadeensino->razao_social ?? old('razao_social') }} ">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 col-xs-12">
            <label>* Nome fantasia:</label>
            <input type="text" name="nome_fantasia" class="form-control" required placeholder="Nome Fantasia" value="{{ $unidadeensino->nome_fantasia ?? old('nome_fantasia') }}">
        </div>    
    </div>
    
    <div class="row">
        <div class="form-group col-sm-3 col-xs-12">
            <label>CNPJ:</label>
            <input type="text" name="cnpj" id="cnpj" class="form-control" placeholder="CNPJ" value="{{ $unidadeensino->cnpj ?? old('cnpj') }}">
        </div>
        <div class="form-group col-sm-3 col-xs-10">
            <label>Telefone:</label>
            <input type="text" name="telefone" id="telefone" class="form-control" placeholder="Telefone" value="{{ $unidadeensino->telefone ?? old('telefone') }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-xs-12">        
            <label>Endereço:</label>
            <input type="text" maxlength="255" name="endereco" class="form-control" placeholder="Endereço" value="{{ $unidadeensino->endereco ?? old('endereco') }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-xs-12">        
            <label>Cidade/UF:</label>
            <input type="text" maxlength="100" name="cidade_uf" class="form-control" placeholder="Endereço" value="{{ $unidadeensino->cidade_uf ?? old('cidade_uf') }}">
            <small>Utilizado nas datas de assinaturas de documentos</small>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 col-xs-12">        
            <label>Email:</label>
            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ $unidadeensino->email ?? old('email') }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 col-xs-12">    
            <label>Responsável Assinatura:</label>
            <input type="text" name="nome_assinatura" class="form-control" placeholder="Responsavel Assinatura" value="{{ $unidadeensino->nome_assinatura ?? old('nome_assinatura') }}">
        </div>
    
        <div class="form-group col-sm-6 col-xs-12">    
            <label>Cargo Assinatura:</label>
            <input type="text" name="cargo_assinatura" class="form-control" placeholder="Cargo Assinatura" value="{{ $unidadeensino->cargo_assinatura ?? old('cargo_assinatura') }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6 col-xs-12">     
            <label>Site:</label>
            <input type="text" name="url_site" class="form-control" placeholder="Endereço do site" value="{{ $unidadeensino->url_site ?? old('url_site') }}">
        </div>
    </div>    
    <div class="form-group">
        <label>*Situação:</label><br>
        @if (isset($unidadeensino->situacao) && $unidadeensino->situacao == 1)
            <input type="checkbox"  id="situacao" name="situacao" value="1" checked> 
        @else
            <input type="checkbox"  id="situacao" name="situacao" value="0"> 
        @endif
        Ativar  
    </div>
    <div class="row">
        <div class="form-group col-sm-4 col-xs-6">     
            <div>   
                * Campos Obrigatórios<br>            
                <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>

<script>
    $(document).ready(function ($) { 
        var $campoCnpj = $("#cnpj");
        $campoCnpj.mask('00.000.000/0000-00', {reverse: true});
    });

    $(document).ready(function ($) { 
        var $fone1 = $("#telefone");
        $fone1.mask('(00) 0000-0000', {reverse: false});
    });
</script>

<script>
    $(document).ready(function(){
          $(".alert").slideDown(300).delay(5000).slideUp(300);
    });    
</script>
