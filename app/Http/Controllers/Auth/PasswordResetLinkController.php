<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        //Check the admin is exist or not in our system
        //if not exits then show an error
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            Toastr::error('Email not found.', 'Message', ["positionClass" => "toast-bottom-right"]);
            return  back()->withInput($request->only('email'));
        }

        $sent = $status == Password::RESET_LINK_SENT;

        if ($sent) {
            Toastr::success('A reset link sent to your email.', 'Message', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('login')->with('status', __($status));
        }

        Toastr::error('Something wrong.', 'Message', ["positionClass" => "toast-bottom-right"]);
        return  back()->withInput($request->only('email'))
            ->withErrors(['status' => __($status)]);
    }
}
