<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;


//validation formrequest 2021/10/14
use App\Http\Requests\sales_period_free;
use App\Http\Requests\sales_period_specialized;
use App\Http\Requests\store_rule;

//ログイン・ログアウト
use Auth;

//日時関係で使用　２０２１年１０月１０日追加
use Carbon\Carbon;

//guzzle ｗｅｂＡＰＩを叩く
use GuzzleHttp\Client;


use App\Models\table01;
use App\Models\table02;
use App\Models\table03;
use App\Models\table04;
use App\Models\table05;
use App\Models\table06;
use App\Models\table07;

//フリーチケット時のvalidation rule
use App\Rules\sales_period_free_rule;
//指定チケット時のvalidation rule
use App\Rules\sales_period_specialized_rule;
use Facade\FlareClient\Http\Client as HttpClient;

class TicketController extends Controller
{
    use SoftDeletes;


    //一覧表示
    public function index(Request $request){

         //viewへ送るデータ（ここへticket_nameとticket_codeを追加していきます）
         $table=DB::table('tables01')
        ->join('tables05','tables01.ticket_code','=','tables05.ticket_code')
        //->where('tables05.type_id',1)
        ->select(['tables01.id','tables01.biz_id','tables01.ticket_code','tables01.ticket_name','tables05.type_name','tables05.type_money'])
        //->orderBy('ticket_code')->orderBy('ticket_name')
        ->where('tables05.type_id',1)
        ->paginate(10);

        //ticket_name ticket_name 同じものを検索用
        $table05=DB::table('tables01')
        ->join('tables05','tables01.ticket_code','=','tables05.ticket_code')
        ->select(['tables01.id','tables01.biz_id','tables01.ticket_code','tables01.ticket_name','tables05.type_name','tables05.type_money'])
        //->orderBy('ticket_name')->orderBy('ticket_code')//ソート大事　とても大事
        ->get();


        //同一チケット名で、金額が複数種類入力されている場合　ひとつにまとめて一方を消す
        //一つずつ確認をとる
        foreach($table as $index){
            $name=array();
            $money=array();
            foreach($table05 as $values){
                    if(($index->ticket_code == $values->ticket_code
                        && $index->ticket_name == $values->ticket_name)){
                        array_push($name,$values->type_name);
                        array_push($money,$values->type_money);
                    }
            }
            $index->type_name=$name;
            $index->type_money=$money;
        }

        //一覧表示したらログアウトさせる
        Auth::logout();

        return view("ticket_register",compact('table'));
    }

    public function sales_period_index(){

        /* 販売期間登録 一覧表示へ */
/*         $table=DB::table('tables07')
        ->join('tables02','tables07.sales_id','=','tables02.sales_id')
        ->leftjoin('tables01','tables07.ticket_code','=','tables01.ticket_code')
        ->select(['tables02.id','tables02.biz_id','tables02.ticket_code','tables01.ticket_name','tables02.sales_interval_start','tables02.sales_interval_end','tables07.ticket_num'])
        ->orderBy('tables01.ticket_name',"asc")
        ->paginate(10); */
        $table=DB::table('tables07')
        ->join('tables02',function($join){  //join複数条件
            $join->on('tables07.sales_id','=','tables02.sales_id')
            ->on('tables07.ticket_code','=','tables02.ticket_code');})
        //->join('tables02','tables07.sales_id','=','tables02.sales_id')
        ->leftjoin('tables01','tables07.ticket_code','=','tables01.ticket_code')
        ->select(['tables02.id','tables02.biz_id','tables02.sales_id','tables02.ticket_code','tables01.ticket_name','tables02.sales_interval_start','tables02.sales_interval_end','tables07.ticket_num'])
        ->orderBy('tables01.ticket_name',"asc")
        ->paginate(10);

        //一覧表示したらログアウトさせる
        Auth::logout();
        return view("sales_period_index",compact('table'));


    }




    //チケット一覧表示(初期画面)
    public function ticket_list_init(){
        //チケットネームが欲しかったのでwebAPIを呼び出している
        //$nowTime = carbon::now()->format('Y_m_d');//現在の年月日を抽出
        $nowTime = "2021-10-01";//テスト用
        $client = new Client();
        $url = "http://127.0.0.1:8080/api/tickets_code_name";
        //$response = $client->request('GET',$url);
        $response = $client->request('GET',$url,[
                                        'query'=>[
                                            'sales_day'=>$nowTime,
                                            'num'=>4,
                                            'page'=>1,
                                            ]
                                        ]);
        $list=$response->getBody();
        //Jsonデータにデコードする
        $list=json_decode($list,true);
        $list=$list['tickets'];

        return view("ticket_list_init",compact('list'));
    }


