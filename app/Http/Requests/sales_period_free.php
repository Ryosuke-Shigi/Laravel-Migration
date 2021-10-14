<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class sales_period_free extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //trueで返せば認証が成功したとして処理を継続　false：問題があるとして４０３を返す
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

            //フリーチケット
            'ticket_interval_start'=>'required',
            'ticket_interval_end'=>'required',

            //共通
            'ticket_num'=>'required|integer',
            'ticket_min_num'=>'required|integer',
            'ticket_max_num'=>'required|integer'
        ];
    }

    //追記
    public function messages(){
        return [
            'ticket_interval.integer'=>'数字を入力してください',
            'ticket_min_num.integer'=>'数字を入力してください',
            'ticket_max_num.integer'=>'数字を入力してください'
        ];
    }


}
