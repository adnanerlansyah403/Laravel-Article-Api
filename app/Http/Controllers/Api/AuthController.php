<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'message' => '',
            'token' => $request->bearerToken(),
            'user' => $user,
        ], 200);
    }

    public function login(Request $request)
    {
        $attributes = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $attributes['email'])->first();

        if(!$user || !Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                'email' => ['You provide a wrong credentials'],
            ]);
        }

        $token = $user->createToken('api-token', ['*'])->plainTextToken;

        return response()->json([
            'message' => 'Login Successful',
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful'
        ], 200);
    }

    public function register(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|min:7|max:255',
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required|min:7|max:255|confirmed',
        ]);

        // Create the user
        $user = User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => Hash::make($attributes['password']),
        ]);

        // Create the profile
        // Profile::create([
        //     'user_id' => $user->id,
        //     'name' => $attributes['name'],
        // ]);

        // Log the user in
        // Auth::login($user);
        event(new Registered($user));

        // Create token for the user
        $token = $user->createToken('API Token')->plainTextToken;

        // Send email verification
        $user->sendEmailVerificationNotification();

        // Return a JSON response with the token
        return response()->json([
            'message' => 'Your account has been created',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function verify(Request $request)
    {
        if (! URL::hasValidSignature($request)) {
            return response()->json([
                'message' => 'Invalid or expired URL signature',
            ], 403);
        }

        $user = User::where('email', $request->query('email'))->first();

        if($request->user()->email != $user->email) {
            return response()->json([
                'message' => 'You provide an invalid email',
            ], 403);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified',
            ], 200);
        }

        $request->user()->markEmailAsVerified();

        return response()->json([
            'message' => 'Successfully verified the email',
        ], 200);
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => hash('sha256', $token),
            'created_at' => Carbon::now()
        ]);

        $resetLink = url('/api/auth/reset-password?token=' . $token . '&email=' . urlencode($request->email));

        Mail::send('auth.emails.forgot_password', ['email' => $request->email, 'token' => $token], function($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return response()->json([
            'message' => 'We have emailed your password reset link!'
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|confirmed|min:7|max:255',
        ]);

        $tokenData = DB::table('password_reset_tokens')
                        ->where('email', $request->email)
                        ->where('token', hash('sha256', $request->token))
                        ->first();

        if (!$tokenData) {
            return response()->json([
                'message' => 'Invalid token or email'
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Your password has been changed!'
        ], 200);
    }

}