    //別のAPIを呼び出して、ticket_codeでさらに絞ったものをリストにするためのデータを送る
    //チケット一覧表示(初期画面)
    public function ticket_list(REQUEST $request){
        //チケット名を取り出すだけのような形
        //$nowTime = carbon::now()->format('Y_m_d');//現在の年月日を抽出
        $nowTime = "2021-10-01";//テスト用
        $client = new Client();
        $url = "http://127.0.0.1:8080/api/tickets_code_name";
        //$response = $client->request('GET',$url);
        //dump($request->ticket_code);
        $response = $client->request('GET',$url,[
                                        'query'=>[
                                            'sales_day'=>$nowTime,
                                            'num'=>4,
                                            'page'=>1,
                                            ]
                                        ]);
        $list=$response->getBody();
        //Jsonデータにデコードする
        $list=json_decode($list,true);
        $list=$list['tickets'];
        //ticket_codeで絞るapiを呼び出しデータを取得する
        $client2 = new Client();
        $url = "http://127.0.0.1:8080/api/tickets_ticket_code_detail";
        //$response = $client->request('GET',$url);
        //dump($request->ticket_code);
        $response2 = $client2->request('GET',$url,[
                                        'query'=>[
                                            'sales_day'=>$nowTime,
                                            'num'=>10,
                                            'page'=>1,
                                            'ticket_code'=>$request->ticket_code
                                            ]
                                        ]);
        $list2=$response2->getBody();
        //Jsonデータにデコードする
        $list2=json_decode($list2,true);
        $list2=$list2['tickets'];


        //listは名前のボタン用　list2はticket_codeで絞ったデータ
        return view("ticket_list",compact('list'),compact('list2'));
    }




    //テスト用修正がはいっています　２０２１１１２０
    //枚数選択のみの購入画面作成 20211119
    public function view_ticket_code_reserve($ticket_code,$sales_id){
        $values = array("tickets"=>array(),'ticket_min_num'=>0,'ticket_max_num'=>0,'ticket_num'=>0);
        $ticket_buy_num = 0;    //現在の購入数


        //ticket_codeとsales_idで　購入済みのチケット数を抽出する
        $client = new Client();
        $url = "http://127.0.0.1:8080/api/re_buyNum";
        $response = $client->request('GET',$url,[
                                        'query'=>[
                                            'ticket_code'=>$ticket_code,
                                            'sales_id'=>$sales_id//（テストのため３にしている）20211020
                                            ]
                                        ]);
        //Jsonデータをデコードする
        //ticket_code と sales_idより　購入されているチケットの枚数を取得
        $ticket_buy_num = json_decode($response->getBody(),true);

        //ticket_codeとsales_idで　購入済みのチケット数を抽出する
        $client2 = new Client();
        $response = $client2->request('GET',"http://127.0.0.1:8080/api/re_types",[
                                        'query'=>[
                                            'ticket_code'=>$ticket_code,
                                        ]
                                        ]);
        //Jsonデータをデコードする
        //ticket_code と sales_idより　購入されているチケットの枚数を取得
        $values["tickets"] = json_decode($response->getBody(),true);


        //購入する際の最小数と最大数を表示
        $tables07 = DB::table('tables07')
        ->where('ticket_code','=',$ticket_code)
        ->where('sales_id','=',$sales_id)
        ->first();
        $values['ticket_min_num']=$tables07->ticket_min_num;
        $values['ticket_max_num']=$tables07->ticket_max_num;
        //本来なら不要だが　minとmaxを入力間違えて入れば
        if($values['ticket_min_num'] > $tables07->ticket_max_num){
            $temp = $values['ticket_min_num'];
            $values['ticket_min_num'] = $values['ticket_max_num'];
            $values['ticket_max_num'] = $temp;
        }
        $values['ticket_num']=$tables07->ticket_num;

        //dump(carbon::now()->format("Y-m-d H:i:s"));//現在日時を2021-11-19 08:02:36でとる
        return view("ticket_code_reserve",compact('values','ticket_code','sales_id'));
    }
    //post
    //20211121
    //購入画面からPOSTで登録
    public function ticket_code_reserve(REQUEST $request,$ticket_code,$sales_id){
        $biz_id = 1;
        $user_id = 1;
        $interval_start = "2021-11-08 00:00:00";

        $client = new Client();
        $url = 'http://127.0.0.1:8080/api/tickets/reserve';

        $param=array('user_id'=>$user_id,
                    'biz_id'=>$biz_id,
                    'ticket_code'=>$ticket_code,


                    'sales_id'=>3,//テスト$sales_id


                    //年月日のみなので　2011-00-00 00:00:00に合わせる
                    'interval_start'=>Carbon::parse($interval_start)->toDateTimeString(),
                    'ticket_types'=>array());
        foreach($request->type_id as $index=>$value){
            array_push($param['ticket_types'],array('type_id'=>$request->type_id[$index],
                                                    'type_money'=>$request->type_money[$index],
                                                    'buy_num'=>$request->buy_num[$index]));
        }
        dump($param);
        $response = $client->request('POST',$url,['json'=>$param]);

        //返り値を受け取る！
        dump($response->getBody()->getContents());




        return redirect('index');
    }




