<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;


class PostRequest extends FormRequest
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
            case 'admin.post.store':
            {
                return [ 
                            'title'          => 'required|string',
                            //'category_id[]' => 'required|array'
                         
                        ];
            }
            case 'admin.post.update':
            {
                return [ 
                        'title'         => 'required|string',
                        'user_id'       => 'integer',
                        //'category_id[]' => 'required|array'
                       ];
            }
            case 'admin.post.update.slug':
            {
                return [

                'id'                              => 'integer',
                'value'                           => 'string',

                ];
            }
            case 'admin.posts.delete':
            {
                return [

                      'item[]'                    => 'integer|array' 
                ];
            }
            case 'admin.post.meta.store':
            {
                return [

                    'meta_key'  => 'required|unique:post_metas,meta_key,null,null,post_id,'.$this->post_id,
                    'post_id'   => 'required'
                ];
            }
            case 'admin.post.meta.update':
            {
                return [

                      'meta_key'  => 'required|unique:post_metas,meta_key,'.$this->id.',id,post_id,'.$this->post_id,
                      'post_id'   => 'required' 
                ];
            }
            case 'admin.post.files.store':
            {
                $images = count($this->input('images'));
                foreach(range(0, $images) as $index) {
                    $rules['images.' . $index] = 'required|image|mimes:jpg,jpeg,png,ico,gif|max:2000';
                }
                    $rules = ['post_id' => 'required'];
                return $rules;
            }
            case 'admin.post.meta.element.update':
            {
                 return [ 
                        'title'         => 'required|string'
                       ];
            }
            case 'admin.post.change.status':
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
            //'category_id[].required' => 'Please select atleast one :attribute',
            'title.required' => 'Please enter a post title',
            'post_id.required' => 'Dont not try to throw false request'
        ];
    }

    public function attributes()
    {
        return [

            //'category_id[]' => 'category'
        ];
    }
}
