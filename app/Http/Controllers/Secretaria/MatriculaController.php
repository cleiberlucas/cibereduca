<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Admin\ACL\UserController;
use App\Http\Controllers\Admin\ACL\UserUnidadeEnsinoController;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateMatricula;
use App\Models\FormaPagamento;
use App\Models\Secretaria\ContratoAtividadeExtraCurricular;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Pessoa;
use App\Models\Secretaria\Turma;
use App\Models\TipoDescontoCurso;
use App\Models\SituacaoMatricula;
use App\Models\TipoAtendimentoEspecializado;
use App\Models\UnidadeEnsino;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MatriculaController extends Controller
{
    private $repositorio;
    
    public function __construct(Matricula $matricula)
    {
        $this->repositorio = $matricula;
    }

    /**
     * Lista alunos matriculados em uma turma
     * @param request
     */
    public function index(Request $request)
    {       
        /* $matriculas = $this->repositorio->where('fk_id_turma', $request->segment(2))
                                        ->paginate(25); */
        $matriculas = $this->repositorio
            ->select('id_matricula', 'nome', 'situacao_matricula', 'fk_id_aluno')
            ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
            ->join('tb_situacoes_matricula', 'fk_id_situacao_matricula', 'id_situacao_matricula')
            ->where('fk_id_turma', $request->segment(2))
            ->orderBy('nome')
            ->paginate(25);
        
         $turma = Turma::select('tb_turmas.nome_turma', 'tb_turmas.id_turma', 'tb_turmas.limite_alunos', 'tb_anos_letivos.ano', 'tb_turnos.descricao_turno', 'tb_sub_niveis_ensino.sub_nivel_ensino')                            
                            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')         
                            ->where('tb_turmas.id_turma', '=', $request->segment(2)) 
                            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())                        
                            ->first();          
                
        return view('secretaria.paginas.matriculas.index', [
                    'matriculas' => $matriculas,
                    'turma'      => $turma,
                    'quantMatriculas'    => $this->repositorio->quantMatriculasTurma($turma->id_turma),
                    'quantVagasDisponiveis' => $this->repositorio->quantVagasDisponiveis($turma->id_turma),
        ]);
    }

    public function create(Request $request)
    {   
        $this->authorize('Matrícula Cadastrar');   
        $turma = Turma::select('tb_turmas.nome_turma', 'tb_turmas.id_turma', 'tb_turmas.limite_alunos',
                        'tb_anos_letivos.ano', 'tb_turnos.descricao_turno', 'tb_sub_niveis_ensino.sub_nivel_ensino',
                        'tb_tipos_turmas.valor_curso', 'tb_tipos_turmas.valor_matricula', 'tb_tipos_turmas.valor_material_didatico','tb_tipos_turmas.fk_id_ano_letivo' )
                        ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                        ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                        ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                        ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')         
                        ->where('tb_turmas.id_turma', '=', $request->segment(4))     
                        ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())                    
                        ->first(); 
       
        $formasPagto = new FormaPagamento;

        $tiposDesconto = new TipoDescontoCurso;

        $situacoesMatricula = new SituacaoMatricula;

        $tiposAtendimentoEspecializado = new TipoAtendimentoEspecializado;

        $pessoa = new Pessoa;
        //dd($turma->id_turma);
        //dd($this->repositorio->quantMatriculas($turma->fk_id_turma));
        return view('secretaria.paginas.matriculas.create', [
                    'alunos'                => $pessoa->alunosNaoMatriculados($turma->fk_id_ano_letivo),
                    'responsaveis'          => $pessoa->getResponsaveis(),
                    'turma'                 => $turma,
                    'formasPagto'           => $formasPagto->getFormasPagamento(),
                    'tiposDesconto'         => $tiposDesconto->getTiposDescontoCurso(),
                    'situacoesMatricula'    => $situacoesMatricula->getSituacoesMatricula(),
                    'quantMatriculas'       => $this->repositorio->quantMatriculasTurma($turma->id_turma),
                    'quantVagasDisponiveis' => $this->repositorio->quantVagasDisponiveis($turma->id_turma),
                    'tiposAtendimentoEspecializado' => $tiposAtendimentoEspecializado->getTiposAtendimentoEspecializado(),
        ]);
    }

    public function store(StoreUpdateMatricula $request )
    {
        $dados = $request->all();
        /* $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit); */
        //dd($request->fk_id_turma);
        try{
            $matricula = $this->repositorio->create($dados);
            Log::channel('secretaria_matricula')->info('Usuário '.Auth::id(). ' - Matrícula Cadastrar '.$matricula->id_matricula);
        } catch(\Throwable $qe) {
            return redirect()->back()->with('erro', 'Não foi possível gravar a matrícula. Possivelmente, o aluno já esteja matriculado nesta turma.');
        }
        
        return redirect()->route('matriculas.index', $request->fk_id_turma)->with('sucesso', 'Matrícula efetuada com sucesso.');
    }

    public function show($id)
    {
        //$matricula = $this->repositorio->where('id_matricula', $id)->first();
        $this->authorize('Matrícula Ver');   

        $matricula = $this->repositorio
                            ->select('tb_matriculas.*', 
                                        'aluno.nome as nome_aluno', 'aluno.foto',
                                        'respons.nome as nome_responsavel',
                                        'respons.telefone_1',
                                        'tb_turmas.nome_turma',
                                        'tb_anos_letivos.ano',
                                        'tb_turnos.descricao_turno',                                        
                                        'user_cadastro.name as nome_user_cadastro',
                                        'user_altera.name as nome_user_altera'
                                    )
                            ->join('tb_pessoas as aluno', 'aluno.id_pessoa', '=', 'fk_id_aluno')
                            ->join('tb_pessoas as respons', 'respons.id_pessoa', '=', 'fk_id_responsavel')
                            
                            ->leftJoin('users as user_cadastro', 'user_cadastro.id', 'tb_matriculas.fk_id_user_cadastro')
                            ->leftJoin('users as user_altera', 'user_altera.id', 'tb_matriculas.fk_id_user_altera')
                            ->join('tb_turmas', 'tb_turmas.id_turma', '=', 'tb_matriculas.fk_id_turma')
                            ->join('tb_tipos_turmas', 'tb_tipos_turmas.id_tipo_turma', '=', 'tb_turmas.fk_id_tipo_turma')
                            ->join('tb_anos_letivos', 'tb_anos_letivos.id_ano_letivo', '=', 'tb_tipos_turmas.fk_id_ano_letivo')
                            ->join('tb_turnos', 'tb_turnos.id_turno', '=', 'tb_turmas.fk_id_turno')                            
                            ->where('id_matricula', $id)
                            ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                            ->first();

        if (!$matricula)
            return redirect()->back();

        return view('secretaria.paginas.matriculas.show', [
            'matricula' => $matricula
        ]);
    }

    public function destroy($id)
    {
        $matricula = $this->repositorio->where('id_matricula', $id)->first();

        if (!$matricula)
            return redirect()->back();

        $matricula->where('id_matricula', $id)->delete();
        return redirect()->route('matriculas.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $matriculas = $this->repositorio->search($request->filtro);
        
        return view('secretaria.paginas.matriculas.index', [
                    'matricula' => $matriculas,
                    'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $this->authorize('Matrícula Alterar');   
        
        $alunos = Pessoa::select('id_pessoa', 'nome')
                    ->where('fk_id_tipo_pessoa', '=', '1')
                    ->where('situacao_pessoa', 1)
                    ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                    ->orderBy('nome')
                    ->get();

        $responsaveis = Pessoa::select('id_pessoa', 'nome')
                        ->where('fk_id_tipo_pessoa', '=', '2')
                        ->where('situacao_pessoa', 1)
                        ->orderBy('nome')
                        ->get();

        $formasPagto = FormaPagamento::select('*')->orderBy('forma_pagamento')->get();

        $tiposDesconto = TipoDescontoCurso::select('*')->orderBy('tipo_desconto_curso')->get();

        $situacoesMatricula = SituacaoMatricula::select('*')->orderBy('situacao_matricula')->get();

        $tiposAtendimentoEspecializado = new TipoAtendimentoEspecializado;

        $matricula = $this->repositorio
                                ->select('tb_matriculas.*', 
                                        'tb_turmas.nome_turma', 
                                        'tb_anos_letivos.ano', 
                                        'tb_turnos.descricao_turno', 
                                        'tb_sub_niveis_ensino.sub_nivel_ensino',
                                        'aluno.nome as nome_aluno', 'aluno.id_pessoa as id_aluno', 'aluno.foto',
                                        'responsavel.nome as nome_responsavel', 'responsavel.id_pessoa as id_responsavel',                                        
                                        'tb_situacoes_matricula.*',
                                        'tb_tipos_turmas.valor_curso') 
                                ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'tb_matriculas.fk_id_aluno')
                                ->join('tb_pessoas as responsavel', 'responsavel.id_pessoa', 'tb_matriculas.fk_id_responsavel')
                                ->join('tb_situacoes_matricula', 'tb_situacoes_matricula.id_situacao_matricula', 'tb_matriculas.fk_id_situacao_matricula')                                
                                ->join('tb_turmas', 'tb_turmas.id_turma', 'tb_matriculas.fk_id_turma')                               
                                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                                ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                                ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')   
                                ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())      
                                ->where('id_matricula', $id)->first();

/*                                 ->join('tb_formas_pagamento as forma_pagto_matricula', 'forma_pagto_matricula.id_forma_pagamento', 'tb_matriculas.fk_id_forma_pagto_matricula')
                                ->join('tb_formas_pagamento as forma_pagto_curso', 'forma_pagto_curso.id_forma_pagamento', 'tb_matriculas.fk_id_forma_pagto_curso')
                                ->join('tb_formas_pagamento as forma_pagto_mat_did', 'forma_pagto_mat_did.id_forma_pagamento', 'tb_matriculas.fk_id_forma_pagto_mat_didatico') */
        //dd($matricula);
        if (!$matricula)
            return redirect()->back();
                
        return view('secretaria.paginas.matriculas.edit',[
                        'matricula'          => $matricula,
                        'alunos'             => $alunos,
                        'responsaveis'       => $responsaveis,
                        'formasPagto'        => $formasPagto,
                        'tiposDesconto'      => $tiposDesconto,
                        'situacoesMatricula' => $situacoesMatricula,
                        'tiposAtendimentoEspecializado' => $tiposAtendimentoEspecializado->getTiposAtendimentoEspecializado(),
        ]);
    }

    /* Função exclusiva para alteração da turma do aluno */
    public function editTurma($id)
    {
        $this->authorize('Matrícula Alterar');          

        $matricula = $this->repositorio
                                ->select('tb_matriculas.id_matricula', 'tb_matriculas.fk_id_turma', 
                                        'tb_turmas.nome_turma', 
                                        'tb_anos_letivos.ano', 'tb_anos_letivos.id_ano_letivo',
                                        'tb_turnos.descricao_turno', 
                                        'tb_sub_niveis_ensino.sub_nivel_ensino',
                                        'aluno.nome as nome_aluno', 'aluno.id_pessoa as id_aluno', 'aluno.foto',                                                                                
                                        ) 
                                ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'tb_matriculas.fk_id_aluno')                                                                
                                ->join('tb_turmas', 'tb_turmas.id_turma', 'tb_matriculas.fk_id_turma')                               
                                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                                ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                                ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')   
                                ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())      
                                ->where('id_matricula', $id)
                                ->first();

        if (!$matricula)
            return redirect()->back();

        $turmas = new Turma;

        $turmas = Turma::select('id_turma', 'nome_turma', 'descricao_turno')
            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma')
            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')            
            ->where('situacao_turma', 1)
            ->where('fk_id_ano_letivo', $matricula->id_ano_letivo)
            ->orderBy('tb_anos_letivos.ano', 'desc')
            ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino', 'asc')
            ->orderBy('nome_turma', 'asc')
            ->orderBy('tb_turnos.descricao_turno', 'asc')
            ->get();

        /* $turmas = $turmas
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')            
            ->where('situacao_turma', 1)
            ->where('fk_id_ano_letivo', $matricula->id_ano_letivo)
            ->get(); */
                
        return view('secretaria.paginas.matriculas.edit_aluno_turma',[
                        'matricula'          => $matricula,
                        'turmas'             => $turmas,
                        
        ]);
    }

    public function update(StoreUpdateMatricula $request, $id)
    {
        $matricula = $this->repositorio->where('id_matricula', $id)->first();

        if (!$matricula)
            return redirect()->back();
        
        /* $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit); */

        $matricula->where('id_matricula', $id)->update($request->except('_token', '_method'));
        Log::channel('secretaria_matricula')->info('Usuário '.Auth::id(). ' - Matrícula Alterar '.$id);

        return redirect()->route('matriculas.index', $matricula->fk_id_turma);
    }

    public function updateTurma(Request $request, $id)
    {
        $matricula = $this->repositorio->where('id_matricula', $id)->first();

        if (!$matricula)
            return redirect()->back()->with('erro', 'Matrícula não encontrada.');
        
        $matricula->where('id_matricula', $id)->update($request->except('_token', '_method'));

        return redirect()->route('matriculas.index', $matricula->fk_id_turma)->with('sucesso', 'A turma do aluno foi alterada com sucesso.');
    }

    public function imprimirContrato($id_matricula, $hash)
    {
        $this->authorize('Matrícula Contrato Ver');  
        if (!decodificarHash($id_matricula, $hash))
            return redirect()->back()->with('erro', 'Matrícula não encontrada');
            
        $matricula = $this->repositorio->where('id_matricula', $id_matricula)->first();
        /* $corpoContrato = new CorpoContrato;
        $corpoContrato = $corpoContrato->where('fk_id_unidade_ensino', $matricula->turma->tipoTurma->anoLetivo->fk_id_unidade_ensino)->first(); */

        $unidadeEnsino = new UnidadeEnsino;
        $unidadeEnsino = $unidadeEnsino->where('id_unidade_ensino', $matricula->turma->tipoTurma->anoLetivo->fk_id_unidade_ensino)->first();
        
       /*  $pdf = PDF::loadView('secretaria.paginas.matriculas.contrato', compact('matricula', 'corpoContrato', 'unidadeEnsino')); */
        //$pdf = PDF

        /* return $pdf->setPaper('a4')->stream('contrato.pdf'); */
        if ($matricula->turma->tipoTurma->anoLetivo->ano == 2020){
            return view('secretaria.paginas.matriculas.contrato_2020', [
                'matricula' => $matricula,
                /* 'corpoContrato' => $corpoContrato, */
                'unidadeEnsino' =>$unidadeEnsino,
            ]);
        }else  if ($matricula->turma->tipoTurma->anoLetivo->ano >= 2021){
            $contratosExtraCurriculares = ContratoAtividadeExtraCurricular::
                select('tb_contratos_atividades_extracurriculares.*',
                    'tipo_atividade_extracurricular', 'titulo_contrato',
                    'formaPagtoCurso.forma_pagamento as forma_pagto_curso',
                    'formaPagtoMaterial.forma_pagamento as forma_pagto_material'
                    )
                ->join('tb_tipos_atividades_extracurriculares', 'fk_id_tipo_atividade_extracurricular', 'id_tipo_atividade_extracurricular')
                ->leftJoin('tb_formas_pagamento as formaPagtoCurso', 'formaPagtoCurso.id_forma_pagamento', 'fk_id_forma_pagto_ativ')
                ->leftJoin('tb_formas_pagamento as formaPagtoMaterial', 'formaPagtoMaterial.id_forma_pagamento', 'fk_id_forma_pagto_material')
                ->where('fk_id_matricula', $id_matricula)
                ->orderBy('data_contratacao')
                ->get();
                //dd($contratosExtraCurriculares);

                return view('secretaria.paginas.matriculas.contrato_2021', 
                    compact ('matricula', 'unidadeEnsino', 'contratosExtraCurriculares')
                );
        }else{

            return redirect()->back()->with('atencao', 'Não há modelo de contrato configurado para o ano letivo desta matrícula.');
        }
        
    }

    //Requerimento de matrícula
    public function imprimirReqMatricula($id_matricula)
    {
        $this->authorize('Matrícula Contrato Ver');  
        $matricula = $this->repositorio->where('id_matricula', $id_matricula)->first();
        
        $unidadeEnsino = new UnidadeEnsino;
        $unidadeEnsino = $unidadeEnsino->where('id_unidade_ensino', $matricula->turma->tipoTurma->anoLetivo->fk_id_unidade_ensino)->first();
        
        return view('secretaria.paginas.matriculas.requerimento_matricula', [
            'matricula' => $matricula,        
            'unidadeEnsino' =>$unidadeEnsino,
        ]);
    }

    public function imprimirFichaMatricula($id_aluno)
    {
        $this->authorize('Matrícula Ver');

        $matricula = $this->repositorio->where('fk_id_aluno', $id_aluno)->first();  

        $matriculas = $this->repositorio
                                ->select('tb_matriculas.*', 
                                        'tb_turmas.nome_turma', 
                                        'tb_anos_letivos.ano', 
                                        'tb_turnos.descricao_turno', 
                                        'tb_sub_niveis_ensino.sub_nivel_ensino',
                                        'aluno.nome as nome_aluno', 'aluno.id_pessoa as id_aluno', 'aluno.foto',
                                        'responsavel.nome as nome_responsavel', 'responsavel.id_pessoa as id_responsavel',                                        
                                        'tb_situacoes_matricula.*',
                                        'tb_tipos_turmas.valor_curso') 
                                ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'tb_matriculas.fk_id_aluno')
                                ->join('tb_pessoas as responsavel', 'responsavel.id_pessoa', 'tb_matriculas.fk_id_responsavel')
                                ->join('tb_situacoes_matricula', 'tb_situacoes_matricula.id_situacao_matricula', 'tb_matriculas.fk_id_situacao_matricula')                                
                                ->join('tb_turmas', 'tb_turmas.id_turma', 'tb_matriculas.fk_id_turma')                               
                                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                                ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                                ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')                                 
                                ->where('fk_id_aluno', $id_aluno)->get();

        //dd($matriculas);
        $unidadeEnsino = new UnidadeEnsino;
        $unidadeEnsino = $unidadeEnsino->where('id_unidade_ensino', $matricula->turma->tipoTurma->anoLetivo->fk_id_unidade_ensino)->first();
        
        return view('secretaria.paginas.matriculas.ficha_matricula', [
            'matriculas' => $matriculas,            
            'unidadeEnsino' =>$unidadeEnsino,
        ]);
    }

    /*
    *Lista de todos alunos matriculados em um ano letivo
    *somente para popular combo
    */
    public function getAlunos($id_ano_letivo)
    {
        $alunos['data'] = $this->repositorio->select('id_matricula', 
                                            'nome')
                                ->join('tb_pessoas', 'id_pessoa', 'tb_matriculas.fk_id_aluno')                                                                
                                ->join('tb_turmas', 'tb_turmas.id_turma', 'tb_matriculas.fk_id_turma')                               
                                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )                                
                                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')                                     
                                ->where('id_ano_letivo', $id_ano_letivo)
                                ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                                ->orderBy('nome')
                                ->get();

        echo json_encode($alunos);
        exit;
    }

    /*
    *Lista de todos alunos matriculados em uma turma
    *somente para popular combo
    */
    public function getAlunosTurma($id_turma)
    {
        $alunos['data'] = $this->repositorio->select('id_matricula', 
                                            'nome')
                                ->join('tb_pessoas', 'id_pessoa', 'tb_matriculas.fk_id_aluno') 
                                ->join('tb_turmas', 'tb_turmas.id_turma', 'tb_matriculas.fk_id_turma')                               
                                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )                                
                                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')                                                                
                                ->where('tb_matriculas.fk_id_turma', $id_turma)
                                ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                                ->orderBy('nome')
                                ->get();

        echo json_encode($alunos);
        exit;
    }

    /*
    *Consulta valores de uma matrícula
    *Valores matrícula, curso e material didático    
    */
    public function getValores($id_matricula)
    {
        $valores['data'] = $this->repositorio->select('tb_matriculas.valor_matricula', 
                                            'valor_curso',
                                            'valor_desconto',
                                            'qt_parcelas_curso',
                                            'data_venc_parcela_um',
                                            'tb_matriculas.valor_material_didatico',
                                            'data_pagto_mat_didatico', 'valor_entrada_mat_didatico',
                                            'qt_parcelas_mat_didatico', 'data_venc_parcela_um_mat_didatico',
                                            'obs_matricula',
                                            )   
                                ->join('tb_turmas', 'tb_turmas.id_turma', 'tb_matriculas.fk_id_turma')                               
                                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', 'tb_tipos_turmas.id_tipo_turma' )                             
                                ->where('id_matricula', $id_matricula)   
                                ->get();

        echo json_encode($valores);
        exit;
    }

    /**
     * Geração de login em massa para todos os responsáveis cadastrados
     * utilizado somente pelo desenvolvedor Cleiber
     */
    public function gerarUserTodos()
    {
        $resps = new Pessoa;
        $resps = $resps
            ->select('nome', 'email_1', 'cpf')
            ->where('fk_id_tipo_pessoa', 2)
            /* ->where('id_pessoa', '<=', 25) */
            ->whereNotNull('cpf')
            ->get();

        $userController = new UserController(new User);
        $userUnidadeController = new UserUnidadeEnsinoController(new User, new UnidadeEnsino);

        $unidadesEnsino = array('0' => User::getUnidadeEnsinoSelecionada());
        foreach ($resps as $resp){
            $dadosUser = array('name' => $resp->nome,
                'email' => $resp->cpf,
                'password' => $resp->cpf);
            try{
                $idRespUser = $userController->storeRespUser($dadosUser);
                //dd($idRespUser);

                if ($idRespUser > 0){

                    //vinculando à unidade de ensino                     
                    $userUnidadeController->vincularUnidadesRespUser($unidadesEnsino, $idRespUser);

                    //atribuindo perfil 5 = PAI/RESPONSAVEL
                    $userPerfil = array(                        
                        'fk_id_perfil' => 6,                         
                     );
                    $userUnidadeController->updateRespUser($userPerfil, $idRespUser);                    
                }

            } catch(\Throwable $qe) {
                return redirect()->back()->with('erro', 'Erro ao gerar user.'.$qe);
            }
                
        }
    }

    /**
     * Verifica se a situação foi ativada
     */
    /* public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao_disciplina', $dados))
            return ['situacao_disciplina' => '0'];
        else
             return ['situacao_disciplina' => '1'];            
    } */
}
