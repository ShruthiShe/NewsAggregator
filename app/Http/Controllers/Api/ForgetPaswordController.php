<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgetPaswordController extends Controller
{
    //
    
    public function sendPasswordResetLink(Request $request)
    {
        dd($request->all());
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['status' => true, 'message' => __($status)], 200)
            : response()->json(['status' => false, 'message' => __($status)], 400);
    }
}
