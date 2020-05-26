@include('admin.includes.alerts')

@csrf

<div class="form-group">
    <label>*Disciplina:</label>
    <input type="text" name="disciplina" class="form-control" placeholder="Nome da Disciplina" value="{{ $disciplina->disciplina ?? old('disciplina') }}">
</div>
<div class="form-group">
    <label>*Sigla:</label>
    <input type="text" name="sigla_disciplina" class="form-control" placeholder="Sigla da Disciplina" value="{{ $disciplina->sigla_disciplina ?? old('sigla_disciplina') }}">
</div>
<div class="form-group">
    <label>*Situação:</label><br>
    @if (isset($disciplina->situacao_disciplina) && $disciplina->situacao_disciplina == 1)
        <input type="checkbox"  id="situacao_disciplina" name="situacao_disciplina" value="1" checked> 
    @else
        <input type="checkbox"  id="situacao_disciplina" name="situacao_disciplina" value="0"> 
    @endif
    Ativar  
</div>

<div class="row">
    <div class="form-group col-sm-2 col-xs-2">            
        <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
    </div>
</div>
