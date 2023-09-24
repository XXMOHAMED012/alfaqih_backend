<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorAuthenticationMail;
use App\Models\EarlyUser;
use App\Models\User;
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

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();            
            $token = $user->createToken('authToken')->plainTextToken;

            return response([
                'user' => $user,
                'token' => $token,
                'message' => 'Login Successful'
            ]);
        }

        return response(['message' => 'Invalid credentials'], 401);
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string',
            'number' => 'required|string|unique:users,number',
            'national_id' => 'required|string|unique:users,national_id',
        ]);

        $checkIfEarlyUserExist = EarlyUser::where('email', $request->email)
            ->orWhere('number', $request->number)
            ->orWhere('national_id', $request->national_id)
            ->first();

        if ($checkIfEarlyUserExist) {
            $checkIfEarlyUserExist->delete();
        }

        $code = rand(10000, 99999);

        $request->merge([
            'password' => Hash::make($request->password),
            'code' => $code,
        ]);

        EarlyUser::create($request->all());

        $email = $request->email;

        Mail::send('emails.completeRegisteration', ['code' => $code], function ($message) use ($email) {
            $message->to($email);
            $message->subject('Alfaqih Cars Registeration Completion');
        });

        return response(['message' => 'Code Sent to Email']);
    }

    public function verify(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'code' => 'required|string',
        ]);

        $earlyUser = EarlyUser::where('email', $request->email)
            ->where('code', $request->code)
            ->firstOrFail();

        $user = User::create([
            'name' => $earlyUser->name,
            'email' => $earlyUser->email,
            'password' => $earlyUser->password,
            'number' => $earlyUser->number,
            'national_id' => $earlyUser->national_id,
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        $earlyUser->delete();

        return response([
            'token' => $token,
            'user' => $user
        ]);
    }
}
