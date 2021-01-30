<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Financeiro\Retorno;

class RetornoController extends Controller
{    
    private $repositorio;
        
    public function __construct(Retorno $retorno)
    {
        $this->repositorio = $retorno;        
    }

    public function index()
    {
        return view('financeiro.paginas.retornos.index',            
        );
    }
}