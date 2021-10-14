<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class store_rule extends FormRequest
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
        return [
            //
            'ticket_name'=>'required',
            'type_money.*'=>'required|integer',//配列ではname.*で
            'ticket_code'=>'required|string|max:5',
            'cancel_limit'=>'integer'
        ];
    }

    public function messages(){
        return [
            'ticket_name.required'=>'チケット名は必須です。',
            'type_money.*.required'=>'価格が入力されていません。',
            'type_money.*.integer'=>'数字で入力してください。',
            'ticket_code.max'=>'５文字以内で入力してください。',
            'ticket_code.required'=>'商品番号は必須です',
            'cancel_limit.integer'=>'数字を入力してください。'
        ];
    }
}
