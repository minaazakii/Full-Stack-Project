<?php

namespace App\Http\Controllers\Api\FrontEnd\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\FrontEnd\Auth\LoginRequest;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        if(!Auth::attempt($request->validated()))
        {
            return response()->json(['message'=>'Unauthorized User Check Email or Password'],401);
        }
        $user = User::where('email',$request->email)->first();
        return response()->json(
            [
                'user'=>$user,
                'token'=> $user->createToken($user->email)->plainTextToken
            ]);
    }
}
