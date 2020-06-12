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
        <div class="form-group col-sm-2 col-xs-12">
            <label>CNPJ:</label>
            <input type="text" name="cnpj" class="form-control" placeholder="CNPJ" value="{{ $unidadeensino->cnpj ?? old('cnpj') }}">
        </div>
        <div class="form-group col-sm-2 col-xs-10">
            <label>Telefone:</label>
            <input type="text" name="telefone" class="form-control" placeholder="Telefone" value="{{ $unidadeensino->telefone ?? old('telefone') }}">
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
