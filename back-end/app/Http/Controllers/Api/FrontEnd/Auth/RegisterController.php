<?php

namespace App\Http\Controllers\Api\FrontEnd\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FrontEnd\Auth\RegisterRequest;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        return response()->json(
            [
                'user' => $user,
                'token' => $user->createToken($user->email)->plainTextToken
            ]
        );
    }

}
