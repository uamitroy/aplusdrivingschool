<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;


class UserRequest extends FormRequest
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

        switch($routeName)
        {
            case 'admin.user.store':
            {
                return [ 
                        'fname' => 'required|string|regex:/^[\pL\s\-]+$/u',
                        'lname' => 'required|string|regex:/^[\pL\s\-]+$/u',
                        'email' => 'required|string|email|max:255|unique:users',
                        'dob' => 'required',
                        'phone' => 'required|min:8|max:13',
                        'address' => 'string',
                        'guardian_name' => 'string|regex:/^[\pL\s\-]+$/u',
                        'guardian_phone' => 'min:8|max:13',
                        'guardian_address' => 'string',
                        'password' => 'required|same:conf_password|regex:/(?=^.{8,12}$)(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_])^.*/|',
                        'conf_password' => 'required'
                      ];
            }
            case 'admin.user.update':
            {
                return [ 
                        'fname' => 'required|string|regex:/^[\pL\s\-]+$/u',
                        'lname' => 'required|string|regex:/^[\pL\s\-]+$/u',
                        'email' => 'required|email|unique:users,email,'.$this->id,
                        'dob' => 'required',
                        'phone' => 'required|min:8|max:13',
                        'address' => 'string',
                        'guardian_name' => 'string|regex:/^[\pL\s\-]+$/u',
                        'guardian_phone' => 'min:8|max:13',
                        'guardian_address' => 'required|string',
                        'image' => 'mimes:jpg,jpeg,png,ico'
                       ];
            }
            case 'admin.user.password.create':
            {
                return [ 
                        'password' => 'required|same:conf_password|regex:/(?=^.{8,12}$)(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_])^.*/',
                        'conf_password' => 'required'
                       ];
            }
            case 'admin.users.delete':
            {
                return [ 
                        'item' => 'required|array'
                       ];
            }
            case 'admin.user.change_status':
            {
                return [ 
                        'status' => 'required|numeric',
                        'id' => 'required|numeric'
                       ];
            }
            default:break;
        }

        
    }

    public function messages()
    {
        return [ 
            'email.required' => 'Please enter an email',
            'email.email' => 'Please enter a valid email',
            'email.unique' => 'This email is already exists',
            'password.required' => 'Please enter a password',
            'password.min' => 'Please enter a secure password (atleast 5 char)',
            'password.same' => 'The password and confirmed password must match.',
            'conf_password.required' => 'Please enter confirm password'
        ];
    }
}
