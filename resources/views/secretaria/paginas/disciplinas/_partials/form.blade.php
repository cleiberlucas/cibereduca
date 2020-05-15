@include('admin.includes.alerts')
@csrf

<div class="form-group">
    <label>*Disciplina:</label>
    <input type="text" name="disciplina" class="form-control" placeholder="Nome da Disciplina" value="{{ $disciplina->disciplina ?? old('disciplina') }} ">
</div>
<div class="form-group">
    <label>Sigla: *</label>
    <input type="text" name="sigla_disciplina" class="form-control" placeholder="Sigla da Disciplina" value="{{ $disciplina->sigla_disciplina ?? old('sigla_disciplina') }}">
</div>
<div class="form-group">
    <label>Situação: *</label>
    <input type="text" name="situacao_disciplina" class="form-control" value="{{ $disciplina->sigla_disciplina ?? old('sigla_disciplina') }}">
</div>

<div>
    <button type="submit" class="btn btn-dark">Enviar</button>
</div>