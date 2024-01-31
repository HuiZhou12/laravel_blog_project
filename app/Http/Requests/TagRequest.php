<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    //验证用户是否通过登录认证
    //触发时机： 在请求到达控制器之前，authorize() 方法会被调用。
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
     //返回验证规则数组
     //触发时机： 在授权通过后，请求数据将会被验证，rules() 方法会在数据验证之前被调用。
    public function rules()
    {
        return [
            'tag' => 'bail|required|unique:tags,tag',
            'title' => 'required',
            'subtitle' => 'required',
            'layout' => 'required',
        ];
    }
}
