<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;


class UsuariosController extends Controller
{
    public function criar(Request $request)
    {   
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $usuarioQtd = User::where('email', $request->email)->count();
        if ($usuarioQtd > 0) {
            return response()->json(['message' => 'E-mail já cadastrado!'], 409);
        }

        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->created_by = auth()->id();
        $usuario->save();

        return response($usuario, 201);
    }


    public function consultar($id)
    {
        $usuario = User::where('id', $id)->first()->makeHidden(['password']);

        if ($usuario == null)
            return response('', 404);


        return response($usuario, 200);
    }

    public function listar()
    {
        $usuario = User::select('id', 'name', 'email')->get();

        return response($usuario, 200);
    }

    public function deletar($id)
    {
        $usuario = User::where('id', $id)->first();

        if ($usuario == null)
            return response('', 404);

        $usuario->deleted_by = auth()->id();
        $usuario->save();

        $usuario->delete();

        return response('', 200);
    }

    public function editar(Request $request, $id)
    {
        // dd($id, $request->all());

        $usuario = User::where('id', $id)->first();
        $usuario->email = $request->email;
        $usuario->name = $request->name;
        if (isset($request->password) && $request->password != '') {
            $usuario->password = bcrypt($request->password);
        }
        $usuario->updated_by = auth()->id();
        $usuario->save();


        return response($usuario, 200);
    }

    public function editarParcial(Request $request, $id)
    {
        // dd($id, $request->all());

        $usuario = User::where('id', $id)->first();
        if (isset($request->email))
            $usuario->email = $request->email;

        if (isset($request->name))
            $usuario->name = $request->name;

        if (isset($request->password))
            $usuario->password = bcrypt($request->password);

        $usuario->updated_by = auth()->id();
        $usuario->save();


        return response($usuario, 200);
    }
}
