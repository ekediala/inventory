<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordRequest;
use App\Notifications\PasswordSuccess;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;

class PasswordRequestController extends Controller
{
    /**
     * Create a password request token and send to the user
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // make sure user is registered
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => "We can't find a user with that e-mail address.",
            ], 404);
        }

        // create token and send to user
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60),
            ]
        );

        if ($user && $passwordReset) {
            $user->notify(
                new PasswordRequest($passwordReset->token)
            );
        }

        return response()->json([
            'message' => 'We have e-mailed your password reset link!',
        ]);
    }

    /**
     * Verify token from user
     * @param String $token
     * @return \Illuminate\Http\Response
    */
     public function find($token)
    {
        // Ok, user did request password change, check token validity
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }
        return response()->json($passwordReset);
    }

     /**
     * Reset password
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
            'token' => 'required|string',
        ]);

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email],
        ])->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => 'This password reset token is invalid.',
            ], 404);
        }

        $user = User::where('email', $passwordReset->email)->first();
        if (!$user) {
            return response()->json([
                'message' => "We can't find a user with that  e-mail address.",
            ], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordSuccess($passwordReset));
        return response()->json(['status' => 'Password changed successfully.']);
    }
}
