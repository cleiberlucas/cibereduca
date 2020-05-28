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
        
    protected $fillable = ['fk_id_aluno', 'fk_id_responsavel', 'fk_id_turma', 'data_matricula', 'valor_matricula'];
   
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
