<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Models\TipoTurma;
use App\User;
use Illuminate\Http\Request;

class PedagogicoTipoTurmaController extends Controller
{
    private $repositorio;
    
    public function __construct(TipoTurma $tipoTurma)
    {
        $this->repositorio = $tipoTurma;
    }

    public function index()
    {
        $tiposTurmas = $this->repositorio
                                ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
                                ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                                ->orderBy('tb_anos_letivos.ano', 'desc')
                                ->orderBy('fk_id_sub_nivel_ensino', 'asc')
                                ->orderBy('tipo_turma', 'asc')
                                ->paginate(25); 

        return view('pedagogico.paginas.tiposturmas.index', [
                    'tiposTurmas' => $tiposTurmas,       
        ]);
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $turmas = $this->repositorio->search($request->filtro);
        
        return view('pedagogico.paginas.turmas.index', [
            'turmas' => $turmas,
            'filtros' => $filtros,
        ]);
    }

}
