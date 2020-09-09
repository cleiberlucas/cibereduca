<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $repositorio;
    
    public function __construct(User $user)
    {
        $this->repositorio = $user;

    }

    public function index() 
    {
        $users = $this->repositorio->orderBy('email')->paginate();
        
        return view('admin.paginas.users.index', [
                    'users' => $users,
        ]);
    }

    public function create()
    {
        $this->authorize('Usuário Cadastrar');              
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
        $this->authorize('Usuário Ver');       
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
        $this->authorize('Usuário Alterar');       
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
                
        if ($id == Auth::id())
            return redirect()->back()->with("sucesso", "Dados alterados com sucesso.");

        else
            return redirect()->route('users.index');
    }

    /**
     * Edição de senha do usuário logado
     */
    public function editSenha()
    {        
        $user = $this->repositorio->where('id', Auth::id())->first();

        return view('admin.paginas.users.editsenha',[
            'user' => $user,
        ]);
    }

    /**
     * Grava atualização de senha do usuário logado */    
    public function updateSenha(Request $request)
    {
        //dd($request);
        $user = $this->repositorio->find(Auth::id());
        if (!$user)
            return redirect(-back());

        /* Verificando se informou a senha atual corretamente */
        $senhaAtual = bcrypt($request->senhaAtual);
        dd($senhaAtual);
        
        if ($senhaAtual != $user->password)
            return redirect()->back()->with('erro', 'A senha atual está incorreta.');

        if ($request->password != $request->password_2)
            return redirect()->back()->with('erro', 'Informe a nova senha e a confirmação iguais.');

        //$dados = $request->only(['password']);

        if ($request->password){
            $dados['password'] = bcrypt($request->password);
        }

        $user->update($dados);
        
        /* $user->where('id', $id)->update($request->except('_token', '_method')); */

        return redirect()->route('users.index');
    }
}
