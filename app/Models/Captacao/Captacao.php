<?php

namespace App\Models\Captacao;

use App\Models\AnoLetivo;
use App\Models\UnidadeEnsino;
use Illuminate\Database\Eloquent\Model;

class Captacao extends Model
{
    protected $table = "tb_captacoes";
    protected $primaryKey = 'id_captacao';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_pessoa', 'aluno', 'serie_pretendida', 'data_contato',
        'fk_id_unidade_ensino', 'fk_id_ano_letivo', 'fk_id_tipo_cliente', 'fk_id_motivo_contato', 'fk_id_tipo_negociacao', 'fk_id_tipo_descoberta', 
        'data_agenda', 'hora_agenda',
        'necessita_apoio', 'valor_matricula', 'valor_curso', 'valor_material_didatico', 'valor_bilingue', 'valor_robotica', 
        'data_cadastro', 'fk_id_usuario_captacao', 'observacao'];
   
    public function search($filtro = null)
    {
        $resultado = $this
            ->select('tb_captacoes.*',
                'tb_pessoas.nome',
                'ano',
                'tipo_negociacao')
            ->join('tb_pessoas', 'fk_id_pessoa', 'id_pessoa')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_tipos_negociacao', 'fk_id_tipo_negociacao', 'id_tipo_negociacao')
            ->where('tb_anos_letivos.fk_id_unidade_ensino', session()->get('id_unidade_ensino'))
            ->where('aluno', 'like', "%{$filtro}%")                             
            ->orWhere('nome', 'like', "%{$filtro}%")     
            ->orWhere('fk_id_pessoa', $filtro)       
            ->paginate(25);
        
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
