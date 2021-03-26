<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRetorno;
use App\Models\Financeiro\Boleto;
use App\Models\Financeiro\BoletoRetorno;
use App\Models\Financeiro\DadoBancario;
use App\Models\Financeiro\Recebimento;
use App\Models\Financeiro\Retorno;
use App\User;
use Eduardokum\LaravelBoleto\Cnab\Retorno\Factory;
use Exception;

class RetornoController extends Controller
{    
    private $repositorio;
        
    public function __construct(Retorno $retorno)
    {
        $this->repositorio = $retorno;
    }

    public function index()
    {
        $this->authorize('Retorno Ver');
        $retornos = $this->repositorio
            ->orderBy('data_retorno', 'desc')
            ->orderBy('data_processamento', 'desc')
            ->paginate('25');

        return view('financeiro.paginas.retornos.index',            
            compact('retornos'));
    }

    public function create()
    {        
        $this->authorize('Retorno Cadastrar');
        $unidadeEnsino = User::getUnidadeEnsinoSelecionada();
        return view('financeiro.paginas.retornos.create',
            compact('unidadeEnsino'));
    }

    public function store(StoreUpdateRetorno $request)
    {
        $this->authorize('Retorno Cadastrar');
       //dd(User::getUnidadeEnsinoSelecionada());
      /*   if(!User::getUnidadeEnsinoSelecionada())
            return redirect()->back()->with('atencao', 'Sessão encerrada, favor efetuar login novamente.');
       */ 
        //$arquivo = 
        //dd($request->hasFile('nome_arquivo'));;
        $dados = $request->all();

        if ($request->hasfile('nome_arquivo') && $request->nome_arquivo->isValid()) {                       
            try{
                $nomeArquivo = $request->file('nome_arquivo')->getClientOriginalName();
                $nomeArquivo = str_replace(' ', '_', $nomeArquivo);
                $dados['nome_arquivo'] = $nomeArquivo; 
                $request->file('nome_arquivo')->storeAs('boletos/retornos/processados', $nomeArquivo);          
                
                $retorno = Factory::make('storage/boletos/retornos/processados' . DIRECTORY_SEPARATOR . "$nomeArquivo");
                $retorno->processar();       
                //dd($retorno->getDetalhes());                

                if(!method_exists($retorno, 'getHeaderLote'))
                    return redirect()->back()->with('erro', 'Envie somente arquivo com formato CNAB240. O arquivo está com formato incorreto.');
            
                //validar dados da conta e convênio
                $dadoBancario = $this->verificarConvenio($retorno->getHeader()->getAgencia(), $retorno->getHeader()->getConta(), $retorno->getHeaderLote()->getConvenio());
                               
                if (!isset($dadoBancario->id_dado_bancario))
                    return redirect()->back()->with('erro', 'Este arquivo não está no convênio do Colégio X Banco.');

                //verificar se retorno ja foi processado
                $retornoProcessado = $this->verificarRetornoProcessado($dadoBancario->id_dado_bancario, $retorno->getHeader()->getNumeroSequencialArquivo());
                if (isset($retornoProcessado->id_retorno))
                    return redirect()->back()->with('erro', 'Este arquivo já foi processado anteriormente.');
               
                $respLancamentos = $this->processarRetorno($retorno);
                //gravando log
                $nomeArquivoLog = 'retorno_'.$retorno->getHeader()->getNumeroSequencialArquivo().'_'.date('YmdHis').'.txt';

                $fp = fopen('storage/boletos/retornos/logs/'.$nomeArquivoLog, 'a');
                fwrite($fp, $respLancamentos[key($respLancamentos)]);
                fclose($fp);                

                if (array_key_exists('ok', $respLancamentos)){                            
                    $dados['data_retorno'] = $retorno->getHeaderLote()->getDataGravacao('Y-m-d');
                    $dados['sequencial_retorno_banco'] = $retorno->getHeader()->getNumeroSequencialArquivo();
                    $dados['fk_id_dado_bancario'] = $dadoBancario->id_dado_bancario;
                    $dados['situacao_processamento'] = 1;
                    $dados['nome_arquivo'] = $nomeArquivo;
                    $dados['nome_arquivo_log'] = $nomeArquivoLog;

                    $this->repositorio->create($dados);
                    //dd($respLancamentos['ok']);    
                    return $this->index()->with('sucesso', 'Retorno processado com sucesso.');
                }
                else{
                    //dd($respLancamentos['erro']);
                    $dados['data_retorno'] = $retorno->getHeaderLote()->getDataGravacao('Y-m-d');
                    $dados['sequencial_retorno_banco'] = $retorno->getHeader()->getNumeroSequencialArquivo();
                    $dados['fk_id_dado_bancario'] = $dadoBancario->id_dado_bancario;
                    $dados['situacao_processamento'] = 0;
                    $dados['nome_arquivo'] = $nomeArquivo;
                    $dados['nome_arquivo_log'] = $nomeArquivoLog;

                    $this->repositorio->create($dados);

                    return redirect()->back()->with('erro', 'Houve erro no processamento do retorno. Verifique o arquivo de log.');           
                }                
            }
            catch(Exception $e)
            {
                return redirect()->back()->with('erro', 'Não foi possível processar o arquivo. Favor entrar em contato com a CiberSys.'.$e->getMessage());
            }
            //dd($gravou);            
        }       
    }

