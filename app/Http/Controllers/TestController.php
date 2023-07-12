<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test($id) {
        $user = User::find($id);
        return successResponseJson(new UserResource($user));
        return view('welcome');
    }
}
