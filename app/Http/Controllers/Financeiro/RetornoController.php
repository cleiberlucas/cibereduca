<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRetorno;
use App\Models\Financeiro\Retorno;
use App\User;
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
        $retornos = $this->repositorio
            ->orderBy('data_retorno', 'desc')
            ->orderBy('data_processamento', 'desc')
            ->paginate('25');

        return view('financeiro.paginas.retornos.index',            
            compact('retornos'));
    }

    public function create()
    {        
        $unidadeEnsino = User::getUnidadeEnsinoSelecionada();
        return view('financeiro.paginas.retornos.create',
            compact('unidadeEnsino'));
    }

    public function store(StoreUpdateRetorno $request)
    {
        $request['data_retorno'] = '20210130';
        $request['sequencial_retorno_banco'] = '1';
        
        $dados = $request->all();

        if ($request->hasfile('nome_arquivo') && $request->nome_arquivo->isValid()) {                       
            try{
                $nomeArquivo = $request->file('nome_arquivo')->getClientOriginalName();
                $nomeArquivo = str_replace(' ', '_', $nomeArquivo);
                $dados['nome_arquivo'] = $nomeArquivo; 
                $request->file('nome_arquivo')->storeAs('boletos/retornos/processar', $nomeArquivo);            
            }
            catch(Exception $e)
            {
                return redirect()->back()->with('erro', 'Não foi possível processar o arquivo. Favor entrar em contato com a CiberSys.');
            }
            //dd($gravou);            
        }

        $this->repositorio->create($dados);

        return $this->index()->with('sucesso', 'Retorno gravado com sucesso.');
    }
}