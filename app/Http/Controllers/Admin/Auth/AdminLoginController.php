<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;

use Cache;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests\ValidateSecretRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Admin;


class AdminLoginController extends Controller
{
    
    use AuthenticatesUsers;

    public function __construct()
    {
      $this->middleware('guest:admin',['except' => ['logout']]);
    }

    public function showLoginForm()
    {
      return view('admin.auth.admin-login');
    }

    public function login(Request $request)
    {
      $this->validate($request, [
        'email'   => 'required|email',
        'password' => 'required|min:6'
      ]);

      // Attempt to log the user in
      if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {

        return $this->sendLoginResponse($request);
        
      }

      // if unsuccessful, then redirect back to the login with the form data
      Session::flash('danger', 'Wrong username or password'); 
      return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        $user = Admin::firstOrFail();

        return $this->authenticated($request, $user)
                ?: redirect()->intended($this->redirectPath());
    }


      /**
     * Send the post-authentication response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @return \Illuminate\Http\Response
     */
    private function authenticated(Request $request, $user)
    {
        
        if ($user->google2fa_secret) {
            Auth::guard('admin')->logout();

            $request->session()->put('2fa:admin:id', $user->id);

            return redirect()->route('admin.2fa.validate');
        }

        Session::flash('success', 'Successfully Logged In as '. Auth::guard('admin')->user()->name); 
        // if successful, then redirect to their intended location 
        return redirect()->intended(route('admin.dashboard'));
        //return redirect()->route('admin.dashboard');
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function getValidateToken()
    {
        if (session('2fa:admin:id')) {
            return view('admin.auth.2fa.validate');
        }

         return redirect()->route('admin.login');
    }

     /**
     *
     * @param  App\Http\Requests\ValidateSecretRequest $request
     * @return \Illuminate\Http\Response
     */
    public function postValidateToken(ValidateSecretRequest $request)
    {
        //get user id and create cache key
        $userId = $request->session()->pull('2fa:admin:id');
        $key    = $userId . ':' . $request->totp;

        //use cache to store token to blacklist
        Cache::add($key, true, 4);

        //login and redirect user
        
        Auth::guard('admin')->loginUsingId($userId);

        Session::flash('success', 'Successfully Logged In as '. Auth::guard('admin')->user()->name); 
         return redirect()->intended(route('admin.dashboard'));
    }


     public function logout()
    {
        Auth::guard('admin')->logout();
        Session::flash('success', 'Successfully Logged Out'); 
        return redirect()->route('admin.login');
    }
}
