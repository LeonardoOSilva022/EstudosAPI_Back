<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('login', [AuthController::class, 'login']); // Rota de login
Route::post('cadastrar', [AuthController::class, 'cadastrar']); // Rota de cadastro

Route::middleware('auth:api')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']); // Rota de logout
    Route::get('me', [AuthController::class, 'me']); // Rota para obter o usuário autenticado

    Route::prefix('Gerenciar')->group(function(){
        Route::post('/criar', [UsuariosController::class, 'criar']); // criar um novo dado
        Route::get('/consultar/{id}', [UsuariosController::class, 'consultar']);//consultar 1 individualmente
        Route::get('/listar', [UsuariosController::class, 'listar']); // listar todos
        Route::delete('/deletar/{id}', [UsuariosController::class, 'deletar']); // deletar um unico dado
        Route::put('/editar/{id}', [UsuariosController::class, 'editar']); // editar um unico dado
        Route::patch('/editarParcial/{id}', [UsuariosController::class, 'editarParcial']); // editar um unico dado
    });
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();});