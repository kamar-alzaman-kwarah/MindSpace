<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationController extends Controller
{
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['status'=>200,'message' => 'Already Verified'],Response::HTTP_OK);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['status'=>200,'message' => 'verification-link-sent'],Response::HTTP_OK);
    }

    public function verify(EmailVerificationRequest $request)
    {
        if($request->user()->hasVerifiedEmail()){
            return response()->json(['status'=>200,'message' => 'Already Verified'],Response::HTTP_OK);
        }

        if($request->user()->markEmailAsVerified()){
            event(new Verified($request->user()));
        }

        return response()->json(['status'=>200,'message' => 'successfully Verified'],Response::HTTP_OK);
    }
}
