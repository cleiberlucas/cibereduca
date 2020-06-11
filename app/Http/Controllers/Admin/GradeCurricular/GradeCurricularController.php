<?php

namespace App\Http\Controllers\Admin\GradeCurricular;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateGradeCurricular;
use App\Models\GradeCurricular;
use App\Models\Secretaria\Disciplina;
use App\Models\TipoTurma;
use App\User;
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
        $tipoTurma = $this->tipoTurma
                                    ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
                                    ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                                    ->where('id_tipo_turma', $id_tipo_turma)->with('subNivelEnsino')->first();

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
        $tipoTurma = $this->tipoTurma
                            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
                            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                            ->where('id_tipo_turma', $id_tipo_turma)
                            ->first();

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
        $j = 0;//contador p disciplinas selecionadas
        $ch_preenchidas = 0;
        //Sincronizando a escolha de disciplina e carga horária
        for($i = 0; $i < count($request->cargas_horarias); $i++)
        {           
            //verificando se a carga horaria foi preenchida
            if ($request->cargas_horarias[$i]){
                $ch_preenchidas ++;
                //verificando se uma disciplina foi selecionada
                if (isset($request->disciplinas[$j]))
                    //sincroniza disciplina X carga horaria
                    $gradeCurricular[$request->disciplinas[$j]] = ['carga_horaria_anual' => $request->cargas_horarias[$i]];
                
                    //incrementa contador disciplinas
                $j++;
            }            
        }
        //verificando se preencheu a carga horaria para todas as disciplinas selecionadas
        if ($ch_preenchidas != count($request->disciplinas))
            return redirect()
                    ->back()
                    ->with('info', 'Informe a carga horária para a(s) disciplina(s) escolhida(s).');

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
