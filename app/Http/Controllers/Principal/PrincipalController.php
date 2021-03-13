<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Secretaria\Pessoa;
use App\Models\UnidadeEnsino;
use App\User;
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

        $perfil = new User;
        $perfil = $perfil->getPerfilUsuario(Auth::id());            
        
        //dd($perfil);
        /* Portal do aluno
        Se for responsável ou aluno  
        6=responsável
        7=aluno
        */
        if (isset($perfil) and ($perfil->fk_id_perfil == 6 or $perfil->fk_id_perfil == 7)){
            $pessoa = new Pessoa;
            $pessoa = $pessoa
                ->select('nome')
                ->where('fk_id_user', Auth::id())
                ->first();

           /*  if (!$pessoa)
                return view()->with('atencao', 'O seu login não foi liberado. Favor entrar em contato com a secretaria do Colégio.');
 */
            return view('principal.paginas.home.portal_aluno',
                compact('unidadesEnsino', 'pessoa')
            );
        }
        else                
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
