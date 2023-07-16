<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

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
        $admin = auth()->user();

        $request->validate([
            'name' => 'required|string|min:4',
            'email' => 'required|email|string',
            'image' => 'image|max:2'
        ]);
        
        $admin->name = $request->name;
        $admin->email = $request->email;

        try {
            if($request->hasFile('image')){
                $path = 'public/admin';
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename_with_ext = time() . '.' . $extension;
                if ($admin->image) {
                    if (Storage::exists('public/admin/' . $admin->photo)) {
                        Storage::delete('public/admin/' . $admin->photo);
                    }
                }
                $request->file('image')->storeAs($path, $filename_with_ext);
                $admin->image = $filename_with_ext;
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
