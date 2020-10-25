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

        /* $pessoas = new Pessoa;

        $pessoas = $pessoas
        ->select('id_pessoa', 'nome', 'situacao_pessoa')
        ->join('tb_unidades_ensino', 'fk_id_unidade_ensino', 'id_unidade_ensino')
        ->where('fk_id_tipo_pessoa', 1)
        ->where('id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())
        ->orderBy('nome', 'asc')
        ->paginate(20); */

        $usuarios = new User;
        $usuarios = $usuarios
            ->select('id', 'name')
            ->join('tb_usuarios_unidade_ensino', 'fk_id_user', 'id')
            ->where('fk_id_unidade_ensino', '=', session()->get('id_unidade_ensino'))
            ->where('situacao_vinculo', '1')
            ->orderBy('name')        
            ->get();

        $formasPagamento = new FormaPagamento;
        $formasPagamento = $formasPagamento->getFormasPagamento();

        $tiposRecebivel = new ContaContabil;
        $tiposRecebivel = $tiposRecebivel->get();

        return view('financeiro.paginas.recebiveis.relatorios.index',             
            compact('anosLetivos', 'situacoesRecebivel', 'usuarios', 'formasPagamento', 'tiposRecebivel')); 
    }

    public function recebiveis(Request $request){
        $recebiveis = new Recebivel;

        //filtro obrigatório: unidade de ensino
        $recebiveis = $recebiveis  
            ->join('tb_unidades_ensino', 'fk_id_unidade_ensino', 'id_unidade_ensino')
            ->where('tb_recebiveis.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada());

        //alguns joins necessários, mesmo se não houver filtro
        $recebiveis = $recebiveis
            ->join('tb_anos_letivos', 'tb_anos_letivos.fk_id_unidade_ensino', 'id_unidade_ensino')
            ->join('tb_contas_contabeis', 'fk_id_conta_contabil_principal', 'id_conta_contabil')
            ->join('tb_tipos_situacao_recebivel', 'fk_id_situacao_recebivel', 'id_situacao_recebivel')
            ;

        //filtrando ano letivo        
        if ($request->ano_letivo > 0)
            $recebiveis = $recebiveis->where('id_ano_letivo', $request->ano_letivo);

        //filtrando tipo recebível (conta contábil)
        if ($request->tipo_recebivel > 0)
            $recebiveis = $recebiveis->where('fk_id_conta_contabil_principal', $request->tipo_recebivel);

        if ($request->situacao_recebivel > 0)
            $recebiveis = $recebiveis->where('fk_id_tipo_situacao_recebivel', $request->situacao_recebivel);

        
        $recebiveis = $recebiveis->get();

        return view('',
                compact('')
        );
        

    }


}
