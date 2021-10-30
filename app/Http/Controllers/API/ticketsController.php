<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use carbon\Carbon;

class ticketsController extends Controller
{

    //webAPI　re:チケット情報
    public function sales_interval(REQUEST $request){
        //返し値用配列
        $values=array('status'=>0,'total_num'=>0,'tickets'=>array());

        //引数確認　一つでもなければstatusを-1
        if(!isset($request->sales_day)
             || !isset($request->num )
             || !isset($request->page)){
            $values['status']=-1;
        }else{
            //選択した時間をcarbonをつかって書式を統一する
            $selectTime=new Carbon($request->sales_day);
            //table01,02,03
            $tables=DB::table('tables01')
            ->join('tables02','tables01.ticket_code','=','tables02.ticket_code')
            //->join('tables03','tables01.ticket_code','=','tables03.ticket_code')
            //->join('tables05','tables01.ticket_code','=','tables05.ticket_code')
            ->select(['tables01.id','tables02.biz_id','tables02.ticket_code','tables02.sales_id','tables01.ticket_name','tables02.sales_interval_start','tables02.sales_interval_end'])
            //条件：選択した時間がstartからendの間にある
            ->whereDate('sales_interval_start','<=',$selectTime->todatetimestring())//"日付の条件をつける"   いつから
            ->whereDate('sales_interval_end','>=',$selectTime->todatetimestring())//”日付の条件２をつける” いつまで
            ->paginate($request->num,$request->page);

            //総件数カウント
            $tablenum=DB::table('tables02')
            //->select(['tables01.id','tables02.biz_id','tables02.ticket_code','tables02.sales_id','tables01.ticket_name','tables03.contents_data','tables02.sales_interval_start','tables02.sales_interval_end'])
            //条件：選択した時間がstartからendの間にある
            ->whereDate('sales_interval_start','<=',$selectTime->todatetimestring())//"日付の条件をつける"   いつから
            ->whereDate('sales_interval_end','>=',$selectTime->todatetimestring())//”日付の条件２をつける” いつまで
            ->get();
            //最大数取得
            $values['total_num']=$tablenum->count();

            //tables03取得
            $tables03=DB::table('tables03')->get();
            //tables05取得
            $tables05=DB::table('tables05')->get();

            //$tempに１件分のtiketsを作成して、まとめの$valueに入れていく
            //$tablesで抽出したデータを元に　ticket_codeで判断して　同じであれば入れていく
            foreach($tables as $value){
                //一軒分をとるために　仮の配列を作成
                $temp=array('biz_id'=>$value->id,'ticket_code'=>$value->ticket_code,'sales_id'=>$value->sales_id,'ticket_name'=>$value->ticket_name,'ticket_contents'=>array(),'ticket_types'=>array());

                //tables03で同じチケットコードであれば　ticket_contentsという配列にまとめていれていく
                foreach($tables03 as $t03){
                    if($value->ticket_code == $t03->ticket_code){
                        //空の配列を用意
                        $typevalue=array();
                        //type_nameとtype_moneyをまとめ
                        $typevalue+=array("type_name"=>$t03->contents_data,);
                        //ticket_contentsにいれる　　をあるだけ繰り返して挿入する
                        array_push($temp['ticket_contents'],$typevalue);
                    }
                }
                //tables05で同じチケットコードであれば　ticket_typesという配列にまとめていれていく
                foreach($tables05 as $t05){
                    if($value->ticket_code == $t05->ticket_code){
                        //空の配列を用意
                        $typevalue=array();
                        //type_nameとtype_moneyをまとめ
                        $typevalue+=array("type_name"=>$t05->type_name,);
                        $typevalue+=array("type_money"=>$t05->type_money);
                        //ticket_typesにいれる　　をあるだけ繰り返して挿入する
                        array_push($temp['ticket_types'],$typevalue);
                    }
                }
                //最後にまとめてvaluesのtickets内に放り込む
                array_push($values['tickets'],$temp);
                //}
            }
    }
        return $values;
    }










    public function index(){
        return "test";
    }
}
