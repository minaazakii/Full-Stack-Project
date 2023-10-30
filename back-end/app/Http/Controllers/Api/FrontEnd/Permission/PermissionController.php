<?php

namespace App\Http\Controllers\Api\FrontEnd\Permission;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\api\FrontEnd\Permission\AssignPermissionRequest;

class PermissionController extends Controller
{
    public function index()
    {
        return response()->json([
            'permissions' => Permission::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'display' => 'required'
        ]);
        $permission = Permission::findOrFail($id);
        $permission->update(['display' => $request->display]);
        return response()->json(['message' => 'Permission Updated Successfully']);
    }

    public function assignPermission(AssignPermissionRequest $request, $userId)
    {
        $user = User::findOrFail($userId);
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $user->givePermissionTo($permissions);
        return response()->json(['message' => 'Permission Assigned Successfully']);
    }
}