    public function processarRetorno($retorno)
    {   
        //echo $retorno->getBancoNome();
        //dd($retorno->getDetalhes());

        $arrayRetorno = $retorno->getDetalhes()->toArray();
        /* $log_tmp = '';
        $quebra = chr(13).chr(10);
        foreach($arrayRetorno as $ret){
            if ($ret->valorMulta > 0 or $ret->valorMora > 0){
                $log_tmp .= 'Boleto '.$ret->nossoNumero.' Multa '.$ret->valorMulta.' Juro '.$ret->valorJuro.$quebra;
            }
            
        } */
        //dd($log_tmp);

        $recebimento = new RecebimentoController(new Recebimento);
        /**
         *  A classe recebimento controller faz:
         * - lança recebimentos
         * - atualiza recebíveis
         * - lança acrescimos, se o boleto foi pago em atraso
         * */
        $respLancamentos = $recebimento->receberBoleto($arrayRetorno);

        if (array_key_exists('erro', $respLancamentos))
            return $respLancamentos;
        
        //situações de boleto
        //convertendo as situações do boleto do Sicoob para o Cibereduca
        $situacoesBoleto = new Boleto;
        $situacoesBoleto = $situacoesBoleto->getSituacaoBoletoSicoob();
        
        $arrayBoletos = Array();
        //gerar array para atualizar a situação dos boletos.
        foreach ($arrayRetorno as $index => $itemRetorno){
            $arrayBoletos[$index]['id_boleto'] = $itemRetorno->numeroDocumento;
            $arrayBoletos[$index]['fk_id_situacao_registro'] = $situacoesBoleto[$itemRetorno->ocorrencia];            
            
            //verificar se há registro de erro no arquivo retorno            
           /*  if ($itemRetorno->ocorrencia == '06')
                dd($itemRetorno); */
            if ($itemRetorno->error != null)
                $arrayBoletos[$index]['textoOcorrencia'] = " ATENÇÃO-ERRO ".$itemRetorno->error;
            else
                $arrayBoletos[$index]['textoOcorrencia'] = $itemRetorno->ocorrenciaDescricao;
        }
        
        //lançar tarifa dos boletos
        $logBoletoRetorno = $this->gravarBoletoRetorno($retorno);
        if (array_key_exists('erro', $logBoletoRetorno))
            return $logBoletoRetorno;

        //atualizar a situação do registro do boleto
        $atualizaBoleto = new BoletoController(new Boleto);

        $logBoletos = $atualizaBoleto->updateBoletoRetorno($arrayBoletos);

        $quebra = chr(13).chr(10);
        //unindo o log dos recebimentos com log dos boletosretornos e boletos
        $logBoletos[key($logBoletos)] = $respLancamentos['ok'].$logBoletoRetorno[key($logBoletoRetorno)].$logBoletos[key($logBoletos)];

        $logResumo = '#### TOTAIS DO ARQUIVO RETORNO'.$quebra;
        foreach($retorno->getTotais() as $index => $total){
            //dd($index);
            $logResumo .= $index. ' = '.$total.$quebra;
        }

        $logBoletos[key($logBoletos)] = $logBoletos[key($logBoletos)].$logResumo;

        return $logBoletos;
    }
    
   /**
    * Grava tarifa e ocorrencia dos boletos
    * vai possibilitar identificar boletos pagos duas vezes
    */
   public function gravarBoletoRetorno($retorno)
   {
       try {
           //situações de boleto
            //convertendo as situações do boleto do Sicoob para o Cibereduca
            $situacoesBoleto = new Boleto;
            $situacoesBoleto = $situacoesBoleto->getSituacaoBoletoSicoob();

            $boletos = $retorno->getDetalhes()->toArray();
            $arrayBoletos = array();
            foreach($boletos as $index => $boleto){
                $arrayBoletos[$index]['fk_id_boleto'] = $boleto->numeroDocumento;
                $arrayBoletos[$index]['valor_tarifa'] = $boleto->valorTarifa;
                $arrayBoletos[$index]['ocorrencia'] = $situacoesBoleto[$boleto->ocorrencia];
                $arrayBoletos[$index]['fk_id_situacao_registro'] = $situacoesBoleto[$boleto->ocorrencia];
                $arrayBoletos[$index]['sequencial_retorno_banco'] = $retorno->getHeader()->getNumeroSequencialArquivo();
                
                if ($boleto->error != null)
                    $arrayBoletos[$index]['obs'] = $boleto->error;
                else
                    $arrayBoletos[$index]['obs'] = $boleto->ocorrenciaDescricao;
                
                
            }

            $quebra = chr(13).chr(10);
            /**
             * gravando tarifas e ocorrencias de todos os boletos do retorno
             */
            $log = '#### GRAVANDO TARIFAS E OCORRÊNCIAS DOS BOLETOS'.$quebra;
            $log.= 'Sequencial retorno '.$retorno->getHeader()->getNumeroSequencialArquivo().$quebra;           
            
            $boletoRetorno = new BoletoRetorno;
            foreach($arrayBoletos as $boleto){
                $boletoRetorno->create($boleto);
            }
            return array('ok' => $log);
       } catch (Exception $e) {
           //throw $th;
           return array('erro' => ''.$log.'Erro ao gravar tarifas e ocorrências dos boletos.'.$e);
       }       
   }   

    public function verificarConvenio($agencia, $conta, $convenio_retorno)
    {        
        $dadoBancario = new DadoBancario;
        $dadoBancario = $dadoBancario
            ->select('id_dado_bancario')
            ->where('agencia', $agencia)
            ->where('conta', $conta)
            ->where('convenio_retorno', $convenio_retorno)
            ->first();        
        
        return $dadoBancario;
    }
    
    public function verificarRetornoProcessado($id_dado_bancario, $sequencial_retorno)
    {
        return $this->repositorio
            ->select('id_retorno')
            ->where('fk_id_dado_bancario', $id_dado_bancario)
            ->where('sequencial_retorno_banco', $sequencial_retorno)
            ->first();            
    }
}