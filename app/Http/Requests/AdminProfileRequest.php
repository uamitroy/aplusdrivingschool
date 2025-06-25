<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;


class AdminProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $routeName = Route::currentRouteName();
        $method = $this->method();
        $type = $routeName.'.'.$method;

        switch($type)
        {
            case 'admin.profile.GET':
            {
                return [];
            }
            case 'admin.profile.PATCH':
            {
                return [ 
                        'name' => 'required|regex:/^[\pL\s\-]+$/u',
                        'email' => 'required|email'
                       ];
            }
            case 'admin.change_password.GET':
            {
                return [];                    
            }
            case 'admin.change_password.PATCH':
            {
                return [ 
                        'password' => 'required',
                        'password' => 'required|same:conf_password|min:5',
                        'conf_password' => 'required'
                       ];
            }
            default:break;
        }

        
    }

    public function messages()
    {
        return [ 
            'name.required' => 'Please enter a name',
            'name.regex' => 'Please enter a valid name',
            'email.required' => 'Please enter an email',
            'email.email' => 'Please enter a valid email',
            'password.required' => 'Please enter a password',
            'password.min' => 'Please enter a secure password (atleast 5 char)',
            'password.same' => 'The password and confirmed password must match.',
            'conf_password.required' => 'Please enter confirm password'
        ];
    }
}
