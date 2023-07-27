<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function myInfo()
    {
        $user = auth('sanctum')->user();

        return successResponseJson(['user' => new UserResource($user)]);
    }

    public function updateProfile(ProfileRequest $request, ImageService $image)
    {
        // validate 
        $request->validated();
        $user = auth('sanctum')->user();

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('image')) {
            // get file extension
            $name = time().'.'.$request->file('image')->getClientOriginalExtension();
                
            // compress file using service
            $compressed_image = $image->compress($request->file('image'));

            // upload file using service method 
            $filename = $image->upload($compressed_image, $name, 'organization', $user->image);

            if(!$filename){
                return errorResponseJson('Image upload failed!', 422);
            }
            
            $user->image = $filename;
        }

        $user->save();

        return successResponseJson(new UserResource($user), 'Profile updated successfully!');
    }


    public function changePassword(Request $request)
    {

        $request->validate([
            'old_password' => 'required|different:new_password',
            'new_password' => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        $user = auth('sanctum')->user();

        if (Hash::check($request->old_password, $user->password)) {

            $user->password = $request->new_password;
            $user->save();

            return successResponseJson(new UserResource($user), "Password changed successfully!");
        } else {
            return errorResponseJson('Current password does not match!', 422);
        }
    }
}
