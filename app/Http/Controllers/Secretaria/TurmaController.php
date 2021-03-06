<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTurma;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Turma;
use App\Models\TipoTurma;
use App\Models\Turno;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TurmaController extends Controller
{
    private $repositorio, $turnos, $tiposTurmas;

    public function __construct(Turma $turma)
    {
        $this->repositorio = $turma;
        $this->turnos = new Turno;
        $this->turnos = $this->turnos->all()->sortBy('descricao_turno');
        $this->tiposTurmas = new TipoTurma;
        //$this->tiposTurmas = $this->tiposTurmas->all()->sortBy('tipo_turma');

    }

    public function index()
    {

        $turmas = Turma::select('*')
            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma')
            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->where('tb_anos_letivos.fk_id_unidade_ensino', '=', session()->get('id_unidade_ensino'))
            ->orderBy('tb_anos_letivos.ano', 'desc')
            ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino', 'asc')
            ->orderBy('nome_turma', 'asc')
            ->orderBy('tb_turnos.descricao_turno', 'asc')
            ->paginate(25);

        $matriculas = new Matricula;

        $totalMatriculas = $matriculas->totalMatriculasAno(date('Y'));

        $quantVagas = [];
        foreach ($turmas as $turma) {
            $quantVagas[$turma->id_turma] = $matriculas->quantVagasDisponiveis($turma->id_turma);
        }
        return view('secretaria.paginas.turmas.index', [
            'turmas' => $turmas,
            'quantVagas' => $quantVagas,
            'totalMatriculas' => $totalMatriculas,
        ]);
    }

    public function create()
    {
        $this->authorize('Turma Cadastrar');
        $tiposTurma = TipoTurma::select('*')
            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->where('tb_anos_letivos.situacao', '=', '1')
            ->orderBy('tb_anos_letivos.ano', 'desc')
            ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino', 'asc')
            ->orderBy('tb_tipos_turmas.tipo_turma', 'asc')
            ->get();

        return view('secretaria.paginas.turmas.create', [
            'turnos' => $this->turnos,
            'turmas' => $this->repositorio,
            'tiposTurmas' => $tiposTurma,
        ]);
    }

    public function store(StoreUpdateTurma $request)
    {
        $dados = $request->all();
        $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit);
        // dd($dados);
        $this->repositorio->create($dados);

        return redirect()->route('turmas.index');
    }

    public function show($id)
    {
        $this->authorize('Turma Ver');
        
        $turma = $this->repositorio
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->where('id_turma', $id)
            ->with('tipoTurma', 'usuario')
            ->first();
        //dd($turma);

        if (!$turma)
            return redirect()->back();

        return view('secretaria.paginas.turmas.show', [
            'turma' => $turma
        ]);
    }

    public function destroy($id)
    {
        $Turma = $this->repositorio->where('id_turma', $id)->first();

        if (!$Turma)
            return redirect()->back();

        $Turma->where('id_turma', $id)->delete();
        return redirect()->route('turmas.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $turmas = $this->repositorio->search($request->filtro);
        
        $matriculas = new Matricula;

       /*  dd($request->filtro);
        $totalMatriculas = 0;
        if (is_int($request->filtro)) */
            $totalMatriculas = $matriculas->totalMatriculasAno($request->filtro);

        $quantVagas = [];

        foreach ($turmas as $turma)
            $quantVagas[$turma->id_turma] = $matriculas->quantVagasDisponiveis($turma->id_turma);

        return view('secretaria.paginas.turmas.index', [
            'turmas' => $turmas,
            'filtros' => $filtros,
            'quantVagas' => $quantVagas,
            'totalMatriculas' => $totalMatriculas,
        ]);
    }

    public function edit($id)
    {
        $this->authorize('Turma Alterar');
        $turma = $this->repositorio
            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma')
            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->where('id_turma', $id)
            ->with('tipoTurma')
            ->first();

        if (!$turma)
            return redirect()->back();

        $tiposTurmas = TipoTurma::select('*')
            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->where('tb_anos_letivos.situacao', '=', '1')
            ->orderBy('tb_anos_letivos.ano', 'desc')
            ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino', 'asc')
            ->orderBy('tb_tipos_turmas.tipo_turma', 'asc')
            ->get();

        return view('secretaria.paginas.turmas.edit', [
            'turma'         => $turma,
            'turnos'        => $this->turnos,
            'tiposTurmas'   => $tiposTurmas,
        ]);
    }

    public function update(StoreUpdateTurma $request, $id)
    {
        $turma = $this->repositorio->where('id_turma', $id)->first();

        if (!$turma)
            return redirect()->back();

        $sit = $this->verificarSituacao($request->all());

        $request->merge($sit);

        $turma->where('id_turma', $id)->update($request->except('_token', '_method'));

        return redirect()->route('turmas.index');
    }

    /**
     * Verifica se a situação foi ativada
     */
    public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao_turma', $dados))
            return ['situacao_turma' => '0'];
        else
            return ['situacao_turma' => '1'];
    }

    /**
     * Turmas de um ano letivo
     * Popular COMBOBOX
     * @param int id_ano_letivo
     * @return array turmas 
     */
    public function getTurmas($anoLetivo = 0)
    {
        $turma['data'] = DB::table('tb_turmas')->select('id_turma', DB::raw("CONCAT(nome_turma,' - ',sub_nivel_ensino,' - ', descricao_turno) AS nomeTurma"))
            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma')
            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')
            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
            ->where('tb_tipos_turmas.fk_id_ano_letivo', $anoLetivo)
            ->orderBy('sub_nivel_ensino')
            ->orderBy('nome_turma', 'asc')
            ->orderBy('descricao_turno')
            ->get();

        echo json_encode($turma);
        exit;
    }
}
