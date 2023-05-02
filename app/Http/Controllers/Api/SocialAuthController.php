<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SocialAuthController extends Controller
{
    public function redirectToProvider(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);
        
        $token = $request->token;
        $provider = $request->provider; 

        $providerUrl = User::$provider;
        
        return $provider_user = Http::withHeaders([
            'Authorization' => 'Bearer '.$token
        ])->get($providerUrl);



        
    }

    // /**
    //  * Obtain the user information from Facebook.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function handleProviderCallback($provider)
    // {
    //     $getInfo = Socialite::driver($provider)->stateless()->user()->getId();
    //     return response()->json([
    //         'data' => $getInfo
    //     ]);
    //     $checkUser = User::where('email', $getInfo->email)
    //                 ->where('provider_id', null)
    //                 ->first();
    //     if ($checkUser) {
    //         session()->flash('login_error','Email already taken.');
    //         return redirect()->to('/login');
    //         //return view('auth.login');
    //     }
    //     $user = $this->createUser($getInfo,$provider); 
    //     auth()->login($user);
        
    //     $url = session('url.intended', '/');
    //     return redirect()->to($url);
    // }


    public function facebookLogin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'email|string',
            'facebook_id' => 'required|string',
            'image' => 'required|image'
        ]);

        $user = User::where([
            'facebook_id' => $request->facebook_id,
        ]);

        if($user){
            return successResponseJson(new UserResource($user), 'You are logged in.');
        }

        DB::table('users')->insert([
            'facebook_id' => $request->facebook_id,
            'name' => $request->name,
            'image' => $request->image
        ]);
    }
}
