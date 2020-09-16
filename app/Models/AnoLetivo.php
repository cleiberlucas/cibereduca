<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnoLetivo extends Model
{
    protected $table = "tb_anos_letivos";
    protected $primaryKey = 'id_ano_letivo';

    /* protected $attributes = array(
        'fk_id_unidade_ensino' => session()->get('id_unidade_ensino'),
    ); */
    
    public $timestamps = false;
        
    protected $fillable = ['ano', 'fk_id_unidade_ensino', 'media_minima_aprovacao', 'fk_id_user', 'situacao'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('ano', '=', "{$filtro}")                            
                            ->paginate();
        
        return $resultado;
    }

    /**
     * Anos letivos abertos, vinculados a uma unidade de ensino
     */
    public function anosLetivosAbertos($id_unidade_ensino)
    {
        $resultado = $this->where('situacao', '=', '1')
                            ->where('fk_id_unidade_ensino', '=', $id_unidade_ensino)
                            ->get();
        return $resultado;
    }

    /**
    * Retorna a média de aprovação de um ano letivo
    * @param int idanoletivo
    * @return double media aprovacao
    */
    public function getMediaAprovacao($id_ano_letivo)
    {
        $mediaAprovacao = $this
            ->select('media_minima_aprovacao')
            ->where('id_ano_letivo', $id_ano_letivo)->first();
        
        return $mediaAprovacao;
    }

    public function unidadeEnsino()
    {      
        return $this->belongsTo(UnidadeEnsino::class, 'fk_id_unidade_ensino', 'id_unidade_ensino');
    }
}
