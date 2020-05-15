@include('admin.includes.alerts')

<div class="form-group">
    <label>Razão Social:</label>
    <input type="text" name="razao_social" class="form-control" placeholder="Razão Social" value="{{ $unidadeensino->razao_social ?? old('razao_social') }} ">
</div>
<div class="form-group">
    <label>Nome fantasia:</label>
    <input type="text" name="nome_fantasia" class="form-control" placeholder="Nome Fantasia" value="{{ $unidadeensino->nome_fantasia ?? old('nome_fantasia') }}">
</div>
<div class="form-group">
    <label>CNPJ:</label>
    <input type="text" name="cnpj" class="form-control" placeholder="CNPJ" value="{{ $unidadeensino->cnpj ?? old('cnpj') }}">
</div>
<div class="form-group">
    <label>Telefone:</label>
    <input type="text" name="telefone" class="form-control" placeholder="Telefone" value="{{ $unidadeensino->telefone ?? old('telefone') }}">
</div>
<div class="form-group">
    <label>Email:</label>
    <input type="text" name="email" class="form-control" placeholder="Email" value="{{ $unidadeensino->email ?? old('email') }}">
</div>
<div class="form-group">
    <label>Responsável Assinatura:</label>
    <input type="text" name="nome_assinatura" class="form-control" placeholder="Responsavel Assinatura" value="{{ $unidadeensino->nome_assinatura ?? old('nome_assinatura') }}">
</div>
<div class="form-group">
    <label>Cargo Assinatura:</label>
    <input type="text" name="cargo_assinatura" class="form-control" placeholder="Cargo Assinatura" value="{{ $unidadeensino->cargo_assinatura ?? old('cargo_assinatura') }}">
</div>
<div class="form-group">
    <label>Site:</label>
    <input type="text" name="url_site" class="form-control" placeholder="Endereço do site" value="{{ $unidadeensino->url_site ?? old('url_site') }}">
</div>
<div>
    <button type="submit" class="btn btn-dark">Enviar</button>
</div>