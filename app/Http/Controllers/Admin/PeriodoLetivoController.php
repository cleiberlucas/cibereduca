<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdatePeriodoLetivo;
use App\Models\PeriodoLetivo;
use Illuminate\Http\Request;

class PeriodoLetivoController extends Controller
{
    private $repositorio;
    
    public function __construct(PeriodoLetivo $periodoLetivo)
    {
        $this->repositorio = $periodoLetivo;

    }

    public function index()
    {
        $periodosLetivos = $this->repositorio->with('anoLetivo')->paginate();          
                
        return view('admin.paginas.periodosletivos.index', [
                    'periodosletivos' => $periodosLetivos,        
        ]);
    }

    public function create()
    {
       // dd(view('admin.paginas.periodosletivos.create'));
        return view('admin.paginas.periodosletivos.create');
    }

    public function store(StoreUpdatePeriodoLetivo $request )
    {
        $dados = $request->all();
        $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit);
       // dd($dados);
        $this->repositorio->create($dados);

        return redirect()->route('periodosletivos.index');
    }

    public function show($id)
    {
        $periodoLetivo = $this->repositorio->where('id_periodo_letivo', $id)->with('anoLetivo')->first();

        if (!$periodoLetivo)
            return redirect()->back();

        return view('admin.paginas.periodosletivos.show', [
            'periodoletivo' => $periodoLetivo
        ]);
    }

    public function destroy($id)
    {
        $periodoLetivo = $this->repositorio->where('id_periodo_letivo', $id)->first();

        if (!$periodoLetivo)
            return redirect()->back();

        $periodoLetivo->where('id_periodo_letivo', $id)->delete();
        return redirect()->route('periodosletivos.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $periodosLetivos = $this->repositorio->search($request->filtro);
        
        return view('admin.paginas.periodosletivos.index', [
            'periodosletivos' => $periodosLetivos,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {

        $periodoLetivo = $this->repositorio->where('id_periodo_letivo', $id)->first();
        
        if (!$periodoLetivo)
            return redirect()->back();
                
        return view('admin.paginas.periodosletivos.edit',[
            'periodoletivo' => $periodoLetivo,
        ]);
    }

    public function update(StoreUpdatePeriodoLetivo $request, $id)
    {
        $periodoLetivo = $this->repositorio->where('id_periodo_letivo', $id)->first();

        if (!$periodoLetivo)
            return redirect()->back();
        
        $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit);

        $periodoLetivo->where('id_periodo_letivo', $id)->update($request->except('_token', '_method'));

        return redirect()->route('periodosletivos.index');
    }

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
