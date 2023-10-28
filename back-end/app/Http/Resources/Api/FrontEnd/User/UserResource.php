<?php

namespace App\Http\Resources\Api\FrontEnd\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'permissions'=>$this->getAllPermissions(),
            'roles'=>$this->roles,
            'created_at'=>$this->created_at->format('F j, Y, g:i a')
        ];
    }
}
