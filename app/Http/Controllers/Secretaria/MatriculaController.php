<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateMatricula;
use App\Models\FormaPagamento;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Pessoa;
use App\Models\Secretaria\Turma;
use App\Models\Secretaria\TipoDescontoCurso;
use App\Models\SituacaoMatricula;
use Illuminate\Http\Request;

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
       // dd($request->all());
        $matriculas = $this->repositorio->where('fk_id_turma', $request->segment(2))
                                        ->paginate();
        //dd($matriculas);
         $turma = Turma::select('tb_turmas.nome_turma', 'tb_turmas.id_turma', 'tb_anos_letivos.ano', 'tb_turnos.descricao_turno', 'tb_sub_niveis_ensino.sub_nivel_ensino')                            
                            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')         
                            ->where('tb_turmas.id_turma', '=', $request->segment(2))                         
                            ->first();  
        
                
        return view('secretaria.paginas.matriculas.index', [
                    'matriculas' => $matriculas,
                    'turma'      => $turma,
        ]);
    }

    public function create(Request $request)
    {
        $alunos = Pessoa::select('id_pessoa', 'nome')
                            ->where('fk_id_tipo_pessoa', '=', '1')
                            ->where('situacao_pessoa', 1)
                            ->orderBy('nome')
                            ->get();
        
        $responsaveis = Pessoa::select('id_pessoa', 'nome')
                                ->where('fk_id_tipo_pessoa', '=', '2')
                                ->where('situacao_pessoa', 1)
                                ->orderBy('nome')
                                ->get();

        $turma = Turma::select('tb_turmas.nome_turma', 'tb_anos_letivos.ano', 'tb_turnos.descricao_turno', 'tb_sub_niveis_ensino.sub_nivel_ensino')                            
                        ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                        ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                        ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                        ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')         
                        ->where('tb_turmas.id_turma', '=', $request->segment(4))                         
                        ->first(); 

        $formasPagto = FormaPagamento::select('*')->orderBy('forma_pagamento')->get();

        $tiposDesconto = TipoDescontoCurso::select('*')->orderBy('tipo_desconto_curso')->get();

        $situacoesMatricula = SituacaoMatricula::select('*')->orderBy('situacao_matricula')->get();

       // dd(view('secretaria.paginas.matriculas.create'));
        return view('secretaria.paginas.matriculas.create', [
                    'alunos'       => $alunos,
                    'responsaveis' => $responsaveis,
                    'turma'        => $turma,
                    'formasPagto' => $formasPagto,
                    'tiposDesconto' => $tiposDesconto,
                    'situacoesMatricula' => $situacoesMatricula,
        ]);
    }

    public function store(StoreUpdateMatricula $request )
    {
        $dados = $request->all();
        $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit);
       // dd($dados);
        $this->repositorio->create($dados);

        return redirect()->route('matriculas.index');
    }

    public function show($id)
    {
        //$matricula = $this->repositorio->where('id_matricula', $id)->first();

        $matricula = $this->repositorio
                            ->select('tb_matriculas.*', 'aluno.nome as nome_aluno', 'respons.nome as nome_responsavel', 'respons.telefone_1',
                                    'tb_turmas.nome_turma', 'tb_anos_letivos.ano', 'tb_turnos.descricao_turno')
                            ->join('tb_pessoas as aluno', 'aluno.id_pessoa', '=', 'fk_id_aluno')
                            ->join('tb_pessoas as respons', 'respons.id_pessoa', '=', 'fk_id_responsavel')
                            ->join('tb_turmas', 'tb_turmas.id_turma', '=', 'tb_matriculas.fk_id_turma')
                            ->join('tb_tipos_turmas', 'tb_tipos_turmas.id_tipo_turma', '=', 'tb_turmas.fk_id_tipo_turma')
                            ->join('tb_anos_letivos', 'tb_anos_letivos.id_ano_letivo', '=', 'tb_tipos_turmas.fk_id_ano_letivo')
                            ->join('tb_turnos', 'tb_turnos.id_turno', '=', 'tb_turmas.fk_id_turno')                            
                            ->where('id_matricula', $id)
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
        $alunos = Pessoa::select('id_pessoa', 'nome')
        ->where('fk_id_tipo_pessoa', '=', '1')
        ->where('situacao_pessoa', 1)
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

        $matricula = $this->repositorio
                                ->select('tb_matriculas.*', 'tb_turmas.nome_turma', 'tb_anos_letivos.ano', 'tb_turnos.descricao_turno', 'tb_sub_niveis_ensino.sub_nivel_ensino',
                                        'aluno.nome as nome_aluno', 'aluno.id_pessoa as id_aluno', 'responsavel.nome as nome_responsavel', 'responsavel.id_pessoa as id_responsavel',
                                        'tb_situacoes_matricula.*') 
                                ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'tb_matriculas.fk_id_aluno')
                                ->join('tb_pessoas as responsavel', 'responsavel.id_pessoa', 'tb_matriculas.fk_id_responsavel')
                                ->join('tb_situacoes_matricula', 'tb_situacoes_matricula.id_situacao_matricula', 'tb_matriculas.fk_id_situacao_matricula')
                                ->join('tb_turmas', 'tb_turmas.id_turma', 'tb_matriculas.fk_id_turma')                               
                                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                                ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                                ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')         
                                ->where('id_matricula', $id)->first();
        
        if (!$matricula)
            return redirect()->back();
                
        return view('secretaria.paginas.matriculas.edit',[
            'matricula' => $matricula,
            'alunos'    => $alunos,
            'responsaveis' => $responsaveis,
            'formasPagto'   => $formasPagto,
            'tiposDesconto' => $tiposDesconto,
            'situacoesMatricula' =>$situacoesMatricula,
        ]);
    }

    public function update(StoreUpdateMatricula $request, $id)
    {
        $matricula = $this->repositorio->where('id_matricula', $id)->first();

        if (!$matricula)
            return redirect()->back();
        
        $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit);

        $matricula->where('id_matricula', $id)->update($request->except('_token', '_method'));

        return redirect()->route('matriculas.index');
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
