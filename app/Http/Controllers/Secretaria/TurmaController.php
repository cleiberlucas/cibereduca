<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTurma;
use App\Models\Secretaria\Turma;
use App\Models\Turno;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    private $repositorio, $turnos;
    
    public function __construct(Turma $Turma)
    {
        $this->repositorio = $Turma;
        $this->turnos = new Turno;
        $this->turnos = $this->turnos->all()->sortBy('descricao_turno');

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
        return view('secretaria.paginas.turmas.create', [
            'turnos' => $this->turnos,
        ]);
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
            'turnos' => $this->turnos,
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
}