    //webAPIをPOST送信　登録処理
    public function ticket_reserve(REQUEST $request){


        $client = new Client();
        $url = 'http://127.0.0.1:8080/api/tickets/reserve';

        $param=array('user_id'=>$request->user_id,
                    'biz_id'=>$request->biz_id,
                    'ticket_code'=>$request->ticket_code,
                    'sales_id'=>$request->sales_id,
                    //年月日のみなので　2011-00-00 00:00:00に合わせる
                    'interval_start'=>Carbon::parse($request->interval_start)->toDateTimeString(),
                    'ticket_types'=>array());
        foreach($request->type_id as $index=>$value){
            array_push($param['ticket_types'],array('type_id'=>$request->type_id[$index],
                                                    'type_money'=>$request->type_money[$index],
                                                    'buy_num'=>$request->buy_num[$index]));
        }

        $response = $client->request('POST',$url,['json'=>$param]);

        //返り値を受け取る！
        dump($response->getBody()->getContents());


        return redirect('index');
    }







    /*販売期間登録　商品番号選択画面*/
    public function sales_period(){
        $table=DB::table('tables01')->get();
        return view("sales_period",compact('table'));
    }


    /*
    *
    *   @param Request $request
    *   @return Response
    *
    */

    //登録画面表示
    public function store(){
        return view("create_ticket");
    }

    //編集作業
    public function update(Request $request,$ticket_code){

        //tables01更新
        $table=table01::where('ticket_code',$ticket_code)
        ->first();
        //findもしくはfirst　１レコードずつになる
        //ジャンルコード
        if(isset($request->genre_code1)){
            $table->genre_code1=1;
        }else{
            $table->genre_code1=0;
        }
        if(isset($request->genre_code2)){
            $table->genre_code2=1;
        }else{
            $table->genre_code2=0;
        }
        //チケットお問い合わせ
        $table->ticket_remarks=$request->ticket_remarks;
        //未成年フラグ
        if(isset($request->minors_flag)){
            $table->minors_flag=1;
        }else{
            $table->minors_flag=0;
        }
        //キャンセル料発生期限
        $table->cancel_limit=$request->cancel_limit;
        //チケット種類
        $table->tickets_kind=$request->tickets_kind;
        $table->save();

        //tables03更新
        if(isset($request->overview)){
             $table=table03::where('ticket_code',$ticket_code)
            ->where('contents_type',1)
            ->first();
            if($table != NULL){
                $table->contents_data=$request->overview;
                $table->save();
            }
        }
        if(isset($request->contents_data)){
            $table=table03::where('ticket_code',$ticket_code)
           ->where('contents_type',2)
           ->first();
           if($table != NULL){
                $table->contents_data=$request->contents_data;
                $table->save();
           }
       }

       //table04更新
       if(isset($request->important_notes)){
            $table=table04::where('ticket_code',$ticket_code)
            ->where('cautions_type',1)
            ->first();
            if($table != NULL){
                $table->cautions_text=$request->important_notes;
                $table->save();
            }
        }
        if(isset($request->detail_notes)){
            $table=table04::where('ticket_code',$ticket_code)
            ->where('cautions_type',2)
            ->first();
            if($table != NULL){
                $table->cautions_text=$request->detail_notes;
                $table->save();
            }
        }
        if(isset($request->item_notes)){
            $table=table04::where('ticket_code',$ticket_code)
            ->where('cautions_type',3)
            ->first();
            if($table != NULL){
                $table->cautions_text=$request->item_notes;
                $table->save();
            }
        }

        //table05更新
        //$table->save();
        //まだ途中　他のコード分を考えるため　
        //名称：単価：キャンセル料はtype_idで１～並んでいるので、順番に当てはめていく
        foreach($request->type_id as $key=>$index){
            $table=table05::find($request->id[$key]);
            $table->type_name=$request->type_name[$key];
            $table->type_money=$request->type_money[$key];
            $table->cancel_rate=$request->cancel_rate[$key];
            $table->save();
        }

        //table06更新
        if(isset($request->svc_name)){
            $table=table06::where('ticket_code',$ticket_code)
            ->first();
            if($table != NULL){
                $table->svc_name=$request->svc_name;
                $table->save();
            }
        }
        if(isset($request->svc_type)){
            $table=table06::where('ticket_code',$ticket_code)
            ->first();
            if($table != NULL){
                $table->svc_type=$request->svc_type;
                $table->save();
            }
        }

        return redirect('index');
    }

