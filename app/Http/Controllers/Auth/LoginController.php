<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use App\Post;
use Auth;
use Hash;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest',['except' => ['logout','userLogout']]);
    }

    public function showLoginForm()
    {
       
        return view('auth.login');
    }

 
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        Session::flash('success', 'Successfully Logged In as '. Auth::guard('web')->user()->fname); 

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard('web')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1], $request->remember);
    }
    
     public function loginUsername()
    {
        return property_exists($this, 'username') ? $this->username : 'email';
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->loginUsername() => trans('auth.failed')];
        // Load user from database
        $user = User::where($this->loginUsername(), $request->{$this->loginUsername()})->first();
        // Check if user was successfully loaded, that the password matches
        // and active is not 1. If so, override the default error message.
        if ($user && Hash::check($request->password, $user->password) && $user->status != 1) {
            Session::flash('danger', 'Your account is not active.');
            $errors = [$this->loginUsername() => 'Your account is not active.'];
        }else{
            Session::flash('danger', 'Wrong username or password');
        }
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($errors, 422);
        }   

        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors($errors);
    }

    public function userLogout()
    {
        Auth::guard('web')->logout();
        Session::flash('success', 'Successfully Logged Out'); 
        return redirect()->route('login');
    }

}
