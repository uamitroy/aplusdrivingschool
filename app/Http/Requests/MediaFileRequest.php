<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class MediaFileRequest extends FormRequest
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
                $images = count($this->input('images'));
                foreach(range(0, $images) as $index) {
                    $rules['images.' . $index] = 'required|image|mimes:jpg,jpeg,png,ico,gif|max:2000';
                }
                return $rules;
            }
            default:break;
        }

        
    }

}