    //編集画面表示
    public function update_types($ticket_code){
        $table=DB::table('tables05')
        ->join('tables01','tables01.ticket_code','=','tables05.ticket_code')
        ->where('tables05.ticket_code',$ticket_code)
        ->select(['tables05.id','tables05.biz_id','tables05.ticket_code','tables01.ticket_name','tables05.type_id','tables05.type_name','tables05.type_money','tables05.cancel_rate'])
        ->orderBy('tables05.type_id')
        ->get();

        return view("update_table5",compact('table'));
    }






    //固定部分以外すべて修正できる　画面を表示    //ticket_codeで対象をしぼります
    public function update_all($ticket_code){

        $table1=DB::table('tables01')
        ->where('ticket_code','=',$ticket_code)
        ->get();
        $table3=DB::table('tables03')
        ->where('ticket_code','=',$ticket_code)
        ->get();
        $table4=DB::table('tables04')
        ->where('ticket_code','=',$ticket_code)
        ->get();
        $table5=DB::table('tables05')
        ->where('ticket_code','=',$ticket_code)
        ->get();
        $table6=DB::table('tables06')
        ->where('ticket_code','=',$ticket_code)
        ->get();

        return view("update_tables",compact('table1','table3','table4','table5','table6'));

    }


    //削除
    public function delete($ticket_code,$ticket_name){
/*         dump($ticket_code);
        dump($ticket_name); */

        return redirect('index3');
    }
    //テーブル０１基準　削除
    public function delete_ticket_code_name($ticket_code,$ticket_name){
        //ticket_codeとticket_nameの二つをwhereでわけて、削除する
        //table1からtable3,table5は関連付けされているので　一緒にその他データも削除される
        $table=table01::where('ticket_code',$ticket_code)
        ->where('ticket_name',$ticket_name)
        ->first()->delete();

        return redirect('index');
    }
    //販売期間登録削除
    public function delete_ticket_code_sales_period($ticket_code){
        table02::where('ticket_code',$ticket_code)
        ->first()->delete();
        return redirect('sales_period_index');
    }





