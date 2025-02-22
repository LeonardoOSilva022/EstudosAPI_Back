<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    //register
//login
//me
//logout


    public function cadastrar(Request $request)
    {
        $userQtd = User::where('email', $request->email)->count();

        if ($userQtd > 0)
            return response('', 409);


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->created_by = 0;
        $user->save();

        return response($user, 201);

    }

    public function login(Request $request)
    {

        if (isset($request->email) && isset($request->password)) {

            $validate = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);


            if ($token = JWTAuth::attempt($validate)) {
                return response()->json(['token' => $token], 200);
            }
        }

        return response('', 401);
    }


    public function me()
    {
        $user = auth()->user();
        return response($user, 200);
    }


    public function logout()
    {
        auth()->logout();
        return response('', 200);
    }
}