<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ticket_reserve;

use Illuminate\Support\Facades\DB;
use carbon\Carbon;

use App\Models\table08;
use App\Models\table09;
use App\Models\table10;

class ticketsController extends Controller
{

    //webAPI　re:チケット情報
    public function sales_interval(REQUEST $request){
        //返し値用配列
        $values=array('status'=>0,'total_num'=>0,'tickets'=>array());
        //選択した時間をcarbonをつかって書式を統一する
        $selectTime=new Carbon($request->sales_day);//これで2021-10-01 を2021-10-01 00:00:00の形に



        //件数カウント
        $tablenum=DB::table('tables02')
                    ->whereDate('sales_interval_start','<=',$selectTime->todatetimestring())//"日付の条件をつける"   いつから
                    ->whereDate('sales_interval_end','>=',$selectTime->todatetimestring())//”日付の条件２をつける” いつまで
                    ->get();

        //引数がない　もしくは　件数が０　の場合は status=-1 error_messageを返す
        if(!isset($request->sales_day)
             || !isset($request->num )
             || !isset($request->page)
             || $tablenum->count()==0){
            //エラー用変数
            $error=array('status'=>-1,'error_message'=>array());
            //各項目それぞれエラー処理をかける
            if(!isset($request->sales_day)){array_push($error['error_message'],"sales_dayは必須です");};
            if(!isset($request->num)){array_push($error['error_message'],"numは必須です");};
            if(!isset($request->page)){array_push($error['error_message'],"pageは必須です");};
            if(isset($request->sales_day)&&$tablenum->count()==0){array_push($error['error_message'],"件数は０です");};
            //エラーメッセージを返す
            return $error;
        }else{
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
                        $typevalue+=array("type_name"=>$t03->contents_data);
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
                        $typevalue+=array("type_name"=>$t05->type_name);
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



    //チケット予約
    //Validation使用
    public function ticket_reserve(ticket_reserve $request){
        //dump($request->user_id);
        //dump($request->ticket_types['type_id']);
        //エラー返答用配列
         $error=array('status'=>-1,'error_message'=>array());
        //table:users に存在するか
        //->exists()は存在するか否かを返答する
        //存在していればture いなければfalseを返答する
        if(!DB::table('users')->where('id',$request->user_id)->exists()){
            array_push($error['error_message'],"non user");
            return $error;
        }

        //合計の出し方
        //$total=DB::table('tables08')->sum('ticket_total_num');
        //チケット販売枚数
        //チケット総購入枚数

        //登録作業
        DB::beginTransaction();
        try{
            //テーブル宣言
            $tables08 = new table08;
            $tables09 = new table09;
            $tables10 = new table10;

            //
            //  tables08 登録
            //
            //予約番号　現在日時（unix時間)と予約者番号で作成　１７桁０埋
            $tables08->reserve_code = carbon::now()->format('U')*10000000+$request->user_id;
            $tables08->biz_id = $request->biz_id;
            $tables08->ticket_code = $request->ticket_code;
            $tables08->sales_id = $request->sales_id;
            $tables08->user_id = $request->user_id;

            //$tables08->ticket_name =
            //$tables08->tickets_kind =
            //$tables08->ticket_buyday =
            //$tables08->ticket_interval_start =
            //$tables08->ticket_interval_end =
            //$tables08->ticket_start =
            //$tables08->ticket_end =
            //$tables08->ticket_total_num =
            //$tables08->cancel_limit_start =

            //0を固定
            $tables08->ticket_status = 0;
            //$tables08->save()


            //
            //  tables09 登録
            //
            //予約番号　現在日時（unix時間)と予約者番号で作成　１７桁０埋
            $tables09->reserve_code = carbon::now()->format('U')*10000000+$request->user_id;

            //$tables09->svc_id =
            //$tables09->svc_name =
            //$tables09->svc_type =
            //$tables09->svc_select_type =
            //0を固定
            $tables09->select_btn_id = 0;
            //$tables09->usage_time =
            //0を固定
            $tables09->svc_status = 0;
            //NULL
            $tables09->svc_start = NULL;
            $tables09->svc_end = NULL;

            //$tables09->save();



            //
            //  tables10 登録
            //
            //予約番号　現在日時（unix時間)と予約者番号で作成　１７桁０埋
            $tables10->reserve_code = carbon::now()->format('U')*10000000+$request->user_id;
            //$tables10->type_id =
            //$tables10->type_money =
            //$tables10->buy_num =
            //$tables10->cancel_money

            //$tables10->save();


            //$tables10->save();

            dump($tables08);

            DB::commit();
        }catch(Exception $exception){
            DB::RollBack();
            throw $exception;//例外を投げる
        }


        return array('status'=>0);
    }








    public function index(){
        return "test";
    }
}
