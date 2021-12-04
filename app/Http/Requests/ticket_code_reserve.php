<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ticket_code_reserve extends FormRequest
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

    /*
        #parameters: array:6 [
            "_token" => "3kh2cWCo2plXqJ1z0OrFAviUdrMgT3XWcU0TGSej"
            "type_money" => array:2 [
              0 => "1111"
              1 => "111"
            ]
            "buy_num" => array:2 [
              0 => "1"
              1 => "1"
            ]
            "type_id" => array:2 [
              0 => "1"
              1 => "2"
            ]
            "buy_money" => "1222"
            "ticket_interval_start" => "2021-11-22"
          ]
    */
    public function rules()
    {
/*         dump($this->ticket_max_num);
        dump($this->ticket_min_num); */
        dump($this);
        //変数での指定も可能
        return [
            //
            'buy_num.*'=>'required|integer|max:'.$this->ticket_max_num.'|min:'.$this->ticket_min_num
        ];
    }

    public function withValidator($validator){

        $validator->after(function($validator){
            if($this->ticket_max_num < array_sum($this->buy_num)){
                $validator->errors()->add("合計","チケットの合計が購入可能数を超えています 購入可能数は：".$this->ticket_max_num."　です。");
            }
        });

    }

    public function attributes(){
        return [
            'buy_num.*'=>'チケット数',
        ];

    }

    public function messages()
    {
        return [
            'buy_num.*.required'=>':attributeが入力されていません',
            'buy_num.*.max'=>'購入枚数は、最小数：'.$this->ticket_min_num.'から'.'最大数：'.$this->ticket_max_num.'になります',
            'buy_num.*.min'=>'購入枚数は、最小数：'.$this->ticket_min_num.'から'.'最大数：'.$this->ticket_max_num.'になります'
        ];
    }
}
