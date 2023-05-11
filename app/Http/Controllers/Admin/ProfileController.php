<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            'image' => 'image'
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if($request->hasFile('image')){
            $path = 'public/admin';
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename_with_ext = time() . '.' . $extension;
            if ($admin->image) {
                Storage::delete('public/admin/' . $admin->photo);
            }
            $request->file('image')->storeAs($path, $filename_with_ext);
            $admin->image = $filename_with_ext;
        }
        $admin->save();

        return redirect()->route('admin.profile.edit')->withMessage('Profile updated successfully.');
    }


    public function changePassword(){
        return view('admin.profile.change-password');
    }

    public function test()
    {
        $admin = auth()->user();
        return view('admin.profile.test', compact('admin'));

    }
}
