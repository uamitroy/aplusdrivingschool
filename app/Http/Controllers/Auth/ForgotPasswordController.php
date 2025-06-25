<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Session;
use App\User;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $user_check = User::where('email', $request->email)->first();

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        if($user_check){
            if($user_check->status == 1){
                $response = $this->broker()->sendResetLink(
                $request->only('email')
            );

            return $response == Password::RESET_LINK_SENT
                        ? $this->sendResetLinkResponse($response)
                        : $this->sendResetLinkFailedResponse($request, $response);
            }else{

                Session::flash('danger', 'Your account is not activated. Please activate it first.');
                return back()->with('status', 'Your account is not activated. Please activate it first.');
            }
        }
        else{

            Session::flash('danger', 'Sorry you are not our user !!!');
            return back()->with('status', 'Sorry you are not our user !!!');
        }
                     
    }

    protected function sendResetLinkResponse($response)
    {
        Session::flash('success', trans($response)); 
        return back()->with('status', trans($response));
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        Session::flash('danger', trans($response));
        return back()->withErrors(
            ['email' => trans($response)]
        );
    }
}
