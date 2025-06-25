<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class SiteSettingRequest extends FormRequest
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

        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'title'                      => 'required|string|max:25',
                    'tagline'                    => 'string|nullable',
                    'emails'                   	 => 'required|string|nullable',
                    'cc_emails'                  => 'string|nullable',
                    'bcc_emails'                 => 'string|nullable',
                    'logo'                       => 'mimes:jpg,jpeg,png,ico,gif,svg',
                    'favicon'                    => 'mimes:jpg,jpeg,png,ico,gif|nullable',
                    'loginbg'                    => 'mimes:jpg,jpeg,png,gif|nullable'
                ];
            }
            default:break;
        }

        
    }

}
