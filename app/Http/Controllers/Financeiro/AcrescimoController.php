<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Financeiro\Acrescimo;

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

    //Gravando acrescimo de boletos
    public function storeAcrescimosBoleto(Array $acrescimos)
    {        
       // dd($acrescimos);
        $gravou_acrescimo = true;
    
        foreach($acrescimos as $index => $acrescimo){            
            //gravando multa
            $dados = array(
                'fk_id_recebivel' => $acrescimos[$index]['id_recebivel'],
                'fk_id_conta_contabil_acrescimo' => '4', // multa
                'valor_acrescimo' => $acrescimos[$index]['valor_acrescimo_multa'],
                'valor_desconto_acrescimo'  => $acrescimos[$index]['valor_desconto_acrescimo_multa'],
                'valor_total_acrescimo'  => $acrescimos[$index]['valor_total_acrescimo_multa'],                
            );              
            $gravou_acrescimo = $this->repositorio->create($dados);

            //gravando juros
            $dados = array(
                'fk_id_recebivel' => $acrescimos[$index]['id_recebivel'],
                'fk_id_conta_contabil_acrescimo' => '5', // juros
                'valor_acrescimo' => $acrescimos[$index]['valor_acrescimo_juro'],
                'valor_desconto_acrescimo'  => $acrescimos[$index]['valor_desconto_acrescimo_juro'],
                'valor_total_acrescimo'  => $acrescimos[$index]['valor_total_acrescimo_juro'],                
            );              
            $gravou_acrescimo = $this->repositorio->create($dados);
            if (! $gravou_acrescimo->wasRecentlyCreated){
                return false;
                break;
            }    
        }       
        return $gravou_acrescimo;       
    }

    //Remover acréscimo de um recebível
    public function apagarAcrescimo($id_recebivel)
    {                
        $acrescimo = $this->repositorio->where('fk_id_recebivel', $id_recebivel)->first();

       /*  if (!$acrescimo)
            return redirect()->back()->with('error', 'Recebimento não encontrado.');  */          

        try {
            if ($acrescimo)
                $acrescimo->where('fk_id_recebivel', $id_recebivel)->delete();

        } catch (QueryException $qe) {
            return redirect()->back()->with('error', 'Não foi possível excluir o acréscimo. ');            
        }
        //return redirect()->route('tiposturmas.avaliacoes', $avaliacao->fk_id_tipo_turma);
        return true;
    }

}
