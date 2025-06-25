<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;


class PageRequest extends FormRequest
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
            case 'admin.page.store':
            {
                return [ 
                        'title'          => 'required|string',
                      ];
            }
            case 'admin.page.update':
            {
                return [ 
                        'title'         => 'required|string',
                        'user_id'       => 'integer'
                       ];
            }
            case 'admin.page.update.slug':
            {
                return [

                'id'                              => 'integer',
                'value'                           => 'string',

                ];
            }
            case 'admin.pages.delete':
            {
                return [

                      'item[]'                    => 'integer|array' 
                ];
            }
            case 'admin.page.update.status':
            {
                return [

                         'id'                              => 'integer'
                ];
            }
            case 'admin.page.meta.store':
            {
                return [

                    'meta_key'  => 'required|unique:post_metas,meta_key,null,null,post_id,'.$this->post_id,
                    'post_id'   => 'required'
                ];
            }
            case 'admin.page.meta.update':
            {
                return [

                      'meta_key'  => 'required|unique:post_metas,meta_key,'.$this->id.',id,post_id,'.$this->post_id,
                      'post_id'   => 'required' 
                ];
            }
            case 'admin.page.meta.delete':
            {
                return [];
            }

            case 'admin.page.meta.element.update':
            {
                 return [ 
                        'title'         => 'required|string'
                       ];
            }
            case 'admin.page.change.status':
            {
                 return [ 
                        'id'         => 'required|integer',
                        'status'     => 'required|integer'
                       ];
            }
            default:break;
        }

        
    }

    public function messages()
    {
        return [ 

            'title.required' => 'Please enter a post title',
        ];
    }

}
