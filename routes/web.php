<?php

use Illuminate\Support\Facades\Route;

/**
 * Rotas Secretaria
 */
Route::prefix('secretaria')
        ->namespace('Secretaria')
        ->middleware('auth')
        ->group(function(){

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
