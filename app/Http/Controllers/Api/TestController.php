<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    function test(Request $request) {
        $user = User::where([
            'provider_id' => $request->provider_id,
        ])->first();

        if($user){
            return successResponseJson([
            'access_token' => $user->createToken('authToken')->plainTextToken,
            'token_type' => 'Bearer',
            'has_name' => $user->name ? true : false,
            'user' => new UserResource($user)],
            'You are logged in.');
        }

        // $result = DB::table('users')->insert([
        //     'provider_id' => $provider_id,
        //     'email' => $response->email,
        //     'image' => $response->picture,
        //     'password' => Hash::make($provider_id)
        // ]);

        // $user = User::where([
        //     'provider_id' => $provider_id,
        // ])->first();

        // if($result){
        //     return successResponseJson([
        //         'access_token' => $user->createToken('authToken')->plainTextToken,
        //         'token_type' => 'Bearer',
        //         'has_name' => $user->name ? true : false,
        //         'user' => new UserResource($user)
        //     ], 'You are logged in.');
        // }else{
        //     return errorResponseJson('Something went wrong',422);
        // }
    }
}