    //登録作業
    public function create(store_rule $request){

/*         $this->validate($request,[
            'ticket_name'=>'required',
            'type_money.*'=>'required|integer',//配列ではname.*で
            'ticket_code'=>'required|string|max:5',
            'cancel_limit'=>'integer'
        ],[
            'ticket_name.required'=>'チケット名は必須です。',
            'type_money.*.required'=>'価格が入力されていません。',
            'type_money.*.integer'=>'数字で入力してください。',
            'ticket_code.max'=>'５文字以内で入力してください。',
            'ticket_code.required'=>'商品番号は必須です',
            'cancel_limit.integer'=>'数字を入力してください。'
        ]); */

        //モデルをインスタンス化
        $tables01 = new table01;
        $tables03 = new table03;
        $tables04 = new table04;
        $tables05 = new table05;
        $tables06 = new table06;
        //手動　トランザクション・スタート
        //全ての操作が完了するか　全てキャンセルされるか　のどちらかになる
        DB::beginTransaction();
        try{
            //table01の処理
            //事業者ID
            $tables01->biz_id = 1;
            //商品番号
            $tables01->ticket_code=$request->ticket_code;
            //地域コード
            $tables01->spot_area_id = 1;
            //ジャンルコード大 formで送信されていなければで判断
            if(isset($request->genre_code1)){
                $tables01->genre_code1=1;
            }else{
                $tables01->genre_code1=0;
            }
            //ジャンルコード小
            if(isset($request->genre_code2)){
                $tables01->genre_code2=1;
            }else{
                $tables01->genre_code2=0;
            }
            //チケット名
            $tables01->ticket_name=$request->ticket_name;
            //チケットお問い合わせ
            $tables01->ticket_remarks=$request->ticket_remarks;
            //チケット種類
            $tables01->tickets_kind=$request->tickets_kind;
            //未成年フラグ
            if(isset($request->minors_flag)){
                $tables01->minors_flag=1;
            }else{
                $tables01->minors_flag=0;
            }
            //キャンセル料発生期限（分)
            $tables01->cancel_limit=$request->cancel_limit;
            //課題でcancel_flag　NULL不可　とりあえず値を入れておく　int
            $tables01->cancel_flag=0;
            //とりあえず０をいれておく
            //tables01に値をいれる
            $tables01->save();




            //table03の処理
            //概要・チケット紹介　それぞれ入っていればレコードわけて保存する
            //概要が入力されていれば概要の分も保存
            if(isset($request->overview)){
                //事業者ID
                $tables03->biz_id=1;
                //商品番号
                $tables03->ticket_code=$request->ticket_code;

                //チケット紹介内容index
                $tables03->contents_index=1;
                //区分１
                $tables03->contents_type=1;
                //概要のデータをいれる
                $tables03->contents_data=$request->overview;
                //tables03に値を入れる
                $tables03->save();
                $tables03= new table03; //newしなおさないと　オートIDが更新されない　createで試してみたが駄目だったのとりあえずこれで

            }
            //チケット紹介が入力されていればチケット紹介の分も保存
            if(isset($request->contents_data)){
               //事業者ID
                $tables03->biz_id=1;
                //商品番号
                $tables03->ticket_code=$request->ticket_code;

                //チケット紹介内容index
                $tables03->contents_index=1;
                //区分２
                $tables03->contents_type=2;
                //チケット紹介のデータを入れる
                $tables03->contents_data=$request->contents_data;
                //tables03に値を入れる
                $tables03->save();
            }



            //tables04の処理
            //重要注意事項
            if(isset($request->important_notes)){
                //事業者ID
                $tables04->biz_id=1;
                //商品番号
                $tables04->ticket_code=$request->ticket_code;
                //注意事項index (現在固定)
                $tables04->cautions_index=1;

                $tables04->cautions_type=1;
                $tables04->cautions_text=$request->important_notes;
                $tables04->save();          //saveしてから
                $tables04 = new table04;    //newしなおさないと　オートIDが更新されない！
            }
            //詳細：注意事項
            if(isset($request->detail_notes)){
                //$tables04->create();
                //事業者ID
                $tables04->biz_id=1;
                //商品番号
                $tables04->ticket_code=$request->ticket_code;
                //注意事項index (現在固定)
                $tables04->cautions_index=1;

                $tables04->cautions_type=2;
                $tables04->cautions_text=$request->detail_notes;
                $tables04->save();
                $tables04 = new table04;
            }
            //料金：注意事項
            if(isset($request->item_notes)){
                //$tables04->create();
                //事業者ID
                $tables04->biz_id=1;
                //商品番号
                $tables04->ticket_code=$request->ticket_code;
                //注意事項index (現在固定)
                $tables04->cautions_index=1;

                $tables04->cautions_type=3;
                $tables04->cautions_text=$request->item_notes;
                $tables04->save();
            }


            //tables05処理
            //券種ID１
            if(isset($request->type_money)){
                foreach($request->type_money as $index=>$values){
                    //事業者ID
                    $tables05->biz_id=1;
                    //商品番号
                    $tables05->ticket_code=$request->ticket_code;
                    //キャンセル区分
                    $tables05->cancel_type=1;

                    $tables05->type_id=$index+1;                            //券種ID
                    $tables05->type_name=$request->type_name[$index];       //単価名称
                    $tables05->cancel_rate=$request->cancel_rate[$index];   //キャンセル料単価
                    $tables05->type_money=$request->type_money[$index];     //単価！
                    //tables05に値を入れる
                    $tables05->save();
                    $tables05 = new table05;
                }
            }




            //tables06の処理
            //事業者ID
            $tables06->biz_id=1;
            //商品番号
            $tables06->ticket_code=$request->ticket_code;
            //サービスID
            $tables06->svc_id=1;
            //サービス名称
            $tables06->svc_name=$request->svc_name;
            //サービス注意事項
            $tables06->svc_cautions=$request->svc_cautions01.$request->svc_cautions02;
            //サービス種類
            $tables06->svc_type=$request->svc_type;
            //サービス選択区分
            $tables06->svc_select_type=1;
            //サービス利用時間
            $tables06->usage_time=0;

            //tables06に値を入れる
            $tables06->save();


            //変更を確定させる
            DB::commit();               //処理を実行する
        }catch(Exception $exception){    //catch()で例外クラスを指定する　Exceptionはphpの例外クラス
            //データ操作を巻き戻す
            DB::RollBack();             //処理を戻す
                throw $exception;           //例外を投げる（例外を知らせる）例外メッセージの取得はExceptionのgetMessage();
        }

        return redirect('index3');
    }


