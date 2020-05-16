

<div class="container-fluid">

    @include('admin.includes.alerts')

    <div class="row">
        <div class="form-group col-sm-6 col-xs-12">
            <label>Nome:</label>
            <input type="text" name="nome" class="form-control" placeholder="Nome" required value="{{ $unidadeensino->razao_social ?? old('razao_social') }} ">
        </div>
        <div class="form-group col-sm-3 col-xs-12">
            <label>Foto:</label>
            <input type="file" name="foto" class="form-control" >
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-2 col-xs-12">
            <label>CPF:</label>
            <input type="text" name="cpf" class="form-control" placeholder="CPF" value="{{ $unidadeensino->nome_fantasia ?? old('nome_fantasia') }}">
        </div>    
    
        <div class="form-group col-sm-2 col-xs-12">
            <label>Identidade:</label>
            <input type="text" name="doc_identidade" class="form-control" placeholder="Documento de Identidade" value="{{ $unidadeensino->cnpj ?? old('cnpj') }}">
        </div>
        <div class="form-group col-sm-2 col-xs-10">
            <label>Data Nascimento:</label>
            <input type="date" name="data_nascimento" class="form-control" placeholder="Data de Nascimento" required value="{{ $unidadeensino->telefone ?? old('telefone') }}">
            <div class="input-group-addon" >
                <span class="glyphicon glyphicon-th"></span>
            </div>            
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-2 col-xs-12">
            <label>Telefone Principal:</label>
            <input type="text" name="telefone_1" class="form-control" placeholder="Telefone com DDD" value="{{ $unidadeensino->nome_fantasia ?? old('nome_fantasia') }}">
        </div>        
        <div class="form-group col-sm-2 col-xs-12">
            <label>Telefone opcional:</label>
            <input type="text" name="telefone_2" class="form-control" placeholder="Telefone 2 com DDD" value="{{ $unidadeensino->nome_fantasia ?? old('nome_fantasia') }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-4 col-xs-12">        
            <label>E-mail:</label>
            <input type="email" name="email_1" class="form-control" placeholder="E-mail principal" value="{{ $unidadeensino->email ?? old('email') }}">
        </div>
        <div class="form-group col-sm-4 col-xs-12">        
            <label>E-mail opcional:</label>
            <input type="email" name="email_2" class="form-control" placeholder="E-mail opcional" value="{{ $unidadeensino->email ?? old('email') }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 col-xs-12">                
            <label>*Situação:</label><br>
            @if (isset($disciplina->situacao_disciplina) && $disciplina->situacao_disciplina == 1)
                <input type="checkbox" id="situacao_disciplina" name="situacao_disciplina" value="1" checked> 
            @else
                <input type="checkbox" id="situacao_disciplina" name="situacao_disciplina" value="0"> 
            @endif
            Ativar  
        </div>
    </div>    

    
</div>
