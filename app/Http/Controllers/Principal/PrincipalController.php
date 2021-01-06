<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\UnidadeEnsino;
use App\Models\UserUnidadeEnsino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrincipalController extends Controller
{   
    public function __construct()
    {
        
    }

    public function index(Request $request)
    {            
        $unidadesEnsino = UnidadeEnsino::where('situacao', '=', '1')
                        ->where('situacao_vinculo', '=', '1')                         
                        ->where('tb_usuarios_unidade_ensino.fk_id_user', '=', Auth::id())        
                        ->join('tb_usuarios_unidade_ensino', 'fk_id_unidade_ensino', 'id_unidade_ensino')                                                            
                        ->get();
                        
        return view('principal.paginas.home.index',[
            'unidadesEnsino' => $unidadesEnsino,
        ]);

    }

    public function defineUnidadePadrao(Request $request)
    {        
        session()->forget('id_unidade_ensino');
        session()->put('id_unidade_ensino', $request['unidadeensino']);

        return redirect()->back();
    }
    
}
