<?php

namespace App\Http\Controllers\Financeiro\Relatorio;

use App\Http\Controllers\Controller;
use App\Models\AnoLetivo;
use App\Models\Financeiro\ContaContabil;
use App\Models\Financeiro\Recebivel;
use App\Models\FormaPagamento;
use App\Models\Financeiro\TipoSituacaoRecebivel;
use App\Models\Secretaria\Turma;
use App\User;
use Illuminate\Http\Request;

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
        $this->authorize('Recebível Relatório');   

        $anosLetivos = AnoLetivo::where('fk_id_unidade_ensino', '=', session()->get('id_unidade_ensino'))
            ->orderBy('ano', 'desc')
            ->get();

        $situacoesRecebivel = TipoSituacaoRecebivel::get();

        $usuarios = new User;
        $usuarios = $usuarios->getUsuariosColegio();            

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
            ->join('tb_unidades_ensino', 'tb_recebiveis.fk_id_unidade_ensino', 'id_unidade_ensino')
            ->where('tb_recebiveis.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada());

        //alguns joins necessários, mesmo se não houver filtro
        $recebiveis = $recebiveis
            ->select(
                'nome_turma',
                'ano', 
                'aluno.nome as nome_aluno',
                'resp.nome as nome_responsavel',
                'descricao_conta',
                'id_recebivel',
                'parcela',
                'valor_total',
                'data_vencimento',
                'fk_id_situacao_recebivel',
                'situacao_recebivel',
                'forma_pagamento', 
                'data_recebimento',
                'valor_recebido',
                )
            ->join('tb_matriculas', 'tb_recebiveis.fk_id_matricula', 'id_matricula')
            ->join('tb_pessoas as aluno', 'tb_matriculas.fk_id_aluno', 'aluno.id_pessoa')
            ->join('tb_pessoas as resp', 'tb_matriculas.fk_id_responsavel', 'resp.id_pessoa')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', 'id_ano_letivo')            
            ->join('tb_contas_contabeis', 'fk_id_conta_contabil_principal', 'id_conta_contabil')
            ->join('tb_tipos_situacao_recebivel', 'fk_id_situacao_recebivel', 'id_situacao_recebivel')
            ->leftJoin('tb_recebimentos', 'fk_id_recebivel', 'id_recebivel')
            ->leftJoin('tb_formas_pagamento', 'tb_recebimentos.fk_id_forma_pagamento', 'id_forma_pagamento')
            ->leftJoin('users', 'fk_id_usuario_recebimento', 'id')
            
            ;
        
            $filtroAplicado = '';

        /**
         * Se preencher esses campos, não precisa verificar os demais campos, pq vai retornar apenas 1 resultado
         * Código validação ou
         * Número recibo
         */
        if (strlen($request->codigo_validacao) > 0 or strlen($request->numero_recibo) > 0){
            if (strlen($request->codigo_validacao) > 0){
                $recebiveis = $recebiveis->where('codigo_validacao', $request->codigo_validacao);
                $filtroAplicado .= "<br>-Código de Validação = ".$request->codigo_validacao;
            }
            if (strlen($request->numero_recibo) > 0){
                $recebiveis = $recebiveis->where('numero_recibo', $request->numero_recibo);
                $filtroAplicado .= "<br>-Número do Recibo = ".$request->numero_recibo;
            }
        }        
        else{
            //filtrando ano letivo        
            if ($request->anoLetivo > 0){
                $recebiveis = $recebiveis->where('id_ano_letivo', $request->anoLetivo);

                $anoLetivo = AnoLetivo::where('id_ano_letivo', $request->anoLetivo)->first();
                $filtroAplicado .= '<br>-Ano Letivo = "'.$anoLetivo->ano.'"';
            }

            //filtranto turma
            if ($request->turma > 0){
                $recebiveis = $recebiveis->where('fk_id_turma', $request->turma);                

                $turma = Turma::where('id_turma', $request->turma)->first();
                $filtroAplicado .= '<br>-Turma = "'.$turma->nome_turma.'"';
            }

            //filtrando tipo recebível (conta contábil)
            if ($request->tipo_recebivel > 0){
                //dd($request->tipo_recebivel);
                $recebiveis = $recebiveis->where('fk_id_conta_contabil_principal', $request->tipo_recebivel);

                $tipoRecebivel = ContaContabil::where('id_conta_contabil', $request->tipo_recebivel)->first();
                $filtroAplicado .= '<br>-Tipo Recebimento = "'.$tipoRecebivel->descricao_conta.'"';
            }

            //filtrando situação do recebível
            if ($request->situacao_recebivel > 0){
                $recebiveis = $recebiveis->where('fk_id_situacao_recebivel', $request->situacao_recebivel);
                
                $situacaoRecebivel = TipoSituacaoRecebivel::where('id_situacao_recebivel', $request->situacao_recebivel)->first();
                $filtroAplicado .= '<br>-Situação = "'.$situacaoRecebivel->situacao_recebivel.'"';
            }

            if (strlen($request->nome_aluno) > 3){
                $recebiveis = $recebiveis->where('aluno.nome', 'like', '%'.$request->nome_aluno.'%');
                $filtroAplicado .= '<br>-Nome do aluno contém "'.$request->nome_aluno.'"';
            }

            if (strlen($request->nome_responsavel) > 3){
                $recebiveis = $recebiveis->where('resp.nome', 'like', '%'.$request->nome_responsavel.'%');
                $filtroAplicado .= '<br> -Nome do responsável contém "'.$request->nome_responsavel.'"';
            }

            //filtrando data vencimento início
            if ($request->data_vencimento_inicio != null){
                $recebiveis = $recebiveis->where('data_vencimento', '>=', $request->data_vencimento_inicio);
                $filtroAplicado .= "<br>-Data Vencimento a partir de ".date('d/m/Y', strtotime($request->data_vencimento_inicio));
            }

            //filtrando data vencimento fim
            if ($request->data_vencimento_fim != null){
                $recebiveis = $recebiveis->where('data_vencimento', '<=', $request->data_vencimento_fim);
                $filtroAplicado .= "<br>-Data Vencimento até ".date('d/m/Y', strtotime($request->data_vencimento_fim));
            }

            //filtrando data recebimento início
            if ($request->data_recebimento_inicio != null){
                $recebiveis = $recebiveis->where('data_recebimento', '>=', $request->data_recebimento_inicio);
                $filtroAplicado .= "<br>-Data Pagamento a partir de ".date('d/m/Y', strtotime($request->data_recebimento_inicio));
            }

            //filtrando data recebimento início
            if ($request->data_recebimento_fim != null){
                $recebiveis = $recebiveis->where('data_recebimento', '<=', $request->data_recebimento_fim);
                $filtroAplicado .= "<br>-Data Pagamento até ".date('d/m/Y', strtotime($request->data_recebimento_fim));
            }

            //filtrando forma de pagamento
            if ($request->forma_pagamento  > 0){
                $recebiveis = $recebiveis->where('fk_id_forma_pagamento', $request->forma_pagamento);

                $formaPagamento = FormaPagamento::where('id_forma_pagamento', $request->forma_pagamento)->first();
                $filtroAplicado .= '<br>-Forma de Pagamento = "'.$formaPagamento->forma_pagamento.'"';
            }

            //filtrando usuário recebimento
            if($request->recebido_por > 0){
                $recebiveis = $recebiveis->where('fk_id_usuario_recebimento', $request->recebido_por);

                $recebidoPor = User::where('id', $request->recebido_por)->first();
                $filtroAplicado .= '<br>-Recebido por "'.$recebidoPor->name.'"';
            }


        }
        
        if ($request->ordem){
            $recebiveis = $recebiveis->orderBy($request->ordem);            
        }

        $recebiveis = $recebiveis
            ->orderBy('ano')
            ->orderBy('descricao_conta')
            ->orderBy('data_vencimento')
            ->get();

        return view('financeiro.paginas.recebiveis.relatorios.recebiveis',
                compact('recebiveis', 'filtroAplicado')
        );        

    }


}
