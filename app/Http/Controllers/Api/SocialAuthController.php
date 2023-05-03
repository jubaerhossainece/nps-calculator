<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
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
        
        $value = User::class.'::'.($provider);
        $providerUrl = constant($value);
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$token
        ])->get($providerUrl);
        

        if(isset(json_decode($response)->error)){
            return response()->json([
                'mes' => 'Invalid token sent'
            ]);
        }

        $response = Request::create();

        return $this->providerLogin($response, $provider);
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


    public function providerLogin(Request $request, $provider)
    {
        return $request->all();
        $request->validate([
            'name' => 'required|string|min:3',
            'provider_id' => 'required|string',
            'image' => 'required|image'
        ]);

        return $request->all();

        $user = User::where([
            'provider_id' => $request->facebook_id,
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
