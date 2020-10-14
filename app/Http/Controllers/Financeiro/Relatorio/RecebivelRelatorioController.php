<?php

namespace App\Http\Controllers\Financeiro\Relatorio;

use App\Http\Controllers\Controller;
use App\Models\AnoLetivo;
use App\Models\Financeiro\Acrescimo;
use App\Models\Financeiro\ContaContabil;
use App\Models\Financeiro\Recebimento;
use App\Models\Financeiro\Recebivel;
use App\Models\FormaPagamento;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Pessoa;
use App\Models\Financeiro\TipoSituacaoRecebivel;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecebivelRelatorioController extends Controller
{
    private $repositorio;
        
    public function __construct(Recebivel $recebivel)
    {
        $this->repositorio = $recebivel;        
    }

    /**
    *Chama interface p Escolha dos campos p filtro e gerar relatório */
    public function index()
    {
        $this->authorize('Recebível Ver');   

        /* $idUnidade = User::getUnidadeEnsinoSelecionada(); */
        /* $perfilUsuario = new User;        
        $perfilUsuario = $perfilUsuario->getPerfilUsuarioUnidadeEnsino($idUnidade, Auth::id()); */
        $anosLetivos = AnoLetivo::where('fk_id_unidade_ensino', '=', session()->get('id_unidade_ensino'))
            ->orderBy('ano', 'desc')
            ->get();

        $situacoesRecebivel = TipoSituacaoRecebivel::get();

        $pessoas = new Pessoa;

        $pessoas = $pessoas
        ->select('id_pessoa', 'nome', 'situacao_pessoa')
        ->join('tb_unidades_ensino', 'fk_id_unidade_ensino', 'id_unidade_ensino')
        ->where('fk_id_tipo_pessoa', 1)
        ->where('id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())
        ->orderBy('nome', 'asc')
        ->paginate(20);

        return view('financeiro.paginas.recebiveis.relatorios.index',             
            compact('anosLetivos', 'situacoesRecebivel')); 
    }

    public function recebiveis(){

    }


}
