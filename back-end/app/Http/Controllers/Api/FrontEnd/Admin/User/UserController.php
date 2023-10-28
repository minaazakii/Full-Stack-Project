<?php

namespace App\Http\Controllers\Api\FrontEnd\Admin\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\FrontEnd\User\UserResource;
use App\Http\Requests\api\FrontEnd\User\StoreUserRequest;
use App\Http\Requests\api\FrontEnd\User\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'users' => UserResource::collection(User::with(['permissions', 'roles'])->get()),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        User::create($request->validated());
        return response()->json(['message' => 'User Created Successfully']);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return response()->json(['message' => 'User Updated Successfully']);
    }

    public function show($id)
    {
        $user = User::with(['permissions', 'roles'])->findOrFail($id);
        return response()->json(['user' => new UserResource($user)]);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User Deleted Successfully']);
    }
}
