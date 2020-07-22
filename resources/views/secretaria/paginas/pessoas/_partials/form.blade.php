@include('admin.includes.alerts')

<div class="row">    
    <div class="form-group col-sm-4 col-xs-12">
        {{-- TIPO PESSOA {{$pessoa->tipoPessoa->tipo_pessoa}} --}}
        
        @if (isset($tipo_pessoa) &&  $tipo_pessoa == 'aluno')
            <input type="hidden" name="fk_id_tipo_pessoa" value="1">
        @elseif (isset($tipo_pessoa) &&  $tipo_pessoa == 'responsavel')
            <input type="hidden" name="fk_id_tipo_pessoa" value="2"> 
        @endif
        
        <input type="hidden" name="fk_id_user_alteracao" value="{{Auth::id()}}">
        <label>* Nome:</label>
        <input type="text" name="nome" class="form-control" placeholder="Nome" required value="{{ $pessoa->nome ?? old('nome') }}">
    </div>
    <div class="form-group col-sm-4 col-xs-12">
        <label>Foto:</label>
        <input type="file" name="foto" class="form-control">
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-2 col-xs-2">            
        <label>Tipo Identidade</label>
        <select name="fk_id_tipo_doc_identidade" id="fk_id_tipo_doc_identidade" class="form-control">
            <option value=""></option>
            @foreach ($tiposDocIdentidade as $tipoDocIdentidade)
                <option value="{{$tipoDocIdentidade->id_tipo_doc_identidade }}"
                    @if (isset($pessoa) && $tipoDocIdentidade->id_tipo_doc_identidade == $pessoa->fk_id_tipo_doc_identidade)
                        selected="selected"
                    @endif
                    >                    
                    {{$tipoDocIdentidade->tipo_doc_identidade}}</option>
            @endforeach
        </select>
    </div> 

    <div class="form-group col-sm-6 col-xs-12">
        <label>Identidade:</label>
        <input type="text" name="doc_identidade" class="form-control" placeholder="Documento de Identidade" value="{{ $pessoa->doc_identidade ?? old('doc_identidade') }}">
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-2 col-xs-12">
        <label>CPF:</label>
        <input type="text" name="cpf" id="cpf" class="form-control" placeholder="CPF" value="{{ $pessoa->cpf ?? old('cpf') }}">
    </div>    
    
    <div class="form-group col-sm-3 col-xs-10">
        <label>Data Nascimento:</label>
        <input type="date" name="data_nascimento" class="form-control" placeholder="Data de Nascimento" value="{{ $pessoa->data_nascimento ?? old('data_nascimento') }}">
        <div class="input-group-addon" >
            <span class="glyphicon glyphicon-th"></span>
        </div>            
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-4 col-xs-12">
        <label>Nome do Pai:</label>
        <input type="text" name="pai" class="form-control" placeholder="Pai" value="{{ $pessoa->pai ?? old('pai') }}">
    </div>
    <div class="form-group col-sm-4 col-xs-12">
        <label>Nome da Mãe:</label>
        <input type="text" name="mae" class="form-control" placeholder="Mãe" value="{{ $pessoa->mae ?? old('mae') }}">
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-2 col-xs-12">
        <label>Telefone Principal:</label>
        <input type="text" name="telefone_1" id="telefone_1" class="form-control" placeholder="Telefone com DDD" value="{{ $pessoa->telefone_1 ?? old('telefone_1') }}">
    </div>        
    <div class="form-group col-sm-2 col-xs-12">
        <label>Telefone opcional:</label>
        <input type="text" name="telefone_2" id="telefone_2" class="form-control" placeholder="Telefone 2 com DDD" value="{{ $pessoa->telefone_2 ?? old('telefone_2') }}">
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-4 col-xs-12">        
        <label>E-mail:</label>
        <input type="email" name="email_1" class="form-control" placeholder="E-mail principal" value="{{ $pessoa->email_1 ?? old('email_1') }}">
    </div>
    <div class="form-group col-sm-4 col-xs-12">        
        <label>E-mail opcional:</label>
        <input type="email" name="email_2" class="form-control" placeholder="E-mail opcional" value="{{ $pessoa->email_2 ?? old('email_2') }}">
    </div>
</div>

   
<script>
    $(document).ready(function ($) { 
        var $seuCampoCpf = $("#cpf");
        $seuCampoCpf.mask('000.000.000-00', {reverse: true});
    });

    $(document).ready(function ($) { 
        var $fone1 = $("#telefone_1");
        $fone1.mask('(00) 00000-0000', {reverse: false});
    });

    $(document).ready(function ($) { 
        var $fone2 = $("#telefone_2");
        $fone2.mask('(00) 90000-0000', {reverse: false});
    });
</script>

<script>
    $(document).ready(function(){
          $(".alert").slideDown(300).delay(5000).slideUp(300);
    });    
</script>


