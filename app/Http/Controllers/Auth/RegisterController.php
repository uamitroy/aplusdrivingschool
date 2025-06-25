<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Session;
use Mail;
use App\Mail\verifyEmail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

     public function showRegistrationForm()
    {
       return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
          
           $age = date_diff(date_create($data['dob']), date_create('today'))->y;
            if($age>=18){
                return Validator::make($data, [
                'fname' => 'required|string|regex:/^[\pL\s\-]+$/u',
                'lname' => 'required|string|regex:/^[\pL\s\-]+$/u',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|same:password_confirmation',
                'dob' => 'required',
                'phone' => 'required|min:8|max:13',
                'address' => 'required|string'
                ]);  
            }else{
                return Validator::make($data, [
                'fname' => 'required|string|regex:/^[\pL\s\-]+$/u',
                'lname' => 'required|string|regex:/^[\pL\s\-]+$/u',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|same:password_confirmation',
                'dob' => 'required',
                'phone' => 'required|min:8|max:13',
                'address' => 'required|string',
                'guardian_name'=>'required',
                'guardian_phone'=>'required|min:8|max:13',
                'guardian_address'=>'required',
                ]);
            }
        
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \Laravel_5.4\User
     */
    protected function create(array $data)
    {
        
        $user = User::create([
            'fname' => $data['fname'],
            'lname' => $data['lname'],
            'email' => $data['email'],
            'dob' => date("Y-m-d", strtotime(str_replace('-', '/', $data['dob']))),
            'phone' => $data['phone'],
            'address' => $data['address'],
            'guardian_name' => $data['guardian_name'],
            'guardian_phone' => $data['guardian_phone'],
            'guardian_address' => $data['guardian_address'],
            'password' => bcrypt($data['password']),
            'status' => 1,
            'verifyToken' => Str::random(40)
        ]);

        //$thisUser = User::findOrFail($user->id);
        //$this->sendEmail($thisUser);
        return $user;
    }

     public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);    

        //Session::flash('success', 'Email verification link has sent to your email.Please check your Junk Folder as well'); 

        Session::flash('success', 'You have successfully logged in'); 

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    public function sendEmail($thisUser){
        Mail::to($thisUser['email'])->send(new verifyEmail($thisUser));
    }

    public function sendEmailDone($email,$verifyToken){

            $user = User::where(['email' => $email, 'verifyToken' => $verifyToken ])->first();
            if($user){
            $input['status'] = 1;
            $input['verifyToken'] = NULL;
            $result = $user->update($input);
            Session::flash('success', 'Email verification successfully completed');
            return redirect()->route('login');
            }else{
            Session::flash('danger', 'User not found');
            return redirect()->route('register');
            }
    }

}
