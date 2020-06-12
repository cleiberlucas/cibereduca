@include('admin.includes.alerts')
@csrf
<div class="container-fluid">
    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">     
            <label>Permissão:</label>
            <input type="text" name="permissao" class="form-control" placeholder="Permissões de usuário" autofocus value="{{ $permissao->permissao ?? old('permissao') }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6 col-xs-2">     
            <label>Descrição:</label>
            <input type="text" name="descricao_permissao" class="form-control" placeholder="Descrição" value="{{ $permissao->descricao_permissao ?? old('descricao_permissao') }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6 col-xs-2">     
            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>
        </div>
    </div>
</div>
