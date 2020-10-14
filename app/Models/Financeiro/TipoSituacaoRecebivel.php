<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;

class TipoSituacaoRecebivel extends Model
{
    protected $table = "tb_tipos_situacao_recebivel";
    protected $primaryKey = 'id_situacao_recebivel';
    
    public $timestamps = false;
    
    //protected $attributes = ['situacao_disciplina' => '0'];

    protected $fillable = ['situacao_recebivel'];
   /* 
    public function search($filtro = null)
    {
        $resultado = $this->where('situacao_matricula', 'LIKE', "%{$filtro}%")
                            ->paginate();
        
        return $resultado;
    } */

    /* public function getTiposSituacoesRecebivel()
    {
        return TipoSituacaoRecebivel::select('*')
            ->orderBy('situacao_matricula')
            ->get();
    } */
}