    //チケット登録
    public function sales_period_create(sales_period_free $request){


        $tables02 = new table02;
        $tables07 = new table07;

        //試してみたものの　エラーが発生した際、sales_period_registerに送っていたテーブルデータを
        //再度送ることができないので、使用できていない
        //validateのエラーが発生した際の設定を組み込む必要がある
        //$thisにはコントローラーそのものが含まれている　このコントローラーのvalidateという意味あいか

/*         if($request->tickets_kind==1){
            $this->validate($request,[
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
            ],[
                'ticket_interval.integer'=>'数字を入力してください',
                'ticket_min_num.integer'=>'数字を入力してください',
                'ticket_max_num.integer'=>'数字を入力してください'
            ]);
        }else{
            $this->validate($request,[
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
            ],[
                'ticket_interval.integer'=>'数字を入力してください',
                'ticket_min_num.integer'=>'数字を入力してください',
                'ticket_max_num.integer'=>'数字を入力してください'
            ]);
        } */


        //dump($tables07->count());
        //dump($request);
        //dump($request->sales_interval_start_date." ".$request->sales_interval_start_times);
        DB::beginTransaction();
        try{
            //tables02
            //事業者IDID
            $tables02->biz_id=1;
            //商品番号
            $tables02->ticket_code=$request->ticket_code;
            //販売期間 開始日時と時間　." ". は間に空白を入れないと　書式があわなくなるため 2021-10-11 10:10:10 の中心空白部分
            $tables02->sales_interval_start=$request->sales_interval_start_date." ".$request->sales_interval_start_times;
            $tables02->sales_interval_end=$request->sales_interval_end_date." ".$request->sales_interval_end_times;


            //商品番号（ticket_code)＋数字（１から順番につけていく）　tables07でも同様なので　同一名でいくようにしよう！
            //ticket_code%で数をカウント　複数なければ０　複数あればsales_idの最大値を+1して削除してもsales_idは重複しないようにする。
            if(DB::table('tables02')
                ->where('ticket_code','=',$request->ticket_code)
                //->where('ticket_code','like',"$request->ticket_code"."%")
                ->get()->count()==0){
                //同じticket_codeがなければsales_idは１
                $tables02->sales_id=$tables07->sales_id=1;
            }else{
                //同じticket_codeがあれば、sales_idは割り振られているのでmaxをとって＋１していれる
                $tables02->sales_id=$tables07->sales_id=DB::table('tables02')->max('sales_id')+1;
            }

            //tables02保存
            $tables02->save();

            //tables07
            //事業者ID
            $tables07->biz_id=1;//固定
            //商品番号
            $tables07->ticket_code=$request->ticket_code;
            //販売ID
            //$tables07->sales_id=$request->ticket_code."($tables07->count()+1)";
            //$tables07->sales_id=10;
            //チケット利用可能日時（開始）
            //チケット利用可能日時（終了）
            //チケット有効日数
            //フリーチケット tables07
            if($request->tickets_kind==1){
                //チケット利用可能日時（開始）
                $tables07->ticket_interval_start=$request->ticket_interval_start;
                //チケット利用可能日時（終了）
                $tables07->ticket_interval_end=$request->ticket_interval_end;
                //チケット有効日数
                $tables07->ticket_num=$request->ticket_num;
                //チケット有効日数　フリーであれば　０固定
                $tables07->ticket_days=0;
            }else{  //指定チケット
                //チケット利用可能日時（開始） 指定チケットの場合は現在時刻をいれる
                $tables07->ticket_interval_start=Carbon::now()->toDateTimeString();//Carbon::now()静的メソッド呼び出し　carbon 現在日時
                //チケット利用可能日時（終了）　carbon::now()+$request->ticket_interval　がはいる
                $tables07->ticket_interval_end=Carbon::now()->addday($request->ticket_interval)->toDateTimeString();    //toDateTimeString()がフォーマット変換
                //チケット有効日数　指定の場合　有効期限を格納
                $tables07->ticket_days=$request->ticket_interval;
            }
            //チケット販売枚数
            $tables07->ticket_num=$request->ticket_num;
            //チケット最小購入枚数
            $tables07->ticket_min_num=$request->ticket_min_num;
            //チケット最大購入枚数
            $tables07->ticket_max_num=$request->ticket_max_num;
            $tables07->save();

            //変更を確定させる まだ曖昧な部分があるので　コミットはまだしない　２０２１年１０月１０日１８：５７
            DB::commit();               //処理を実行する
        }catch(Exception $exception){    //catch()で例外クラスを指定する　Exceptionはphpの例外クラス
            //データ操作を巻き戻す
            DB::RollBack();             //処理を戻す
                    throw $exception;           //例外を投げる（例外を知らせる）例外メッセージの取得はExceptionのgetMessage();
        }
        return redirect('sales_period_index');
    }


