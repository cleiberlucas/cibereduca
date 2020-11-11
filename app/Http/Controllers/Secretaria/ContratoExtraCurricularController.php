<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateContratoAtividadeExtraCurricular;
use App\Models\AnoLetivo;
use App\Models\FormaPagamento;
use App\Models\Secretaria\ContratoAtividadeExtraCurricular;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\TipoAtividadeExtraCurricular;
use App\User;

class ContratoExtraCurricularController extends Controller
{
    private $repositorio;

    public function __construct(ContratoAtividadeExtraCurricular $contratoExtraCurricular)
    {
        $this->repositorio = $contratoExtraCurricular;        
    }

    public function index()
    {
        $this->authorize('Matrícula Ver');

        $contratosExtraCurriculares = $this->repositorio
            ->select('*')            
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')            
            ->orderBy('ano', 'desc')
            ->orderBy('tipo_atividade_extracurricular')
            ->paginate();
        
        return view('secretaria.paginas.contratos_extracurriculares.index', [
            'contratosExtraCurriculares' => $contratosExtraCurriculares,            
        ]);
    }

    public function create($fk_id_matricula)
    {
        $this->authorize('Matrícula Cadastrar');

        $anoLetivoAtividade = Matricula::
            select('id_ano_letivo', 'fk_id_aluno')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->where('id_matricula', $fk_id_matricula)
            ->first();

        //dd($anoLetivoAtividade);

        $atividadesExtraCurriculares = TipoAtividadeExtraCurricular::                       
            where('fk_id_ano_letivo', $anoLetivoAtividade->id_ano_letivo)
            ->where('situacao_atividade', 1)            
            ->orderBy('tipo_atividade_extracurricular')
            ->get();
        //dd($atividadesExtraCurriculares);
        $formasPagto = FormaPagamento::
            select('*')
            ->orderBy('forma_pagamento')
            ->get();

        return view('secretaria.paginas.contratos_extracurriculares.create', 
            compact('formasPagto', 'fk_id_matricula', 'atividadesExtraCurriculares', 'anoLetivoAtividade')            
        );
    }

    public function store(StoreUpdateContratoAtividadeExtraCurricular $request)
    {
        $this->authorize('Matrícula Cadastrar');
        $dados = $request->all();
       
        $this->repositorio->create($dados);

        return redirect()->route('matriculas.pasta', $request->fk_id_aluno)->with('sucesso', 'Atividade Extracurricular gravada com sucesso.');
    }

   /*  public function show($id)
    {
        $this->authorize('Matrícula Ver');
        $contratoExtraCurricular = $this->repositorio            
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->join('users', 'fk_id_usuario', 'id')
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->where('id_contrato_atividade_extracurricular', $id)            
            ->first();
        //dd($contratoExtraCurricular);

        if (!$contratoExtraCurricular)
            return redirect()->back()->with('atencao', 'Atividade extracurricular não encontrada.');

        return view('secretaria.paginas.contratos_extracurriculares.show', [
            'contratoExtraCurricular' => $contratoExtraCurricular
        ]);
    } */
/* 
    public function destroy($id)
    {
        $this->authorize('Matrícula Remover');
        $contratoExtraCurricular = $this->repositorio->where('id_contrato_atividade_extracurricular', $id)->first();

        if (!$contratoExtraCurricular)
            return redirect()->back()->with('erro', 'Não foi possível remover a atividade pois está vinculada a alguma matrícula.');

        $contratoExtraCurricular->where('id_contrato_atividade_extracurricular', $id)->delete();

        return $this->index()->with('sucesso', 'A atividade foi excluída com sucesso.');
    } */
/* 
    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $contratosExtraCurriculares = $this->repositorio->search($request->filtro);

        $matriculas = new Matricula;
        $quantVagas = [];

        foreach ($contratosExtraCurriculares as $contratoExtraCurricular)
            $quantVagas[$contratoExtraCurricular->id_turma] = $matriculas->quantVagasDisponiveis($contratoExtraCurricular->id_turma);

        return view('secretaria.paginas.turmas.index', [
            'contratosExtraCurriculares' => $contratosExtraCurriculares,
            'filtros' => $filtros,
            'quantVagas' => $quantVagas,
        ]);
    } */

    public function edit($id)
    {
        $this->authorize('Matrícula Alterar');

        $contratoExtraCurricular = $this->repositorio    
            ->select('tb_contratos_atividades_extracurriculares.*', 'fk_id_aluno')
            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')                            
            ->where('id_contrato_atividade_extracurricular', $id)            
            ->first();

        $fk_id_matricula = $contratoExtraCurricular->fk_id_matricula;

        if (!$contratoExtraCurricular)
            return redirect()->back()->with('Contrato Atividade ExtraCurricular não encontrada.');

        $anoLetivoAtividade = Matricula::
            select('id_ano_letivo')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->where('id_matricula', $contratoExtraCurricular->fk_id_matricula)
            ->first();

        //dd($anoLetivoAtividade);

        $atividadesExtraCurriculares = TipoAtividadeExtraCurricular::                       
            where('fk_id_ano_letivo', $anoLetivoAtividade->id_ano_letivo)
            ->where('situacao_atividade', 1)            
            ->orderBy('tipo_atividade_extracurricular')
            ->get();
        //dd($atividadesExtraCurriculares);
        $formasPagto = FormaPagamento::            
            orderBy('forma_pagamento')
            ->get();

        return view('secretaria.paginas.contratos_extracurriculares.edit', 
            compact('atividadesExtraCurriculares', 'contratoExtraCurricular', 'formasPagto', 'fk_id_matricula')    
        );
    }

    public function update(StoreUpdateContratoAtividadeExtraCurricular $request, $id)
    {
        $this->authorize('Matrícula Alterar');
        $contratoExtraCurricular = $this->repositorio->where('id_contrato_atividade_extracurricular', $id)->first();

        if (!$contratoExtraCurricular)
            return redirect()->back()->with('atencao', 'Contrato Atividade Extracurricular não encontrado.');
        
        $contratoExtraCurricular->where('id_contrato_atividade_extracurricular', $id)->update($request->except('_token', '_method', 'fk_id_matricula', 'fk_id_aluno'));

        return redirect()->route('matriculas.pasta', $request->fk_id_aluno)->with("sucesso", 'Contrato Atividade Extracurricular alterado com sucesso.');
    }

}
