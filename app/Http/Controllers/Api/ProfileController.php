<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{


    public function myInfo()
    {
        $user = auth('sanctum')->user();
        
        return successResponseJson(['user' => new UserResource($user)]);
    }


    public function updateProfile(ProfileRequest $request){
        
        $request->validated();

        $user = auth('sanctum')->user();

        $user->name = $request->name;
        $user->email = $request->email;

        if($request->hasFile('image')){
            $path = 'public/organization';
            $file= $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename_with_ext = time().'.'.$extension;
            if($user->image){
                Storage::delete('public/organization/'.$user->photo);
            }
            $request->file('image')->storeAs($path, $filename_with_ext);
            $user->image = $filename_with_ext;
        }

        $user->save();

        return successResponseJson(null, 'Profile updated successfully!');
    }
}
