<?php

namespace App\Http\Controllers\Api\FrontEnd\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json([
            'roles'=>Role::all(),
        ]);
    }
}
