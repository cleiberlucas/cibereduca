<?php

namespace App\Http\Controllers\Pedagogico\Relatorio;

use App\Http\Controllers\Controller;
use App\Models\AnoLetivo;

class DiarioController extends Controller
{
    
    /**
     * Página inicial relatórios diários
     */
    public function diario()
    {
        $anosLetivos = AnoLetivo::where('fk_id_unidade_ensino', '=', session()->get('id_unidade_ensino'))
                                ->orderBy('ano', 'desc')
                                ->get();

       // $turma = Turma::where('')->all();

        return view('pedagogico.paginas.turmas.relatorios.diario', [
            'anosLetivos' => $anosLetivos,
        ]);
    }
}
