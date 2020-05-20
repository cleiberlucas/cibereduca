<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPagamento extends Model
{
    protected $table = "tb_formas_pagamento";
    protected $primaryKey = 'id_forma_pagamento';
    
    public $timestamps = false;
        
    protected $fillable = ['forma_pagamento'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('forma_pagamento', 'like', "%{$filtro}%") 
                            ->paginate();
        
        return $resultado;
    }
}