    //フリーチケット登録
    public function sales_period_free_create(sales_period_free $request){


        $tables02 = new table02;
        $tables07 = new table07;

        DB::beginTransaction();
        try{
            //tables02
            //事業者IDID
            $tables02->biz_id=1;
            //商品番号
            $tables02->ticket_code=$request->ticket_code;
            //販売期間 開始日時と時間　." ". は間に空白を入れないと　書式があわなくなるため 2021-10-11 10:10:10 の中心空白部分
            $tables02->sales_interval_start=$request->sales_interval_start_date." ".$request->sales_interval_start_times;
            $tables02->sales_interval_end=$request->sales_interval_end_date." ".$request->sales_interval_end_times;


            //商品番号（ticket_code)＋数字（１から順番につけていく）　tables07でも同様なので　同一名でいくようにしよう！
            //ticket_code%で数をカウント　複数なければ０　複数あればsales_idの最大値を+1して削除してもsales_idは重複しないようにする。
            if(DB::table('tables02')
                ->where('ticket_code','=',$request->ticket_code)
                //->where('ticket_code','like',"$request->ticket_code"."%")
                ->get()->count()==0){
                //同じticket_codeがなければsales_idは１
                $tables02->sales_id=$tables07->sales_id=1;
            }else{
                //同じticket_codeがあれば、sales_idは割り振られているのでmaxをとって＋１していれる
                $tables02->sales_id=$tables07->sales_id=DB::table('tables02')->max('sales_id')+1;
            }

            //tables02保存
            $tables02->save();

            //tables07
            //事業者ID
            $tables07->biz_id=1;//固定
            //商品番号
            $tables07->ticket_code=$request->ticket_code;

            //チケット利用可能日時（開始）
            $tables07->ticket_interval_start=$request->ticket_interval_start;
            //チケット利用可能日時（終了）
            $tables07->ticket_interval_end=$request->ticket_interval_end;
            //チケット有効日数
            $tables07->ticket_num=$request->ticket_num;
            //チケット有効日数　フリーであれば　０固定
            $tables07->ticket_days=0;

            //チケット販売枚数
            $tables07->ticket_num=$request->ticket_num;
            //チケット最小購入枚数
            $tables07->ticket_min_num=$request->ticket_min_num;
            //チケット最大購入枚数
            $tables07->ticket_max_num=$request->ticket_max_num;
            $tables07->save();

            //変更を確定させる まだ曖昧な部分があるので　コミットはまだしない　２０２１年１０月１０日１８：５７
            DB::commit();               //処理を実行する
        }catch(Exception $exception){    //catch()で例外クラスを指定する　Exceptionはphpの例外クラス
            //データ操作を巻き戻す
            DB::RollBack();             //処理を戻す
                    throw $exception;           //例外を投げる（例外を知らせる）例外メッセージの取得はExceptionのgetMessage();
        }
        return redirect('sales_period_index');
    }


    //指定チケット登録
    public function sales_period_specialized_create(sales_period_specialized $request){


        $tables02 = new table02;
        $tables07 = new table07;

        DB::beginTransaction();
        try{
            //tables02
            //事業者IDID
            $tables02->biz_id=1;
            //商品番号
            $tables02->ticket_code=$request->ticket_code;
            //販売期間 開始日時と時間　." ". は間に空白を入れないと　書式があわなくなるため 2021-10-11 10:10:10 の中心空白部分
            $tables02->sales_interval_start=$request->sales_interval_start_date." ".$request->sales_interval_start_times;
            $tables02->sales_interval_end=$request->sales_interval_end_date." ".$request->sales_interval_end_times;


            //商品番号（ticket_code)＋数字（１から順番につけていく）　tables07でも同様なので　同一名でいくようにしよう！
            //ticket_code%で数をカウント　複数なければ０　複数あればsales_idの最大値を+1して削除してもsales_idは重複しないようにする。
            if(DB::table('tables02')
                ->where('ticket_code','=',$request->ticket_code)
                //->where('ticket_code','like',"$request->ticket_code"."%")
                ->get()->count()==0){
                //同じticket_codeがなければsales_idは１
                $tables02->sales_id=$tables07->sales_id=1;
            }else{
                //同じticket_codeがあれば、sales_idは割り振られているのでmaxをとって＋１していれる
                $tables02->sales_id=$tables07->sales_id=DB::table('tables02')->max('sales_id')+1;
            }

            //tables02保存
            $tables02->save();

            //tables07
            //事業者ID
            $tables07->biz_id=1;//固定
            //商品番号
            $tables07->ticket_code=$request->ticket_code;

            //指定チケット
            //チケット利用可能日時（開始） 指定チケットの場合は現在時刻をいれる
            $tables07->ticket_interval_start=Carbon::now()->toDateTimeString();//Carbon::now()静的メソッド呼び出し　carbon 現在日時
            //チケット利用可能日時（終了）　carbon::now()+$request->ticket_interval　がはいる
            $tables07->ticket_interval_end=Carbon::now()->addday($request->ticket_interval)->toDateTimeString();    //toDateTimeString()がフォーマット変換
            //チケット有効日数　指定の場合　有効期限を格納
            $tables07->ticket_days=$request->ticket_interval;
            //チケット販売枚数
            $tables07->ticket_num=$request->ticket_num;
            //チケット最小購入枚数
            $tables07->ticket_min_num=$request->ticket_min_num;
            //チケット最大購入枚数
            $tables07->ticket_max_num=$request->ticket_max_num;
            $tables07->save();


            //変更を確定させる まだ曖昧な部分があるので　コミットはまだしない　２０２１年１０月１０日１８：５７
            DB::commit();               //処理を実行する
        }catch(Exception $exception){    //catch()で例外クラスを指定する　Exceptionはphpの例外クラス
            //データ操作を巻き戻す
            DB::RollBack();             //処理を戻す
                    throw $exception;           //例外を投げる（例外を知らせる）例外メッセージの取得はExceptionのgetMessage();
        }
        return redirect('sales_period_index');
    }






