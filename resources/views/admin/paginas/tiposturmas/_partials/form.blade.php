<div class="container-fluid">

    @include('admin.includes.alerts')
    @csrf

    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">
            <label>Nome padrão da turma:</label>
            <input type="text" name="turma_padrao" class="form-control" placeholder="1º Bimestre" value="{{ $tipoturma->tipo_turma ?? old('tipo_turma') }} ">        
        </div>
        
        <div class="form-group col-sm-4 col-xs-2">
            <label>Nível de Ensino:</label>
            <input type="text" name="nivel" class="form-control" placeholder="Fundamental !" value="Fundamental 1">        
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Ano:</label>
            <input type="text" name="ano" class="form-control" placeholder="" value="2020">        
        </div>        
    </div>

    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">            
            <button type="submit" class="btn btn-success">Enviar</button>            
        </div>
    </div>
</div>
