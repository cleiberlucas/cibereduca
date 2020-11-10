<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTipoAtividadeExtraCurricular;
use App\Models\AnoLetivo;
use App\Models\Secretaria\TipoAtividadeExtraCurricular;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExtraCurricularController extends Controller
{
    private $repositorio;

    public function __construct(TipoAtividadeExtraCurricular $extraCurricular)
    {
        $this->repositorio = $extraCurricular;        
    }

    public function index()
    {
        $this->authorize('Tipo Atividade ExtraCurricular Ver');

        $extraCurriculares = $this->repositorio
            ->select('*')            
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')            
            ->orderBy('ano', 'desc')
            ->orderBy('tipo_atividade_extracurricular')
            ->paginate();
        
        return view('secretaria.paginas.extracurriculares.index', [
            'extraCurriculares' => $extraCurriculares,            
        ]);
    }

    public function create()
    {
        $this->authorize('Tipo Atividade ExtraCurricular Cadastrar');
        $anosLetivos = AnoLetivo::select('*')            
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->where('tb_anos_letivos.situacao', '=', '1')
            ->orderBy('tb_anos_letivos.ano', 'desc')            
            ->get();

        return view('secretaria.paginas.extracurriculares.create', 
            compact('anosLetivos')            
        );
    }

    public function store(StoreUpdateTipoAtividadeExtraCurricular $request)
    {
        $this->authorize('Tipo Atividade ExtraCurricular Cadastrar');
        $dados = $request->all();
        $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit);
        // dd($dados);
        $this->repositorio->create($dados);

        return redirect()->route('extracurriculares.index')->with('sucesso', 'Atividade Extracurricular gravada com sucesso.');
    }

    public function show($id)
    {
        $this->authorize('Tipo Atividade ExtraCurricular Ver');
        $extraCurricular = $this->repositorio            
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->join('users', 'fk_id_usuario', 'id')
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->where('id_tipo_atividade_extracurricular', $id)            
            ->first();
        //dd($extraCurricular);

        if (!$extraCurricular)
            return redirect()->back()->with('atencao', 'Atividade extracurricular não encontrada.');

        return view('secretaria.paginas.extracurriculares.show', [
            'extraCurricular' => $extraCurricular
        ]);
    }

    public function destroy($id)
    {
        $this->authorize('Tipo Atividade ExtraCurricular Remover');
        $extraCurricular = $this->repositorio->where('id_tipo_atividade_extracurricular', $id)->first();

        if (!$extraCurricular)
            return redirect()->back()->with('erro', 'Não foi possível remover a atividade pois está vinculada a alguma matrícula.');

        $extraCurricular->where('id_tipo_atividade_extracurricular', $id)->delete();

        return $this->index()->with('sucesso', 'A atividade foi excluída com sucesso.');
    }
/* 
    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $extraCurriculares = $this->repositorio->search($request->filtro);

        $matriculas = new Matricula;
        $quantVagas = [];

        foreach ($extraCurriculares as $extraCurricular)
            $quantVagas[$extraCurricular->id_turma] = $matriculas->quantVagasDisponiveis($extraCurricular->id_turma);

        return view('secretaria.paginas.turmas.index', [
            'extraCurriculares' => $extraCurriculares,
            'filtros' => $filtros,
            'quantVagas' => $quantVagas,
        ]);
    } */

    public function edit($id)
    {
        $this->authorize('Tipo Atividade Extracurricular Alterar');
        $extraCurricular = $this->repositorio            
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->where('id_tipo_atividade_extracurricular', $id)            
            ->first();

        if (!$extraCurricular)
            return redirect()->back()->with('Atividade ExtraCurricular não encontrada.');

            $anosLetivos = AnoLetivo::select('*')            
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->where('tb_anos_letivos.situacao', '=', '1')
            ->orderBy('tb_anos_letivos.ano', 'desc')            
            ->get();

        return view('secretaria.paginas.extracurriculares.edit', 
            compact('anosLetivos', 'extraCurricular')    
        );
    }

    public function update(StoreUpdateTipoAtividadeExtraCurricular $request, $id)
    {
        $this->authorize('Tipo Atividade ExtraCurricular Alterar');
        $extraCurricular = $this->repositorio->where('id_tipo_atividade_extracurricular', $id)->first();

        if (!$extraCurricular)
            return redirect()->back()->with('atencao', 'Atividade Extracurricular não encontrada.');

        $sit = $this->verificarSituacao($request->all());

        $request->merge($sit);

        $extraCurricular->where('id_tipo_atividade_extracurricular', $id)->update($request->except('_token', '_method'));

        return redirect()->route('extracurriculares.index')->with("sucesso", 'Atividade Extracurricular alterada com sucesso.');
    }

    /**
     * Verifica se a situação foi ativada
     */
    public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao_atividade', $dados))
            return ['situacao_atividade' => '0'];
        else
            return ['situacao_atividade' => '1'];
    }

}
