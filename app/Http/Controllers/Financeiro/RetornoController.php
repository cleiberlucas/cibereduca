<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRetorno;
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
       
       
        //$arquivo = 
        //dd($request->hasFile('nome_arquivo'));;
        $dados = $request->all();

        if ($request->hasfile('nome_arquivo') && $request->nome_arquivo->isValid()) {                       
            try{
                $nomeArquivo = $request->file('nome_arquivo')->getClientOriginalName();
                $nomeArquivo = str_replace(' ', '_', $nomeArquivo);
                $dados['nome_arquivo'] = $nomeArquivo; 
                $request->file('nome_arquivo')->storeAs('boletos/retornos/processar', $nomeArquivo);          
                //dd($dados['nome_arquivo']);
                $retorno = Factory::make('storage/boletos/retornos/processar' . DIRECTORY_SEPARATOR . "$nomeArquivo");
                $retorno->processar();
                //dd($retorno);

                //validar dados da conta e convênio
                $dadoBancario = $this->verificarConvenio($retorno->getHeader()->getAgencia(), $retorno->getHeader()->getConta(), $retorno->getHeaderLote()->getConvenio());
                //dd($dadoBancario);
               
                if (!isset($dadoBancario->id_dado_bancario))
                    return redirect()->back()->with('erro', 'Este arquivo não está no convênio do Colégio X Banco.');

                    
                //dd($id_dado_bancario);
                //dd($retorno);

                //verificar se retorno ja foi processado
                $retornoProcessado = $this->verificarRetornoProcessado($dadoBancario->id_dado_bancario, $retorno->getHeader()->getNumeroSequencialArquivo());
                if (isset($retornoProcessado->id_retorno))
                    return redirect()->back()->with('erro', 'Este arquivo já foi processado anteriormente.');

               // dd('ok');
                $this->processarRetorno($retorno);

                $request['data_retorno'] = $retorno->getHeaderLote()->getDataGravacao('Y-m-d');
                $request['sequencial_retorno_banco'] = $retorno->getHeader()->getNumeroSequencialArquivo();
            
                
            }
            catch(Exception $e)
            {
                return redirect()->back()->with('erro', 'Não foi possível processar o arquivo. Favor entrar em contato com a CiberSys.'.$e->getMessage());
            }
            //dd($gravou);            
        }

        $this->repositorio->create($dados);

        return $this->index()->with('sucesso', 'Retorno gravado com sucesso.');
    }

    public function processarRetorno($retorno)
    {   
        //echo $retorno->getBancoNome();
        
        $arrayRetorno = $retorno->getDetalhes()->toArray();
        $recebimento = new RecebimentoController(new Recebimento);
        //dd($retorno->getDetalhes());
        $recebimento->receberBoleto( $retorno->getDetalhes()->toArray());
        //dd($arrayRetorno[1]);
        
        foreach ($arrayRetorno as $itemRetorno){
            //dd($itemRetorno->valorMulta);
            $ocorrencia = $itemRetorno->ocorrencia;
            $idBoleto = $itemRetorno->numeroDocumento;
            $data_recebimento = $itemRetorno->dataOcorrencia; // formato brasileiro
            $valorBoleto = $itemRetorno->valor;
            $valorDesconto = $itemRetorno->valorDesconto;
            $valorMora = $itemRetorno->valorMora;
            $valorMulta = $itemRetorno->valorMulta;
            $valor_recebido = $itemRetorno->valorRecebido;
            $valor_tarifa = $itemRetorno->valorTarifa;

           

            /*se pagou boleto atrasado com juro e multa
                -> ratear valor da multa e juros entre os recebiveis do boleto
                -> lançar em acrescimos                
            */

            //atualizar a situação do registro do boleto

            //gravar tarifas pagas

            //quantidade de baixas de boletos, boletos pagos mais de uma vez

            //gerar log

            //verificar se há registro de erro no arquivo retorno

            //não preciso preocupar com tabela tb_acrescimos

            dd($itemRetorno);
          //  dd($data_recebimento = $itemRetorno->dataOcorrencia);
            
        }
        

        dd($retorno->getDetalhes()->toArray());
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