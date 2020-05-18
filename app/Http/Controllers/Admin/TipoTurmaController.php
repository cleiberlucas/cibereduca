<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTipoTurma;
use App\Models\TipoTurma;
use Illuminate\Http\Request;

class TipoTurmaController extends Controller
{
    private $repositorio;
    
    public function __construct(TipoTurma $tipoTurma)
    {
        $this->repositorio = $tipoTurma;

    }

    public function index()
    {
        $tiposturmas = $this->repositorio->with('anoLetivo')->paginate();          
                
        return view('admin.paginas.tiposturmas.index', [
                    'tiposturmas' => $tiposturmas,        
        ]);
    }

    public function create()
    {
       // dd(view('admin.paginas.tiposturmas.create'));
        return view('admin.paginas.tiposturmas.create');
    }

    public function store(StoreUpdateTipoTurma $request )
    {
        $dados = $request->all();
        $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit);
       // dd($dados);
        $this->repositorio->create($dados);

        return redirect()->route('tiposturmas.index');
    }

    public function show($id)
    {
        $tipoTurma = $this->repositorio->where('id_tipo_turma', $id)->with('anoLetivo')->first();

        if (!$tipoTurma)
            return redirect()->back();

        return view('admin.paginas.tiposturmas.show', [
            'tipoturma' => $tipoTurma
        ]);
    }

    public function destroy($id)
    {
        $tipoTurma = $this->repositorio->where('id_tipo_turma', $id)->first();

        if (!$tipoTurma)
            return redirect()->back();

        $tipoTurma->where('id_tipo_turma', $id)->delete();
        return redirect()->route('tiposturmas.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $tiposturmas = $this->repositorio->search($request->filtro);
        
        return view('admin.paginas.tiposturmas.index', [
            'tiposturmas' => $tiposturmas,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {

        $tipoTurma = $this->repositorio->where('id_tipo_turma', $id)->first();
        
        if (!$tipoTurma)
            return redirect()->back();
                
        return view('admin.paginas.tiposturmas.edit',[
            'tipoturma' => $tipoTurma,
        ]);
    }

    public function update(StoreUpdateTipoTurma $request, $id)
    {
        $tipoTurma = $this->repositorio->where('id_tipo_turma', $id)->first();

        if (!$tipoTurma)
            return redirect()->back();
        
        $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit);

        $tipoTurma->where('id_tipo_turma', $id)->update($request->except('_token', '_method'));

        return redirect()->route('tiposturmas.index');
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