    //販売期間登録画面
    //validateを使用する際　Requestのデータは引き継げない
    public function sales_period_register($ticket_name){
        $table=DB::table('tables01')->join('tables06','tables01.ticket_code','=','tables06.ticket_code')
        ->where('ticket_name',$ticket_name)->first();

        return view("sales_period_register",compact('table'));
    }


    //編集画面　販売期間 2021/10/16
    public function update_sales_period($ticket_code,$sales_id){
        $table=DB::table('tables07')
        ->join('tables02',function($join){  //join複数条件
            $join->on('tables07.sales_id','=','tables02.sales_id')
            ->on('tables07.ticket_code','=','tables02.ticket_code');})
        ->leftjoin('tables01','tables07.ticket_code','=','tables01.ticket_code')
        ->where('tables02.sales_id',"=",$sales_id)
        ->where('tables02.ticket_code',"=",$ticket_code)
        ->first();

        return view("update_sales_period",compact('table'),compact('ticket_interval'));
    }


    //編集処理　販売期間 2021/10/16
    public function put_sales_period(REQUEST $request,$ticket_code,$sales_id){
        //チケットコード
        $tickets_kind=DB::table('tables01')
        ->where('ticket_code',"=",$ticket_code)
        ->first();

        //tables02編集
        $table=table02::where('sales_id',"=",$sales_id)
        ->where('ticket_code',"=",$ticket_code)
        ->first();

        //更新
        $table->sales_interval_start=$request->sales_interval_start_date." ".$request->sales_interval_start_times;
        $table->sales_interval_end=$request->sales_interval_end_date." ".$request->sales_interval_end_times;
        $table->save();

        //tables07編集
        $table=table07::where('sales_id',"=",$sales_id)
        ->where('ticket_code',"=",$ticket_code)
        ->first();

        //フリーチケットであれば
        if($tickets_kind->tickets_kind==1){
            //チケット利用可能日時（開始）
            $table->ticket_interval_start=$request->ticket_interval_start;
            //チケット利用可能日時（終了）
            $table->ticket_interval_end=$request->ticket_interval_end;
            //チケット有効日数
            $table->ticket_num=$request->ticket_num;
        }else{  //指定チケット
            //チケット利用可能日時（開始） 指定チケットの場合は現在時刻をいれる
            $table->ticket_interval_start=Carbon::now()->toDateTimeString();//Carbon::now()静的メソッド呼び出し　carbon 現在日時
            //チケット利用可能日時（終了）　carbon::now()+$request->ticket_interval　がはいる
            $table->ticket_interval_end=Carbon::now()->addday($request->ticket_interval)->toDateTimeString();    //toDateTimeString()がフォーマット変換
            //チケット有効日数　指定の場合　有効期限を格納
            $table->ticket_days=$request->ticket_interval;
        }
        //チケット販売枚数
        $table->ticket_num=$request->ticket_num;
        //チケット最小購入枚数
        $table->ticket_min_num=$request->ticket_min_num;
        //チケット最大購入枚数
        $table->ticket_max_num=$request->ticket_max_num;

        $table->save();


        return redirect('sales_period_index');
    }






    //table3の中身を表示する
    public function index3(){
        $table = table03::paginate(10);
        return view("index_table3",compact('table'));
    }


}
