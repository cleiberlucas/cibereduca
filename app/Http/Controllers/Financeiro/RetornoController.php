<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRetorno;
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
       
        $request['data_retorno'] = '20210130';
        $request['sequencial_retorno_banco'] = '1';
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
               
                
            }
            catch(Exception $e)
            {
                return redirect()->back()->with('erro', 'Não foi possível processar o arquivo. Favor entrar em contato com a CiberSys.');
            }
            //dd($gravou);            
        }
        $this->processarRetorno($request->file('nome_arquivo')->getClientOriginalName());

        $this->repositorio->create($dados);

        return $this->index()->with('sucesso', 'Retorno gravado com sucesso.');
    }

    public function processarRetorno($arquivo)
    {
        //require 'autoload.php';
        //dd($arquivo);

        $retorno = Factory::make('storage/boletos/retornos/processar' . DIRECTORY_SEPARATOR . "$arquivo");
        $retorno->processar();

        echo $retorno->getBancoNome();
        dd($retorno);
        $arrayRetorno = $retorno->getDetalhes()->toArray();
        //dd($arrayRetorno[1]);
        foreach ($arrayRetorno as $itemRetorno){
            //dd($itemRetorno->ocorrencia);
            $ocorrencia = $itemRetorno->ocorrencia;
            $idBoleto = $itemRetorno->numeroDocumento;
            $data_recebimento = $itemRetorno->dataOcorrencia; // formato brasileiro
            $valorBoleto = $itemRetorno->valor;
            $valorDesconto = $itemRetorno->valorDesconto;
            $valorMora = $itemRetorno->valorMora;
            $valorMulta = $itemRetorno->valorMulta;
            $valor_recebido = $itemRetorno->valorRecebido;
            $valor_tarifa = $itemRetorno->valorTarifa;

            //identificar recebiveis de um boleto
            
            //separar/identificar o valor pago entre os recebiveis
            
            //lançar o recebimento separado de cada recebível de um boleto

            /*se pagou boleto atrasado com juro e multa
            lançar em acrescimos*/

            //atualizar a situação do registro do boleto

            //não preciso preocupar com tabela tb_acrescimos

            dd($itemRetorno);
          //  dd($data_recebimento = $itemRetorno->dataOcorrencia);
            
        }
        

        dd($retorno->getDetalhes()->toArray());
    }
}