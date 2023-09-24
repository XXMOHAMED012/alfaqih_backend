<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorAuthenticationMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthenticationController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            $code = rand(100000, 999999);
            $admin->two_factor_authentication_code = $code;
            $admin->save();

            Mail::to($admin->email)->send(new TwoFactorAuthenticationMail($code));

            return response(['message' => 'Code sent to email']);
        }

        return response(['message' => 'Invalid credentials'], 401);
    }

    public function verify(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'code' => 'required|string',
        ]);

        $user = Admin::where('email', $request->email)
                    ->where('two_factor_authentication_code', $request->code)
                    ->firstOrFail();

        $token = $user->createToken('AdminToken')->plainTextToken;

        $user->two_factor_authentication_code = null;
        $user->save();

        return response([
            'user' => $user,
            'token' => $token,
            'message' => 'Login Successful'
        ]);
    }
}
