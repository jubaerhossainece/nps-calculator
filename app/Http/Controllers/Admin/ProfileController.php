<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ProfileController extends Controller
{
    public function show(){
        $admin = auth()->user();
        return view('admin.profile.show', compact('admin'));
    }


    public function edit()
    {
        $admin = auth()->user();
        return view('admin.profile.edit', compact('admin'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:4',
            'email' => 'required|email',
            'image' => 'image'
        ]);
        
        $admin = auth()->user();
        $admin->name = $request->name;
        $admin->email = $request->email;

        try {
            if($request->hasFile('image')){
                $file = $request->file('image');
                $path = 'public/admin/';
                $filename_with_ext = time() . '.' . $file->getClientOriginalExtension();

                // delete previous image if exists
                if ($admin->image) {
                    if (Storage::exists($path . $admin->image)) {
                        Storage::delete($path . $admin->image);
                    }
                }

                $image = Image::make($file);

                // compress if image width is greater than 400px
                if($image->width() > 400){
                    $image = $image->resize(400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }

                $admin->image = $filename_with_ext;            
                Storage::disk('public')->put('admin/' . $filename_with_ext, $image->stream());
            }
            
            $admin->save();
        } catch (\Exception $e) {
            return errorResponseJson($e, 422);
        }

        Toastr::success('Profile updated successfully.', 'Message', ["positionClass" => "toast-bottom-right"]);
        return redirect()->route('admin.profile.show')->withMessage('Profile updated successfully.');
    }


    public function editPassword(){
        return view('admin.profile.edit-password');
    }


    public function updatePassword(Request $request)
    {

        $validated = $request->validate([
            'old_password' => 'required|different:new_password|current_password',
            'new_password' => ['required', 'string', Password::min(8)->numbers()->symbols()->mixedCase()],
            'new_password_confirmation' => 'required'
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $result = $user->save();

        if($result){
            return redirect()->back()->withMessage('Password updated successfully.');
        }

        return redirect()->back()->withError('Something went wrong.');

    }


    public function test()
    {
        $admin = auth()->user();
        return view('admin.profile.test', compact('admin'));

    }
}
