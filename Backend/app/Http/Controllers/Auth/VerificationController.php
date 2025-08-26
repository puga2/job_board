<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    //✅ verify Email

    public function verify(Request $request,$id,$hash){
        $user = User::findOrFail($id);



        if(!hash_equals((string)$hash,sha1($user->getEmailForVerification()))){
            return response()->json(['message'=>'Invalid verification Link'],400);
        }
        if($user->hasVerifiedEmail()){
            return response()->json(['message'=>'Email already verified'],400);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['message'=>'Email verified successfully']);
    }

    // resent Verification

    public function resend(Request $request){
        $user = $request->user();

        if($user->hasVerifiedEmail()){
            return response()->json(['message'=>'Email already verified'],400);
        }

        // Resend the verification email
        $user->sendEmailVerificationNotification();

        return response()->json(['message'=>'Verification email resent successfully']);
    }

}
