<?php

namespace App\Http\Requests;



use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;  // 追加
use Illuminate\Http\Exceptions\HttpResponseException;  // 追加

class ticket_reserve extends FormRequest
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
    //Validationルール
    public function rules()
    {
        return [
            //
            'user_id'=>['required','string'],
            'biz_id'=>['required','integer'],
            'ticket_code'=>['required','string'],
            'sales_id'=>['required','integer'],
            'interval_start'=>['required','string'],
            //連想配列バリデーション
            'ticket_types'=>['required'],
            'ticket_types.type_id'=>['required','integer'],
            'ticket_types.type_money'=>['required','integer'],
            'ticket_types.buy_num'=>['required','integer']
        ];
    }

    //validationメッセージ（追記：[messages]
    public function messages()
    {
        return [
            'user_id.required'=>'入力されていません',
            'biz_id.required'=>'入力されていません',
            'ticket_code.required'=>'入力されていません',
            'sales_id.required'=>'入力されていません',
            'interval_start.required'=>'入力されていません',
            'ticket_types.required'=>'入力されていません',
            'type_id.required'=>'入力されていません',
            'type_money.required'=>'入力されていません',
            'buy_num.required'=>'入力されていません'
        ];
    }

    //エラーメッセージ対応　追加
    protected function failedValidation( Validator $validator )
    {
        $response['status']  = -1;
        $response['error_message']  = $validator->errors()->toArray();

        throw new HttpResponseException(
            response()->json( $response, 422 )
        );
    }
}
