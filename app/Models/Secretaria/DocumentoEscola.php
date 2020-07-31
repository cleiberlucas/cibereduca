<?php

namespace App\Models\Secretaria;

use App\Models\TipoDocumentoEscola;
use Illuminate\Database\Eloquent\Model;

class DocumentoEscola extends Model
{
    protected $table = "tb_documentos_escola";
    protected $primaryKey = 'id_documento_escola';
    
    public $timestamps = false;
    
    //protected $attributes = ['situacao_disciplina' => '0'];

    protected $fillable = ['fk_id_matricula', 'fk_id_tipo_documento', 'corpo_documento', 'codigo_validacao', 'situacao_documento', 'fk_id_user'];
   
    /* public function search($filtro = null)
    {
        $resultado = $this->where('disciplina', 'LIKE', "%{$filtro}%")
                            ->orWhere('sigla_disciplina', 'LIKE', "%{$filtro}%")
                            ->paginate();
        
        return $resultado;
    } */

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'fk_id_matricula', 'id_matricula');
    }

    public function tipoDocumentoEscola()
    {
        return $this->belongsTo(TipoDocumentoEscola::class, 'fk_id_tipo_documento', 'id_tipo_documento');
    }
    
    public function usuario()
    {
        return $this->belongsTo(User::class, 'fk_id_user', 'id');
    }
}
