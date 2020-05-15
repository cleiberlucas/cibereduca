@include('admin.includes.alerts')
@csrf

<div class="form-group">
    <label>Permissões:</label>
    <input type="text" name="permissao" class="form-control" placeholder="Permissões de usuário" value="{{ $permissao->permissao ?? old('permissao') }} ">
</div>
<div class="form-group">
    <label>Descrição:</label>
    <input type="text" name="descricao_permissao" class="form-control" placeholder="Descrição" value="{{ $permissao->descricao_permissao ?? old('descricao_permissao') }}">
</div>

<div>
    <button type="submit" class="btn btn-dark">Enviar</button>
</div>