<?php

namespace App\Http\Controllers\Api\FrontEnd\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

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
}
