<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class twoFaVerificationController extends Controller
{

    /**
     * Display the 2fa verification view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->google2fa_enable_status) {
            $google2fa = app('pragmarx.google2fa');
            $google2fa_secret = decrypt($user->google2fa_secret_key);
            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $google2fa_secret
            );
        } else {
            $google2fa_secret = null;
            $QR_Image = null;
        }
        return view('admin.twofa.index', ['QR_Image' => $QR_Image, 'secret' => $google2fa_secret]);
    }

    /**
     * Display the 2fa verification view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('admin.auth.twoFa-verification');
    }

    /**
     * Confirm the 2fa verification Page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return view('admin.auth.twoFa-verification');
    }

    /**
     * Confirm the 2fa verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function verify(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'code' => 'required|numeric'
            ]);
        if ($validated->fails()){
            Toastr::error('Invalid 2FA Code.', 'Message', ["positionClass" => "toast-bottom-right"]);
            return redirect()->back()->with(['error'=> 'Invalid 2FA code!']);
        }
        $user = Admin::findOrfail(Auth::id());
        $google2fa = app('pragmarx.google2fa');
        $google2fa_secret = decrypt($user->google2fa_secret_key);
        if ($google2fa->verifyKey($google2fa_secret, $request->input('code'))){
            session(['2fa_verified'=> true]);
            $user->google2fa_verify_status = 'verified';
            $user->save();
           Toastr::success('Login successful.', 'Message', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('dashboard.index');
        } else{
            Toastr::error('Invalid 2FA Code.', 'Message', ["positionClass" => "toast-bottom-right"]);
            return redirect()->back()->with(['error'=> 'Invalid 2FA code!']);
        }
    }

    /**
     * Change 2fa enable status with google 2fa secret key generate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed boolean
     */
    public function twoFaEnableStatus(Request $request)
    {

        $auth_id = Auth::id();
        $auth_user = Admin::find($auth_id);
        $auth_user->google2fa_enable_status = !$auth_user->google2fa_enable_status;
        $auth_user->save();

        $status = $auth_user->google2fa_enable_status == 1 ? 'enable' :  'disable';
        if ($status === 'enable') {
            $google2fa = app('pragmarx.google2fa');
            $google2fa_secret = $google2fa->generateSecretKey();
            Admin::where('id', $auth_user->id)->update([
                'google2fa_secret_key' => encrypt($google2fa_secret),
                'google2fa_enable_status' => true,
                'google2fa_verify_status' => 'unverified'
            ]);
            session(['2fa_verified' => true]);
            return redirect()->back()->with(['success' => '2FA enabled successfully!']);
        } else {
            Admin::where('id', $auth_user->id)->update([
                'google2fa_secret_key' => null,
                'google2fa_enable_status' => false,
                'google2fa_verify_status' => 'unverified'
            ]);
            return redirect()->back()->with(['success' => '2FA disabled successfully!']);
        }
    }
}
