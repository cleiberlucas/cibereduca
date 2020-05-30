<?php

namespace App\Http\Controllers\Admin\GradeCurricular;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateGradeCurricular;
use App\Models\GradeCurricular;
use App\Models\Secretaria\Disciplina;
use App\Models\TipoTurma;
use Illuminate\Http\Request;

class GradeCurricularController extends Controller
{
    private $tipoTurma, $disciplina;
    
    public function __construct(TipoTurma $tipoTurma, Disciplina $disciplina)
    {
        $this->tipoTurma = $tipoTurma;
        $this->disciplina = $disciplina;
    }

    //Disciplinas de uma turma
    public function disciplinas($id_tipo_turma)
    {
        $tipoTurma = $this->tipoTurma->where('id_tipo_turma', $id_tipo_turma)->with('anoLetivo', 'subNivelEnsino')->first();

        if (!$tipoTurma)
            return redirect()->back();
        
        //$disciplinas = $tipoTurma->disciplinas()->paginate();
        $disciplinas = GradeCurricular::select('id_grade_curricular', 'id_disciplina', 'disciplina', 'carga_horaria_anual')
                                        ->join('tb_tipos_turmas', 'id_tipo_turma', 'fk_id_tipo_turma')
                                        ->join('tb_disciplinas', 'id_disciplina', 'fk_id_disciplina')
                                        ->where('fk_id_tipo_turma', $id_tipo_turma)
                                        ->orderBy('disciplina')
                                        ->paginate(30);
       // dd($disciplinas);       
        return view('admin.paginas.tiposturmas.disciplinas.disciplinas', [
            'tipoTurma' => $tipoTurma,
            'disciplinas' => $disciplinas,
        ]);
    }

    public function disciplinasAdd(Request $request, $id_tipo_turma)
    {
        $tipoTurma = $this->tipoTurma->where('id_tipo_turma', $id_tipo_turma)->first();

        if (!$tipoTurma)
            return redirect()->back();

        $filtros = $request->except('_token');
        $disciplinas = $tipoTurma->disciplinasLivres($request->filtro);         
        
        return view('admin.paginas.tiposturmas.disciplinas.add', [
            'tipoTurma' => $tipoTurma,
            'disciplinas' => $disciplinas,
            'filtros' => $filtros,
        ]);
    }

    public function vincularDisciplinasTurma(Request $request, $id_tipo_turma)
    {
        $tipoTurma = $this->tipoTurma->where('id_tipo_turma', $id_tipo_turma)->first();

        if (!$tipoTurma)
            return redirect()->back();

        if (!$request->disciplinas || count($request->disciplinas) == 0){
            return redirect()
                    ->back()
                    ->with('info', 'Escolha pelo menos uma disciplina.');
        }

        $gradeCurricular = [];
        //Sincronizando a escolha de disciplina e carga horária
        for($i = 0; $i < count($request->disciplinas); $i++)
            $gradeCurricular[$request->disciplinas[$i]] = ['carga_horaria_anual' => $request->cargas_horarias[$i]];

        $tipoTurma->disciplinas()->attach($gradeCurricular);

        return redirect()->route('tiposturmas.disciplinas', $tipoTurma->id_tipo_turma);
    }

    public function removerDisciplinasTurma($id_tipo_turma, $id_disciplina)
    {
        $tipoTurma = $this->tipoTurma->where('id_tipo_turma', $id_tipo_turma)->first();
        $disciplina = $this->disciplina->where('id_disciplina', $id_disciplina)->first();

        if (!$tipoTurma || !$disciplina)
            return redirect()->back();

        $tipoTurma->disciplinas()->detach($disciplina);

        return redirect()->route('tiposturmas.disciplinas', $tipoTurma->id_tipo_turma);
    }
}
