<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;

//validation
use App\Http\Requests\ticket_reserve;

use Illuminate\Support\Facades\DB;
use carbon\Carbon;

use App\Models\table08;
use App\Models\table09;
use App\Models\table10;
use Dotenv\Regex\Success;

class ticketsController extends BaseController
{

    //webAPI　re:チケット情報
    public function sales_interval(REQUEST $request){
        //返し値用配列 basecontrollerを使う場合　必要なし
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

        //m_ticket_intervals（tables07）
        //tables07 [biz_id][ticket_code][sales_id][ticket_interval_start]が同一のものが存在するか確認

        if(!DB::table('tables07')
                ->where('biz_id','=',$request->biz_id)
                ->where('ticket_code','=',$request->ticket_code)
                ->where('sales_id','=',$request->sales_id)
                ->where('ticket_interval_start','=',$request->interval_start)->exists()){
            array_push($error['error_message'],"non user tables07 m_ticket_intervals");
            return $error;
        }



        //dump($request->ticket_types[0]['type_id']);
        //m_tickets_types(tables05)
        //tables05[biz_Id][ticket_code][type_id]　同一存在確認
        /*
            これは変数にひとまず条件から保存して　foreachでticket_types[]部分を回す方法も考えるべき
            複数あった場合の対応として
            $temp=DB::table('tables05')
                ->where('biz_id','=',$request->biz_id)
                ->where('ticket_code','=',$request->ticket_code);
        */
        foreach($request->ticket_types as $temp){
            if(!DB::table('tables05')
                ->where('biz_id','=',$request->biz_id)
                ->where('ticket_code','=',$request->ticket_code)
                ->where('type_id','=',$temp['type_id'])->exists()){
                array_push($error['error_message'],"non user tables05 m_tickets_types");
                return $error;
            }
        }



        //m_ticket_reserves（tables08）
        //tables08[biz_id][ticket_code][sales_id][ticket_interval_start]より集約　それらのticket_total_numの合計値を出す
        $ticket_total_num = DB::table('tables08')
            ->where('biz_id','=',$request->biz_id)
            ->where('ticket_code','=',$request->ticket_code)
            ->where('sales_id','=',$request->sales_id)
            ->where('ticket_interval_start','=',$request->interval_start)->sum('ticket_total_num');


        //m_ticket_intervals（tables07）
        //tables07[biz_id][ticket_code][sales_id][ticket_interval_start]より集約　それらのticket_total_numの合計値を出す
        //ticket_numを取得
        $ticket_num=DB::table('tables07')
            ->where('biz_id','=',$request->biz_id)
            ->where('ticket_code','=',$request->ticket_code)
            ->where('sales_id','=',$request->sales_id)
            ->where('ticket_interval_start','=',$request->interval_start)->first()->ticket_num;

        //購入枚数がチケット販売枚数＋チケット層購入枚数よりも少ないことを確認　大きければエラーを返す
        /*
            これも同時に複数で叩かれた場合に対応を考える必要があります
            buy_numの合計を出して、それを購入枚数と考えて処理するべき？
        */


        //buy_numの合計値を出す(type_idは問わない　購入の全ての合計枚数で確認)
        $buy_num_total=0;
        foreach($request->ticket_types as $temp){
            $buy_num_total+=$temp['buy_num'];
        }

        //合計値が残数を超えている場合　エラーを出す
        if($buy_num_total > $ticket_num+$ticket_total_num){
            array_push($error['error_message'],"チケットの残り枚数が、全件分ありません");
            return $error;
        }

        //リクエストの単価が正しいものを送信しているか確認
        //m_ticket_types(tables05)->type_money（単価）が requestのtype_moneyと同一か確認
        //[biz_id][ticket_code][sales_id]
        foreach($request->ticket_types as $temp){
            if($temp['type_money']
                != DB::table('tables05')
                ->where('biz_id','=',$request->biz_id)
                ->where('ticket_code','=',$request->ticket_code)
                ->where('type_id','=',$temp['type_id'])->first()->type_money){
                    array_push($error['error_message'],"金額が異なります");
                    return $error;
                }
        }

        //販売期間内にあるかを確認
        //unixtimeより販売開始と販売終了の間であればよい
        //それぞれでエラー文を返すため、一つずつ判断する
        //m_sales_intervals(tables02)より　sales_interval_start（販売開始期間）
        //m_sales_intervals(tables02)より　sales_interval_end(販売終了期間)
        $tables02 = DB::table('tables02')
                    ->where('biz_id','=',$request->biz_id)
                    ->where('ticket_code','=',$request->ticket_code)
                    ->where('sales_id','=',$request->sales_id)->first();
        //比較用の時間（unix)取得
        $sales_interval_start = carbon::create($tables02->sales_interval_start)->format('U');
        $sales_interval_end   = carbon::create($tables02->sales_interval_end)->format('U');

        $nowTime = carbon::now();

        //dump("現在".$nowTime."　開始：".$sales_interval_start."　終了：".$sales_interval_end);
        //販売開始前
        if($nowTime->format('U') < $sales_interval_start){
            array_push($error['error_message'],"販売開始前です");
            return $error;
        }
        //販売開始後
        if($nowTime->format('U') > $sales_interval_end){
            array_push($error['error_message'],"販売期間を終了しています");
            return $error;
        }

        //reserve_code作成
        $reserv_code = $nowTime->format('U')*10000000+$request->user_id;

        //登録作業
        DB::beginTransaction();
        try{

            //登録時：必要データ格納テーブル
            $m_tables01 = DB::table('tables01')
                        ->where('biz_id','=',$request->biz_id)
                        ->where('ticket_code','=',$request->ticket_code)->first();
            $m_tables06 = DB::table('tables06')
                        ->where('biz_id','=',$request->biz_id)
                        ->where('ticket_code','=',$request->ticket_code)->first();
            $m_tables07 = DB::table('tables07')
                        ->where('biz_id','=',$request->biz_id)
                        ->where('ticket_code','=',$request->ticket_code)
                        ->where('sales_id','=',$request->sales_id)->first();


            //
            //  tables08 登録
            //

            $tables08 = new table08;
            //予約番号　現在日時（unix時間)と予約者番号で作成　１７桁０埋
            $tables08->reserv_code = $reserv_code;//修正必要
            $tables08->biz_id = $request->biz_id;
            $tables08->ticket_code = $request->ticket_code;
            $tables08->sales_id = $request->sales_id;
            $tables08->user_id = $request->user_id;
            $tables08->ticket_name = $m_tables01->ticket_name;
            $tables08->tickets_kind = $m_tables01->tickets_kind;
            $tables08->ticket_buyday = $nowTime;
            $tables08->ticket_interval_start = $m_tables07->ticket_interval_start;
            $tables08->ticket_interval_end = $m_tables07->ticket_interval_end;
            $tables08->ticket_start = $m_tables07->ticket_interval_start;
            $tables08->ticket_end = $m_tables07->ticket_interval_end;
            //購入枚数？？　複数でリクエストがあった場合はトータルをforeachで合計して出すようにしなくてはいけない
            $tables08->ticket_total_num = $buy_num_total;
            $tables08->cancel_limit_start = $m_tables07->ticket_interval_end;
            //2021/11/11確認：チケットの有効日時（終了）を入れる
            $tables08->cancel_end = $m_tables07->ticket_interval_end;
            $tables08->ticket_status = 0;            //0固定

            $tables08->save();



            //
            //  tables09 登録
            //
            $tables09 = new table09;
            //予約番号　現在日時（unix時間)と予約者番号で作成　１７桁０埋
            $tables09->reserv_code = $reserv_code;
            $tables09->svc_id = $m_tables06->svc_id;
            $tables09->svc_name = $m_tables06->svc_name;
            $tables09->svc_type = $m_tables06->svc_type;
            $tables09->svc_select_type = $m_tables06->svc_select_type;
            //2021/11/10 tables09にselect_btn_id追加
            $tables09->select_btn_id = 0;
            $tables09->usage_time = $m_tables06->usage_time;
            $tables09->svc_status = 0;            //0を固定
            $tables09->svc_start = NULL;          //NULL固定
            $tables09->svc_end = NULL;            //NULL固定
            $tables09->save();



            //
            //  tables10 登録
            //

            foreach($request->ticket_types as $temp){
                $tables10 = new table10;
                //予約番号　現在日時（unix時間)と予約者番号で作成　１７桁０埋
                $tables10->reserv_code = $reserv_code;
                $tables10->type_id = $temp['type_id'];
                $tables10->type_money = $temp['type_money'];
                $tables10->buy_num = $temp['buy_num'];
                $m_tables05 = DB::table('tables05')
                ->where('biz_id','=',$request->biz_id)
                ->where('ticket_code','=',$request->ticket_code)
                ->where('type_id','=',$temp['type_id'])->first();
                $tables10->cancel_money = round($temp['type_money']*($m_tables05->cancel_rate/100));
                //tables10のtype_name 念のため　tables05のものを入れています　※エラーがおきるため
                $tables10->type_name = $m_tables05->type_name;

                $tables10->save();
            }
            DB::RollBack();

            $test = DB::table('tables02');
            //$test->get();
            //dump($test);
            //$test->where('id','=',1)->first();
            //dump($test);
            dump($test->get());
            dump($test->where('id','=',7)->first());
            dump($test->get());

            //DB::commit();
            //return array('status'=>0,'reserve_code'=>$reserv_code);
            return $this->_success(array('reserv_code'=>$reserv_code));
        }catch(Exception $exception){
            DB::RollBack();
            //throw $exception;//例外を投げる ここで処理が終わる
            //return array('status'=>-1,'error_message'=>"登録エラー");
            return $this->_error(0);
        }
    }








    public function index(){
        return "test";
    }
}
