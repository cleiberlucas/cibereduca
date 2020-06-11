<?php

namespace App\Providers;

use App\Models\Permissao;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $permissoes = Permissao::all();

        foreach ($permissoes as $permissao) {
            Gate::define($permissao->permissao, function(User $user) use ($permissao) {
                return $user->getVerificaPermissaoUsuario($permissao->permissao);
            });
        }

        Gate::define('owner', function(User $user, $object) {
            return $user->id === $object->user_id;
        });

        Gate::before(function (User $user) {
            if ($user->isAdmin()) {
                return true;
            }
        });
    }
}
