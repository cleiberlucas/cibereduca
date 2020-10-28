<?php

namespace App\Models\Captacao;

use App\Models\AnoLetivo;
use Illuminate\Database\Eloquent\Model;

class Captacao extends Model
{
    protected $table = "tb_captacoes";
    protected $primaryKey = 'id_captacao';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_pessoa', 'aluno', 'serie_pretendida', 'data_contato',
        'fk_id_ano_letivo', 'fk_id_tipo_cliente', 'fk_id_motivo_contato', 'fk_id_tipo_negociacao', 'fk_id_tipo_descoberta', 
        'data_cadastro', 'fk_id_usuario_captacao', 'observacao'];
   
    public function search($filtro = null)
    {
        $resultado = $this
            ->where('motivo_contato', 'like', "%{$filtro}%")                             
            ->paginate();
        
        return $resultado;
    }

    /**
     * Retorna ano Letivo
     */
    public function anoLetivo()
    {
        return $this->belongsTo(AnoLetivo::class, 'fk_id_ano_letivo', 'id_ano_letivo');
    }

    /**
     * Retorna Tipo Cliente
     */
    public function tipoCliente()
    {
        return $this->belongsTo(TipoCliente::class, 'fk_id_tipo_cliente', 'id_tipo_cliente');
    }

    /**
     * Retorna Motivo Contato
     */
    public function motivoContato()
    {
        return $this->belongsTo(MotivoContato::class, 'fk_id_motivo_contato', 'id_motivo_contato');
    }

    
    /**
     * Retorna Tipo Negociação
     */
    public function tipoNegociacao()
    {
        return $this->belongsTo(TipoNegociacao::class, 'fk_id_tipo_negociacao', 'id_tipo_negociacao');
    }

    
    /**
     * Retorna Motivo Contato
     */
    public function tipoDescoberta()
    {
        return $this->belongsTo(TipoDescoberta::class, 'fk_id_tipo_descoberta', 'id_tipo_descoberta');
    }
}
