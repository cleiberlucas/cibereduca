<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTurma;
use App\Models\Secretaria\Turma;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    private $repositorio;
    
    public function __construct(Turma $Turma)
    {
        $this->repositorio = $Turma;

    }

    public function index()
    {
        $turmas = $this->repositorio->with('tipoTurma')->paginate();          
                
        return view('secretaria.paginas.turmas.index', [
                    'turmas' => $turmas,        
        ]);
    }

    public function create()
    {
       // dd(view('secretaria.paginas.turmas.create'));
        return view('secretaria.paginas.turmas.create');
    }

    public function store(StoreUpdateTurma $request )
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
        $Turma = $this->repositorio->where('id_turma', $id)->with('tipoTurma')->first();

        if (!$Turma)
            return redirect()->back();

        return view('secretaria.paginas.turmas.show', [
            'turma' => $Turma
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
        
        return view('secretaria.paginas.turmas.index', [
            'turmas' => $turmas,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {

        $Turma = $this->repositorio->where('id_turma', $id)->first();
        
        if (!$Turma)
            return redirect()->back();
                
        return view('secretaria.paginas.turmas.edit',[
            'turma' => $Turma,
        ]);
    }

    public function update(StoreUpdateTurma $request, $id)
    {
        $Turma = $this->repositorio->where('id_turma', $id)->first();

        if (!$Turma)
            return redirect()->back();
        
        $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit);

        $Turma->where('id_turma', $id)->update($request->except('_token', '_method'));

        return redirect()->route('turmas.index');
    }
}
