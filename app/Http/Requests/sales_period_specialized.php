<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class sales_period_specialized extends FormRequest
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
            //共通
            'sales_interval_start_date'=>'required',
            'sales_interval_start_times'=>'required',
            'sales_interval_end_date'=>'required',
            'sales_interval_end_times'=>'required',

            //指定チケット
            'ticket_interval'=>'required|integer',
            'ticket_buy_date'=>'required',

            //共通
            'ticket_num'=>'required|integer',
            'ticket_min_num'=>'required|integer',
            'ticket_max_num'=>'required|integer'
        ];
    }


    public function messages(){
        return [
            'ticket_interval.integer'=>'数字を入力してください',
            'ticket_min_num.integer'=>'数字を入力してください',
            'ticket_max_num.integer'=>'数字を入力してください'
        ];
    }
}
