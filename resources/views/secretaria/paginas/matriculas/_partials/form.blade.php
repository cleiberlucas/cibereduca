<div class="container-fluid">

    @include('admin.includes.alerts')
    @csrf
    
    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">
            <label>Aluno:</label>
            <input type="text" name="nome_turma" class="form-control" placeholder="" value="">        
        </div>
        
        <div class="form-group col-sm-4 col-xs-2">
            <label>Responsável:</label>
            <input type="text" name="turno" class="form-control" placeholder="" value="">        
        </div>
    </div>

    <h3>Matrícula</h3>
    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Valor Matrícula</label>
            <input type="text" name="valor_matricula" class="form-control"  value="{{ $matricula->valor_matricula ?? old('valor_matricula') }} ">
        </div>  
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Data Matrícula</label>
            <input type="date" name="data_matricula" class="form-control"  value="{{ $matricula->data_matricula ?? old('data_matricula') }} ">
        </div>  
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Data Pagamento Matrícula</label>
            <input type="date" name="data_pagamento_matricula" class="form-control"  value="{{ $matricula->data_pagamento_matricula ?? old('data_pagamento_matricula') }} ">
        </div>  
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Data limite desistência</label>
            <input type="date" name="data_limite_desistencia" class="form-control"  value="{{ $matricula->data_limite_desistencia ?? old('data_limite_desistencia') }} ">
        </div>  
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Forma de pagamento</label>
            <input type="text" name="" class="form-control" placeholder="" >        
        </div>        
    </div>

    <h3>Curso</h3>
    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Valor Curso</label>
            <input type="text" name="valor_curso" class="form-control"  value="{{ $matricula->valor_curso ?? old('valor_curso') }} ">
        </div>  
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Valor Desconto</label>
            <input type="decimal" name="valor_desconto" class="form-control"  value="{{ $matricula->valor_desconto ?? old('valor_desconto') }} ">
        </div>  
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Tipo Desconto Curso</label>
            <input type="" name="tipo_desconto" class="form-control">
        </div> 
    </div>

    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Quantidade parcelas</label>
            <input type="number" name="qt_parcelas" class="form-control"  value="{{ $matricula->qt_parcelas ?? old('qt_parcelas') }} ">
        </div>   
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Data Vencimento 1ª parcela</label>
            <input type="date" name="data_matricula" class="form-control"  value="{{ $matricula->data_matricula ?? old('data_matricula') }} ">
        </div> 
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Forma de Pagamento</label>
            <input type="text" name="forma" class="form-control"">
        </div> 
    </div>

    <h3>Material Didático</h3>
    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Valor Material Didático</label>
            <input type="decimal" name="valor_material_didatico" class="form-control"  value="{{ $matricula->valor_material_didatico ?? old('valor_material_didatico') }} ">
        </div>   
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Data Pagto Material Didátivo</label>
            <input type="date" name="data_pagto_mat_didatico" class="form-control"  value="{{ $matricula->data_pagto_mat_didatico ?? old('data_pagto_mat_didatico') }} ">
        </div> 
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Forma de Pagamento</label>
            <input type="text" name="forma" class="form-control"">
        </div> 
    </div>


    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">            
            <button type="submit" class="btn btn-success">Enviar</button>            
        </div>
    </div>
</div>
