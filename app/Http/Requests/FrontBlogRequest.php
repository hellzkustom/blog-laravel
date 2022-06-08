<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Validation\Validator;
//use Illuminate\Http\Exceptions\HttpResponseException;


class FrontBlogRequest extends AdminBlogRequest//FormRequest
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
        $action = $this->getCurrentAction();
                $rules['index'] =  [
            'id'=>'integer|min:1',
            'year'=>'integer',
            'month'=>'integer',
            'category_id'=>'integer|min:1',
        ];
          
         $rules['article'] =  [
            'id'=>'integer|min:1',
            'year'=>'integer',
            'month'=>'integer',
            'category_id'=>'integer|min:1',
        ];
        
             $rules['commentPost'] =  [
                'name'=>'required|string|max:255',
               'body' => 'required|string|max:10000',
            ];


     return   Arr::get($rules, $action, []);
    }
    public function messages()
    {
    

        return [
            'year.integer'=>'年は整数を入れてください',
            'month.ineger'=>'月は整数を入れてください',
            'category_id.ineger'=>'カテゴリIDは整数にしてください',
            'category_id.min'=>'カテゴリIDは1以上にしてください',
            
            'id.ineger'=>'IDは整数にしてください',
            'id.min'=>'IDは1以上にしてください',
            
            'name.required'=>'nameは必須です',
            'name.string'=>'nameは文字列を入力してください',
          'name.max'=>'nameは:max文字以内で入力してください',

           'body.required'=>'本文は必須です',
            'body.string'=>'本文は文字列を入力してください',
             'body.max'=>'本文は:max文字以内で入力してください',

             
          ];
        
    }
   //     public function getCurrentAction()
//    {
        // 実行中のアクション名を取得
        // App\Http\Controllers\AdminBlogController@post のような返り値が返ってくるので @ で分割
  //      $route_action = Route::currentRouteAction();
    //    list(, $action) = explode('@', $route_action);
      //  return $action;
//    }
    
 //   protected function failedValidation(Validator $validator)
 //   {
   //     $action=$this->getCurrentAction();
        
     //   if($action=='article'||$action=='index')
//        {
  //          parent::failedValidation($validator);
    //    }
        
        

//        $response['errors']=$validator->errors();
      //  throw new HttpResponseException(
  //          response()->json($response,422));
//    }
    
    
    
    
}

