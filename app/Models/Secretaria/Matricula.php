<?php

namespace App\Models\Secretaria;

use App\Models\AnoLetivo;
use App\Models\FormaPagamento;
use App\Models\Secretaria\Turma;
use App\Models\SituacaoMatricula;
use App\Models\TipoDescontoCurso;
use App\Models\Turno;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $table = "tb_matriculas";
    protected $primaryKey = 'id_matricula';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_aluno', 'fk_id_responsavel', 'fk_id_turma', 
                            'data_matricula', 'valor_matricula', 'data_limite_desistencia', 'data_vigencia', 'data_pagto_matricula', 'fk_id_forma_pagto_matricula',
                            'valor_desconto', 'fk_id_tipo_desconto_curso', 'qt_parcelas', 'data_venc_parcela_um', 'fk_id_forma_pagto_curso',
                            'valor_material_didatico', 'data_pagto_mat_didatico', 'fk_id_forma_pagto_didatico', 
                            'fk_id_situacao_matricula', 'fk_id_user_cadastro', 'fk_id_user_altera'];
   
    /* public function search($filtro = null)
    {
        $resultado = $this->where('nome_turma', 'like', "%{$filtro}%") 
                            ->paginate();
        
        return $resultado;
    } */

    /**
     * Retorna a turma MatrículaXAluno
     */
    public function turma()
    {       
        return $this->belongsTo(Turma::class, 'fk_id_turma', 'id_turma')->with('turno', 'tipoTurma');
    }

    public function quantMatriculas($idTurma)
    {
        $quant = $this->
                            where(['fk_id_turma' => $idTurma])
                            ->where(['fk_id_situacao_matricula' => '1'])
                            ->count();
        return $quant;
    }

    public function quantVagasDisponiveis($idTurma)
    {
        $turma = new Turma;
        $limiteAlunos =  intval($turma->quantLimiteAlunos($idTurma));
        $qtmatriculas = intval($this->quantMatriculas($idTurma));
        
        return $limiteAlunos - $qtmatriculas;
    }


    /**
     * Retorna Ano da matrículaXAluno
     */
    /*  public function ano()
    {
        return $this->belongsTo(Turma::class, 'fk_id_turma', 'id_turma');
    }  */

    /**
     * Retorna aluno MatrículaXAluno
     */
    public function aluno()
    {       
        return $this->belongsTo(Pessoa::class, 'fk_id_aluno', 'id_pessoa');
    }

    /**
     * Retorna situação MatrículaXAluno
     */
    public function situacaoMatricula()
    {       
        return $this->belongsTo(situacaoMatricula::class, 'fk_id_situacao_matricula', 'id_situacao_matricula');
    }
    

}
