<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Financeiro\Acrescimo;
use App\User;

class AcrescimoController extends Controller
{
    private $repositorio;
        
    public function __construct(Acrescimo $acrescimo)
    {
        $this->repositorio = $acrescimo;        
    }

    //Gravando acrescimo
    public function store(Array $acrescimos)
    {
        $this->authorize('Recebimento Cadastrar');
        //dd($acrescimos);
        $gravou_acrescimo = true;
        if (!empty($acrescimos['valor_acrescimo'])){
            foreach($acrescimos['valor_acrescimo'] as $index => $acrescimo){            
                if ($acrescimos['valor_acrescimo'][$index] > 0){
                    $dados = array(
                        'fk_id_recebivel' => $acrescimos['fk_id_recebivel'],
                        'fk_id_conta_contabil_acrescimo' => $acrescimos['fk_id_conta_contabil_acrescimo'][$index],
                        'valor_acrescimo' => $acrescimos['valor_acrescimo'][$index],
                        'valor_desconto_acrescimo'  => $acrescimos['valor_desconto_acrescimo'][$index],
                        'valor_total_acrescimo'  => $acrescimos['valor_total_acrescimo'][$index],                
                    );         
                    $gravou_acrescimo = $this->repositorio->create($dados);
                    if (! $gravou_acrescimo->wasRecentlyCreated){
                        return false;
                        break;
                    }
                }
            }
        }
        return $gravou_acrescimo;       
    }

}
