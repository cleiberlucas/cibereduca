<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrincipalController extends Controller
{   
    public function __construct()
    {
        
    }

    public function index(Request $request)
    {            
        return view('principal.paginas.home.index');
    }
}
