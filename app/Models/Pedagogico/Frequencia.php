<?php

namespace App\Models\Pedagogico;

use App\Models\Secretaria\Disciplina;
use App\Models\Secretaria\Matricula;
use Illuminate\Database\Eloquent\Model;

class Frequencia extends Model
{
    protected $table = "tb_frequencias";
    protected $primaryKey = 'id_frequencia';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_matricula', 'fk_id_disciplina', 'data_aula', 'fk_id_tipo_frequencia', 'fk_id_user', 'data_cadastro'];
   
    public function getTurmaFrequencia($id_turma)
    {
        $this->select('tb_matriculas.id_matricula',
                'tb_pessoas.nome')
                ->Join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
                ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
                ->where('tb_matriculas.fk_id_turma', '=', '$id_turma')
                ->orderBy('tb_pessoas.nome');
    }

    public function matricula()
    {
         $this->belongsTo(Matricula::class, 'fk_id_matricula', 'id_matricula');
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'fk_id_disciplina', 'id_disciplina');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'fk_id_user', 'id');
    }
   
}
