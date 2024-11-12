<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Notifications\CustomResetPassword;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                $user->notify(new CustomResetPassword($token));
            }
        );
    
        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Link zum Zurücksetzen wird an Ihre E-Mail gesendet.'], 200)
            : response()->json(['message' => 'Link zum Zurücksetzen kann nicht gesendet werden.'], 400);
    }
}
