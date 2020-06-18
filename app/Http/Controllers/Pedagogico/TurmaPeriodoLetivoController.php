<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTurmaPeriodoLetivo;
use App\Models\Pedagogico\TurmaPeriodoLetivo;
use App\Models\PeriodoLetivo;
use App\Models\Secretaria\Turma;
use Illuminate\Http\Request;
/* use App\User; */

class TurmaPeriodoLetivoController extends Controller
{
    protected $turma, $periodoLetivo;
    
    public function __construct(Turma $turma, PeriodoLetivo $periodoLetivo)
    {
        $this->turma = $turma;
        $this->periodosLetivos = $periodoLetivo;
    }

    //Períodos letivos de uma turma
    public function periodosLetivos($id)
    {
        $turma = $this->turma->where('id_turma', $id)->first();

        if (!$turma)
            return redirect()->back();
        
        $periodosLetivos = $this->periodosLetivos::select('id_turma_periodo_letivo', 'tb_turmas_periodos_letivos.situacao as situacao_turma_periodo_letivo' ,
                                                'tb_periodos_letivos.id_periodo_letivo', 
                                                'tb_periodos_letivos.periodo_letivo',
                                                'tb_turmas.id_turma', 'tb_turmas.nome_turma',
                                                'tb_turmas_periodos_letivos.fk_id_turma',
                                                )  
                                        ->join('tb_turmas_periodos_letivos', 'tb_turmas_periodos_letivos.fk_id_periodo_letivo', 'tb_periodos_letivos.id_periodo_letivo')                                        
                                        ->leftJoin('tb_turmas', 'fk_id_turma', 'id_turma')
                                        ->where('tb_turmas_periodos_letivos.fk_id_turma', $id)
                                        ->orderBy('periodo_letivo')
                                        ->get();

        $periodosLetivosLivres = $turma->periodosLetivosLivres();
                
        return view('pedagogico.paginas.turmas.periodosletivos', [
            'turma' => $turma,
            'periodosLetivos' => $periodosLetivos,
            'periodosLetivosLivres' => $periodosLetivosLivres,        
        ]);
    }

    public function periodosLetivosAdd(Request $request, $id)
    {
        $turma = $this->turma->where('id_turma', $id)->first();

        if (!$turma)
            return redirect()->back();

        $filtros = $request->except('_token');
        $periodosLetivos = $turma->periodosLivres($request->filtro);         
        
        return view('pedagogico.paginas.turmas.periodosletivos.add', [
            'turma' => $turma,
            'periodosLetivos' => $periodosLetivos,
            'filtros' => $filtros,
        ]);
    }

    public function vincularPeriodosTurma(Request $request, $id)
    {
        $turma = $this->turma->where('id_turma', $id)->first();

        if (!$turma)
            return redirect()->back();

        if (!$request->periodosLetivos || count($request->periodosLetivos) == 0){
            return redirect()
                    ->back()
                    ->with('info', 'Escolha um período letivo.');
        }
        
        $turma->periodosLetivos()->attach($request->periodosLetivos);

        return redirect()->route('turmas.periodosletivos', $turma->id_turma);
    }

    public function update(StoreUpdateTurmaPeriodoLetivo $request, $id)
    {
        $turmaPeriodoLetivo = TurmaPeriodoLetivo::where('id_turma_periodo_letivo', $id)->first();

        if (!$turmaPeriodoLetivo)
            return redirect()->back();
        
        $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit);

       // dd($turmaPeriodoLetivo);
        $turmaPeriodoLetivo->where('id_turma_periodo_letivo', $id)->update($request->except('_token', '_method'));

        return redirect()->route('turmas.periodosletivos', $turmaPeriodoLetivo->fk_id_turma);
    }

   /*  public function removerUnidadesUser($id, $id_unidade_ensino)
    {
        $turma = $this->turma->where('id', $id)->first();
        $periodoLetivo = $this->periodosLetivos->where('id_unidade_ensino', $id_unidade_ensino)->first();

        if (!$turma || !$periodoLetivo)
            return redirect()->back();

        $turma->unidadesEnsino()->detach($periodoLetivo);

        return redirect()->route('users.unidadesensino', $turma->id);
    } */

    /**
     * Verifica se a situação foi ativada
     */
    public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao', $dados))
            return ['situacao' => '0'];
        else
             return ['situacao' => '1'];            
    }

}
