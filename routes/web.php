<?php

use Illuminate\Support\Facades\Route;

/**
 * Rotas Secretaria
 */
Route::prefix('secretaria')
        ->namespace('Secretaria')
        ->middleware('auth')
        ->group(function(){

        /*
        *Rotas Pessoas
        */
        Route::get('pessoas/create/aluno', 'PessoaController@create')->name('pessoas.create.aluno');
        Route::get('pessoas/create/responsavel', 'PessoaController@create')->name('pessoas.create.responsavel');
        Route::put('pessoas/{id_pessoa}', 'PessoaController@update')->name('pessoas.update');
        Route::get('pessoas/{id_pessoa}/edit', 'PessoaController@edit')->name('pessoas.edit');
        Route::any('pessoas/search', 'PessoaController@search')->name('pessoas.search');
        Route::delete('pessoas/{id_pessoa}', 'PessoaController@destroy')->name('pessoas.destroy');
        Route::get('pessoas/{id_pessoa}', 'PessoaController@show')->name('pessoas.show');
        Route::post('pessoas', 'PessoaController@store')->name('pessoas.store');
        Route::any('{id_tipo_pessoa}/pessoas', 'PessoaController@index')->name('pessoas.index');

        /**
         * Rotas disciplinas
         */
        Route::any('disciplinas/search', 'DisciplinaController@search')->name('disciplinas.search');
        Route::resource('disciplinas', 'DisciplinaController');

        Route::get('/', 'DisciplinaController@index')->name('secretaria.index');

        });

/**
 * Rotas Admin
 */
Route::prefix('admin')
        ->namespace('Admin')
        ->middleware('auth')
        ->group(function(){

             /**
             * Rotas permissões
             */
            Route::any('users/search', 'ACL\UserController@search')->name('users.search');
            Route::resource('users', 'ACL\UserController');
           
           
            /**
             * Rotas Perfis X Permissões
             */
            route::get('perfis/{id}/permissao/{id_permissao}/remove', 'ACL\PerfilPermissaoController@removerPermissoesPerfil')->name('perfis.permissoes.remover');            
            route::post('perfis/{id}/permissoes', 'ACL\PerfilPermissaoController@vincularPermissoesPerfil')->name('perfis.permissoes.vincular');            
            route::any('perfis/{id}/permissoes/add', 'ACL\PerfilPermissaoController@permissoesAdd')->name('perfis.permissoes.add');
            route::get('perfis/{id}/permissoes', 'ACL\PerfilPermissaoController@permissoes')->name('perfis.permissoes');

             /**
             * Rotas permissões
             */
            Route::any('permissoes/search', 'ACL\PermissaoController@search')->name('permissoes.search');
            Route::resource('permissoes', 'ACL\PermissaoController');
           
            /**
             * Rotas perfis
             */
            Route::any('perfis/search', 'ACL\PerfilController@search')->name('perfis.search');
            Route::resource('perfis', 'ACL\PerfilController');
           
            /*
            *Rotas Unidades de ensino
             */
            Route::get('UnidadesEnsino/create', 'UnidadeEnsinoController@create')->name('unidadesensino.create');
            Route::put('UnidadesEnsino/{id_unidade_ensino}', 'UnidadeEnsinoController@update')->name('unidadesensino.update');
            Route::get('UnidadesEnsino/{id_unidade_ensino}/edit', 'UnidadeEnsinoController@edit')->name('unidadesensino.edit');
            Route::any('UnidadesEnsino/search', 'UnidadeEnsinoController@search')->name('unidadesensino.search');
            Route::delete('UnidadesEnsino/{id_unidade_ensino}', 'UnidadeEnsinoController@destroy')->name('unidadesensino.destroy');
            Route::get('UnidadesEnsino/{id_unidade_ensino}', 'UnidadeEnsinoController@show')->name('unidadesensino.show');
            Route::post('UnidadesEnsino', 'UnidadeEnsinoController@store')->name('unidadesensino.store');
            Route::get('UnidadesEnsino', 'UnidadeEnsinoController@index')->name('unidadesensino.index');
            
            Route::get('/', 'UnidadeEnsinoController@index')->name('admin.index');

        });

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(); 
//desabilita link para auto registro de usuário
//Auth::routes(['register' =>false]);
