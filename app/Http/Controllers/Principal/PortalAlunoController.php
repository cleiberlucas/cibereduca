<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Financeiro\Boleto;
use App\Models\Financeiro\Recebivel;
use App\Models\Pedagogico\Frequencia;
use App\Models\Pedagogico\Nota;
use App\Models\Pedagogico\ResultadoAlunoPeriodo;
use App\Models\Secretaria\DocumentoEscola;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Pessoa;
use App\Models\UnidadeEnsino;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalAlunoController extends Controller
{   
    public function __construct()
    {
        
    }

    public function indexRendimento()
    {   
        $perfil = new User;
        $perfil = $perfil->getPerfilUsuario(Auth::id());            
        
        $matriculas = new Matricula;
        //se for responsavel
        if ($perfil->fk_id_perfil == 6){
            $matriculas = $matriculas
                ->select(
                    'id_matricula',
                    'aluno.nome as nome_aluno',
                    'id_ano_letivo', 'ano',
                    'id_turma','nome_turma',
                    'descricao_turno',
                    'sub_nivel_ensino')
                ->join('tb_pessoas as resp', 'resp.id_pessoa', 'tb_matriculas.fk_id_responsavel')            
                ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'tb_matriculas.fk_id_aluno' )
                ->Join('users', 'id', 'resp.fk_id_user')            
                ->join('tb_turmas', 'id_turma', 'fk_id_turma')
                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma')
                ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno') 
                ->where('resp.fk_id_user', Auth::id())
                ->orderBy('aluno.nome')
                ->orderBy('id_turma', 'desc')
                ->get();
        }
        else if($perfil->fk_id_perfil == 7){
            $matriculas = $matriculas
            ->select(
                'id_matricula',
                'aluno.nome as nome_aluno',
                'id_ano_letivo', 'ano',
                'id_turma','nome_turma',
                'descricao_turno',
                'sub_nivel_ensino')
            /* ->join('tb_pessoas as resp', 'resp.id_pessoa', 'tb_matriculas.fk_id_responsavel')             */
            ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'tb_matriculas.fk_id_aluno' )
            ->Join('users', 'id', 'aluno.fk_id_user')            
            ->join('tb_turmas', 'id_turma', 'fk_id_turma')
            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma')
            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno') 
            ->where('aluno.fk_id_user', Auth::id())
            ->orderBy('aluno.nome')
            ->orderBy('id_turma', 'desc')
            ->get();
        }        
        $nomeLogin = Auth::id();
        return view('portal.paginas.rendimento.index',
                compact('matriculas'));
    }

    public function indexDeclaracoes()
    {   
        $perfil = new User;
        $perfil = $perfil->getPerfilUsuario(Auth::id());            
        
        $matriculas = new Matricula;
        //se for responsavel
        if ($perfil->fk_id_perfil == 6){
            $matriculas = $matriculas
                ->select(
                    'id_matricula',
                    'aluno.nome as nome_aluno',
                    'id_ano_letivo', 'ano',
                    'id_turma','nome_turma',
                    'descricao_turno',
                    'sub_nivel_ensino')
                ->join('tb_pessoas as resp', 'resp.id_pessoa', 'tb_matriculas.fk_id_responsavel')            
                ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'tb_matriculas.fk_id_aluno' )
                ->Join('users', 'id', 'resp.fk_id_user')            
                ->join('tb_turmas', 'id_turma', 'fk_id_turma')
                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma')
                ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno') 
                ->where('resp.fk_id_user', Auth::id())
                ->orderBy('aluno.nome')
                ->orderBy('id_turma', 'desc')
                ->get();
        }
        else if($perfil->fk_id_perfil == 7){
            $matriculas = $matriculas
            ->select(
                'id_matricula',
                'aluno.nome as nome_aluno',
                'id_ano_letivo', 'ano',
                'id_turma','nome_turma',
                'descricao_turno',
                'sub_nivel_ensino')
            /* ->join('tb_pessoas as resp', 'resp.id_pessoa', 'tb_matriculas.fk_id_responsavel')             */
            ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'tb_matriculas.fk_id_aluno' )
            ->Join('users', 'id', 'aluno.fk_id_user')            
            ->join('tb_turmas', 'id_turma', 'fk_id_turma')
            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma')
            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno') 
            ->where('aluno.fk_id_user', Auth::id())
            ->orderBy('aluno.nome')
            ->orderBy('id_turma', 'desc')
            ->get();
        }        
        return view('portal.paginas.declaracoes.index',
            compact('matriculas'));
    }

    public function indexFinanceiro()
    {   
        $perfil = new User;
        $perfil = $perfil->getPerfilUsuario(Auth::id());            
        
        $matriculas = new Matricula;
        //se for responsavel
        if ($perfil->fk_id_perfil == 6){
            $matriculas = $matriculas
                ->select(
                    'id_matricula',
                    'aluno.id_pessoa',
                    'aluno.nome as nome_aluno',
                    'id_ano_letivo', 'ano',
                    'id_turma','nome_turma',
                    'descricao_turno',
                    'sub_nivel_ensino')
                ->join('tb_pessoas as resp', 'resp.id_pessoa', 'tb_matriculas.fk_id_responsavel')            
                ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'tb_matriculas.fk_id_aluno' )
                ->Join('users', 'id', 'resp.fk_id_user')            
                ->join('tb_turmas', 'id_turma', 'fk_id_turma')
                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma')
                ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno') 
                ->where('resp.fk_id_user', Auth::id())
                ->where('id_ano_letivo', '>=', 2)
                ->orderBy('aluno.nome')
                ->orderBy('id_turma', 'desc')
                ->get();
        }
        else if($perfil->fk_id_perfil == 7){
            $matriculas = $matriculas
                ->select(
                    'id_matricula',
                    'aluno.id_pessoa',
                    'aluno.nome as nome_aluno',
                    'id_ano_letivo', 'ano',
                    'id_turma','nome_turma',
                    'descricao_turno',
                    'sub_nivel_ensino')
                /* ->join('tb_pessoas as resp', 'resp.id_pessoa', 'tb_matriculas.fk_id_responsavel')             */
                ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'tb_matriculas.fk_id_aluno' )
                ->Join('users', 'id', 'aluno.fk_id_user')            
                ->join('tb_turmas', 'id_turma', 'fk_id_turma')
                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma')
                ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno') 
                ->where('aluno.fk_id_user', Auth::id())
                ->orderBy('aluno.nome')
                ->orderBy('id_turma', 'desc')
                ->get();
        }
        return view('portal.paginas.financeiro.index',
            compact('matriculas'));
    }

    public function indexOutros()
    {   
        $perfil = new User;
        $perfil = $perfil->getPerfilUsuario(Auth::id());            
        
        $matriculas = new Matricula;
        //se for responsavel
        if ($perfil->fk_id_perfil == 6){
            $matriculas = $matriculas
                ->select(
                    'id_matricula',
                    'aluno.nome as nome_aluno',
                    'id_ano_letivo', 'ano',
                    'id_turma','nome_turma',
                    'descricao_turno',
                    'sub_nivel_ensino')
                ->join('tb_pessoas as resp', 'resp.id_pessoa', 'tb_matriculas.fk_id_responsavel')            
                ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'tb_matriculas.fk_id_aluno' )
                ->Join('users', 'id', 'resp.fk_id_user')            
                ->join('tb_turmas', 'id_turma', 'fk_id_turma')
                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma')
                ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno') 
                ->where('resp.fk_id_user', Auth::id())
                ->where('id_ano_letivo', '>=', '2')
                ->orderBy('aluno.nome')
                ->orderBy('id_turma', 'desc')
                ->get();
        }
        else if($perfil->fk_id_perfil == 7){
            $matriculas = $matriculas
            ->select(
                'id_matricula',
                'aluno.nome as nome_aluno',
                'id_ano_letivo', 'ano',
                'id_turma','nome_turma',
                'descricao_turno',
                'sub_nivel_ensino')
            /* ->join('tb_pessoas as resp', 'resp.id_pessoa', 'tb_matriculas.fk_id_responsavel')             */
            ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'tb_matriculas.fk_id_aluno' )
            ->Join('users', 'id', 'aluno.fk_id_user')            
            ->join('tb_turmas', 'id_turma', 'fk_id_turma')
            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma')
            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno') 
            ->where('aluno.fk_id_user', Auth::id())
            ->orderBy('aluno.nome')
            ->orderBy('id_turma', 'desc')
            ->get();
        }
        return view('portal.paginas.outros.index',
            compact('matriculas'));
    }

    /**
     * Lista recebíveis de um aluno
     */
    public function indexRecebiveis($id_aluno, $hash){
        if (!decodificarHash($id_aluno, $hash))
            return redirect()->back()->with('erro', 'Aluno não encontrado.');
                    
        $aluno = new Pessoa;
        $aluno = $aluno->select('nome', 'id_pessoa')
            ->where('id_pessoa', $id_aluno)
            ->first();
            
        $recebiveis = new Recebivel;
        $recebiveis = $recebiveis
            ->select('id_recebivel', 'ano', 'data_vencimento', 'fk_id_conta_contabil_principal', 'parcela',
                'data_recebimento', 'valor_recebido',
                'fk_id_situacao_recebivel',
                'descricao_conta', 'tipo_turma', 'valor_total', 'nome')
            ->rightJoin('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_pessoas', 'id_pessoa', 'fk_id_aluno')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_contas_contabeis', 'fk_id_conta_contabil_principal', 'id_conta_contabil')
            ->join('tb_tipos_situacao_recebivel', 'fk_id_situacao_recebivel', 'id_situacao_recebivel')     
            ->leftJoin('tb_recebimentos', 'fk_id_recebivel', 'id_recebivel')
            ->where('fk_id_aluno', $id_aluno)
            ->where('fk_id_situacao_recebivel', '<=', '3')
            ->where('tb_recebiveis.fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())            
            ->where('id_ano_letivo', '>=', 2)
            ->orderBy('ano', 'desc')
            ->orderBy('data_vencimento')
            ->orderBy('fk_id_conta_contabil_principal')
            ->orderBy('parcela')
            ->paginate(20);

        return view('portal.paginas.financeiro.recebiveis', 
            compact('aluno', 'recebiveis')
        );
    }

    public function indexBoletos($id_aluno, $hash){
        if (!decodificarHash($id_aluno, $hash))
            return redirect()->back()->with('erro', 'Aluno não encontrado.');

        $aluno = new Pessoa;
        $aluno = $aluno
            ->select('nome', 'id_pessoa')
            ->where('id_pessoa', $id_aluno)
            ->first();

        $boletos = new Boleto;
        $boletos = $boletos
            ->select('id_boleto','tb_boletos.data_vencimento',
                'tb_boletos.valor_total', 'instrucoes_recebiveis',
                'situacao_registro', 'fk_id_situacao_registro',
                'data_recebimento'
                )
            ->join('tb_boletos_recebiveis', 'id_boleto', 'fk_id_boleto')
            ->join('tb_recebiveis', 'tb_boletos_recebiveis.fk_id_recebivel', 'id_recebivel')
            ->leftJoin('tb_recebimentos', 'tb_recebimentos.fk_id_recebivel', 'id_recebivel')
            ->join( 'tb_matriculas', 'tb_recebiveis.fk_id_matricula', 'id_matricula')
            ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
            ->join('tb_situacoes_registro', 'fk_id_situacao_registro', 'id_situacao_registro')
            ->where('fk_id_aluno', $id_aluno)     
            ->where('fk_id_situacao_registro', '!=', 5) /* não listar boletos excluídos */                 
            ->groupBy('tb_boletos.data_vencimento')
            ->groupBy('id_boleto')
            ->groupBy('tb_boletos.valor_total')
            ->groupBy('situacao_registro')
            ->groupBy('fk_id_situacao_registro')
            ->groupBy('data_recebimento')            
            ->paginate(25);

        return view('portal/paginas/financeiro/boletos', 
            compact('boletos', 'aluno'));
    }

    public function declaracoes($id_matricula){        
        $perfil = new User;
        $perfil = $perfil->getPerfilUsuario(Auth::id()); 

        $documentosEscola = new DocumentoEscola;
        $documentosEscola = $documentosEscola
            ->join('tb_matriculas', 'id_matricula', '=', 'tb_documentos_escola.fk_id_matricula')
            ->join('tb_turmas', 'id_turma', '=', 'tb_matriculas.fk_id_turma')
            ->join('tb_tipos_turmas', 'id_tipo_turma', '=', 'tb_turmas.fk_id_tipo_turma')
            ->join('tb_anos_letivos', 'id_ano_letivo', '=', 'tb_tipos_turmas.fk_id_ano_letivo')
            ->where('fk_id_matricula', $id_matricula)
            ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->orderBy('ano', 'desc')
            ->orderBy('data_geracao', 'desc')
            ->paginate();

        if (count($documentosEscola) == 0)
            return redirect()->back()->with('info', 'Nenhum documento gerado para este aluno.');

        return view('portal.paginas.declaracoes.declaracoes', [
            'documentosEscola' => $documentosEscola,
            'perfil' =>$perfil,
        ]);
    }

    public function indexNotas($id_matricula, $hash){
        if (!decodificarHash($id_matricula, $hash))
            return redirect()->back()->with('erro',  'Notas não encontradas.');

        $aluno = new Pessoa;
        $aluno = $aluno
            ->select(
                'ano',
                'nome_turma',
                'descricao_turno',
                'nome')
            ->join('tb_matriculas', 'id_pessoa', 'fk_id_aluno')
            ->join('tb_turmas', 'id_turma', 'fk_id_turma')            
            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma')            
            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno') 
            ->where('id_matricula', $id_matricula)
            ->first();

        $avaliacoes = new Nota;
        $avaliacoes = $avaliacoes->getNotasPortalAluno($id_matricula);
        return view('portal.paginas.rendimento.notas',
            compact('aluno','avaliacoes'));
    }

    public function indexFrequencias($id_matricula){
        $aluno = new Pessoa;
        $aluno = $aluno
            ->select(
                'ano',
                'nome_turma',
                'descricao_turno',
                'nome')
            ->join('tb_matriculas', 'id_pessoa', 'fk_id_aluno')
            ->join('tb_turmas', 'id_turma', 'fk_id_turma')            
            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma')            
            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno') 
            ->where('id_matricula', $id_matricula)
            ->first();

        $frequencias = new ResultadoAlunoPeriodo;
        $frequencias = $frequencias->getResultadosPortalAluno($id_matricula);
        return view('portal.paginas.rendimento.frequencias',
            compact('aluno','frequencias'));
    }

    public function defineUnidadePadrao(Request $request)
    {        
        session()->forget('id_unidade_ensino');
        session()->put('id_unidade_ensino', $request['unidadeensino']);

        return redirect()->back();
    }
    
}
