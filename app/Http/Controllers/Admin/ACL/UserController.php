<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUser;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $repositorio;
    
    public function __construct(User $user)
    {
        $this->repositorio = $user;

    }

    public function index()
    {
        $users = $this->repositorio->paginate();
        
        return view('admin.paginas.users.index', [
                    'users' => $users,
        ]);
    }

    public function create()
    {
       // dd(view('admin.paginas.users.create'));
        return view('admin.paginas.users.create');
    }

    public function store(StoreUpdateUser $request )
    {
        $dados = $request->all();
        $dados['password'] = bcrypt($dados['password']); // encrypt password
        $this->repositorio->create($dados);

        return redirect()->route('users.index');
    }

    public function show($id)
    {
        $user = $this->repositorio->where('id', $id)->first();

        if (!$user)
            return redirect()->back();

        return view('admin.paginas.users.show', [
            'user' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = $this->repositorio->where('id', $id)->first();

        if (!$user)
            return redirect()->back();

        $user->where('id', $id)->delete();
        return redirect()->route('users.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $users = $this->repositorio->search($request->filtro);
        
        return view('admin.paginas.users.index', [
            'users' => $users,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $user = $this->repositorio->where('id', $id)->first();
        
        if (!$user)
            return redirect()->back();
                
        return view('admin.paginas.users.edit',[
            'user' => $user,
        ]);
    }

    public function update(StoreUpdateUser $request, $id)
    {
        if (!$user = $this->repositorio->find($id))
            return redirect(-back());

        $dados = $request->only(['name', 'email']);

        if ($request->password){
            $dados['password'] = bcrypt($request->password);
        }

        $user->update($dados);
        
        /* $user->where('id', $id)->update($request->except('_token', '_method')); */

        return redirect()->route('users.index');
    }
}
