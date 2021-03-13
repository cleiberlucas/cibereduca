<div class="container-fluid">
    <div class="card border-success">
        <div class="row ">
            <div class="col-sm-2 col-xs-2 ml-3">
                <a href="/" class="">
                <img width="50%" height="" src="/vendor/adminlte/dist/img/logo.png" alt="">
                </a>
            </div>
            <div class="col-sm-5 col-xs-2  " align="center"> 
                <h3>Portal do Aluno</h3>
                <!-- <h3>Rendimento do(a) Aluno(a)</h3> -->
            </div>
            <div class="col-sm-4 col-xs-2" align="right">
                <a href="/">Home</a> / 
                <a href="javascript:void(0)" onClick="history.go(-1); return false;">Voltar</a>
                <br>
                <br>
                <?php
                    use App\User;
                    use Illuminate\Support\Facades\Auth;

                    if (Auth::id()){
                        $user = new User;                        
                        echo $user->getNomeUsuario(Auth::id())->name;                        
                    }
                    else
                        //echo 'nome';
                        echo '<font color=red>Login incompleto. Favor entrar em contato com a secretaria da escola.</font>';                
                ?>
                <br>
               <!--  <a href="users.editsenha"> <i class="fas fa-fw fa-lock"></i>Alterar Senha</a> 
                <br>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-fw fa-power-off"></i>Sair</a>
                <form id="logout-form" action="logout" method="POST" style="display: none;">
                    <?=@csrf?>
                </form> -->
            </div>
        </div>            
        <!-- <div class="card-footer bg-transparent border-success"> 
            <div class="col-sm-10 col-xs-2" align="center">
                <h4>Matrículas</h4>
            </div>
        </div> -->
    </div>    
</div>