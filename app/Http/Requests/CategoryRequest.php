<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\checkDelete;


class CategoryRequest extends FormRequest
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
            {
                return [];
            }
            case 'DELETE':
            {
                return [

                      'item[]'                    => ['integer','array',new checkDelete] 
                ];
            }
            case 'POST':
            {
                return [
                    
                    'title'                      => 'required|string|max:50|unique:categories,title'
                    
                    ];
            }
            case 'PUT':
            {
                return [

                'id'                              => 'integer',
                'value'                           => 'string',

                ];
            }
            case 'PATCH':
            {
                return [

                'title'                       => 'required|string|max:50|unique:categories,title,'.$this->id,
                'slug'                        => 'string',
                'parent'                      => 'integer',
                'image'                       => 'mimes:jpg,jpeg,png,ico,gif'
                ];
            }
            default:break;
        }

        
    }

    public function messages()
    {
        return [ 

            'title.required' => 'Please enter a category title',
            'title.max'      => 'Please enter a category title of max 50 character',
            'title.unique'   => 'This category is already exists',
            'parent.integer' => 'Something went wrong'
       
        ];
    }

}
