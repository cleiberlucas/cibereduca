    
    <input type="hidden" name="fk_id_usuario" value={{Auth::id()}}>

    @include('admin.includes.alerts')
    @csrf
    
    <div class="row ml-3">                        
        <div class="form-check">
            <strong>* Opção Educacional:</strong>
            <br><br>
            <input class="form-check-input" type="radio" name="opcao_educacional" id="1" value="1" required 
            @if (isset($opcaoEducacional) and $opcaoEducacional->opcao_educacional == 1)
                checked
            @endif
            >
            <label for="1" class="form-check-label">Ensino Híbrido</label>                            
        </div>
    </div>

    <div class="row my-3 ml-3">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="opcao_educacional" id="2" value="2" 
                @if (isset($opcaoEducacional) and $opcaoEducacional->opcao_educacional == 2)
                    checked
                @endif
            >
            <label for="2" class="form-check-label">Ensino Remoto</label>                            
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-xs-2">
            <label>Observações:</label><br>
            <textarea name="observacao"  rows="3" class="form-control">{{$opcaoEducacional->observacao ?? old('observacao')}}</textarea>
        </div>
    </div>
        
    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">            
            * Campos Obrigatórios<br>
            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
        </div>
    </div>
</div>

<?php $versao_rand = rand();?>
<script type="text/javascript" src="/js/populaAlunos.js?v=<?php echo urlencode(base64_decode((str_shuffle('cibereduca'))))?>&<?php echo $versao_rand ?>"></script>

<script>   
    $(document).ready(function(){
          $(".alert").slideDown(300).delay(5000).slideUp(300);
    });  
</script>
