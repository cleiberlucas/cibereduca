    
    <input type="hidden" name="fk_id_usuario" value={{Auth::id()}}>

   
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
            <label for="1" class="form-check-label"><b>Ensino Híbrido</b></label>   
            <p>
            Nesta condição, declaro(amos) ciência de que o ano letivo
                        poderá ser desenvolvido com <b>atividades presenciais e/ou remotas</b>, com permissão expressa para
                        alteração de formato conforme a necessidade indicada pela escola CONTRATADA a partir das
                        determinações e recomendações dos órgãos públicos e aceito(amos) a aplicação dos protocolos
                        sanitários nas instalações escolares, ciente(s) dos riscos e implicações com a circulação de pessoas
                        devido à pandemia do novo coronavirus/COVID-19. Assumo(imos) o compromisso de colaborar no
                        combate à doença e afastar o(a) aluno(a) na ocorrência de febre ou sintomas de gripe até a
                        confirmação médica de cessação do risco de contágio.                         
                    </p>
        </div>
    </div>

    <div class="row my-3 ml-3">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="opcao_educacional" id="2" value="2" 
                @if (isset($opcaoEducacional) and $opcaoEducacional->opcao_educacional == 2)
                    checked
                @endif
            >
            <label for="2" class="form-check-label"><b>Ensino Remoto Exclusivo</b></label>       
            <p>Nesta condição, declaro(amos) ciência de
                que o ALUNO somente terá <b>atividades letivas não presenciais</b> enquanto durar a pandemia, e
                aceito(amos) a realização de aulas, provas e exercícios por meio de plataforma digital.
                Assumo(imos) o compromisso de prestar assistência doméstica ao(à) o aluno(a) incentivando(a) e
                apoiando(a) para o progresso do aprendizado.
                </p>                     
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-xs-2">
          <p>
            Em qualquer uma das opções, declaro(amos) ciência de que os serviços educacionais estão tendo
            continuidade, e por isso são mantidas as condições financeiras combinadas na matrícula, e que o
            acesso à internet doméstica e disponibilização de computador, tablet ou smartphone é
            responsabilidade da família.
          </p>
          
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
