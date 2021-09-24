<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Collection;

use App\Models\table01;
use App\Models\table03;
use App\Models\table04;
use App\Models\table05;
use App\Models\table06;

class TicketController extends Controller
{
    //一覧表示
    public function index(Request $request){
        $table=DB::table('tables05')
        ->join('tables01','tables01.ticket_code','=','tables05.ticket_code')
        ->select(['tables01.id','tables01.biz_id','tables01.ticket_code','tables01.ticket_name','tables05.type_name','tables05.type_money'])
        ->orderBy('ticket_name')->orderBy('ticket_code');    //ソート大事　とても大事
        //レコード件数取得
        $recordnum=4;
        $table=$table->paginate($recordnum);//paginateはページ切り替え時の度にここを通るようです

        //同一チケット名で、金額が複数種類入力されている場合　ひとつにまとめて一方を消す
        //一つずつ確認をとる
        for($i=0;$i<$recordnum-1;$i++){
            if(isset($table[$i])){
                $samename=0;    //同名件数
                $name = array($table[$i]->type_name);
                $money = array($table[$i]->type_money);
    /*             $table[$i]->type_name=array($table[$i]->type_name);
                $table[$i]->type_money=array($table[$i]->type_money); */
                for($j=$i+1;$j<$recordnum;$j++){
                    if(isset($table[$j]->ticket_name)){
                        if($table[$i]->ticket_name == $table[$j]->ticket_name){
                            array_push($name,$table[$j]->type_name);
                            array_push($money,$table[$j]->type_money);
                            $table[$j]->id=0; //idを０にしてbladeで表示しないようにしている　削除できたらしたい
                            unset($table[$j]);//インデックス情報は変わらないのを逆手にとってどうにかできないかなと思っている
                            $samename+=1;
                        }
                    }else{
                        break;
                    }
                    if($samename!=0){
                        $table[$i]->type_name=$name;
                        $table[$i]->type_money=$money;
                    }
                }
                $i+=$samename;
    /*             if(isset($table[$i+1])){
                    if($table[$i]->ticket_name == $table[$i+1]->ticket_name){
                        $table[$i]->type_name = array(0=>$table[$i]->type_name,1=>$table[$i+1]->type_name);
                        $table[$i]->type_money = array(0=>$table[$i]->type_money,1=>$table[$i+1]->type_money);
                        $table[$i+1]->id=0;
                        //unset($table[$i+1]);
                        $i+=1;
                    }
                } */
            }else{
                break;
            }
        }
        dump($table);


        return view("index_ticket",compact('table'));
    }

    //登録画面
    public function store(){
        return view("create_ticket");
    }
    //削除画面
    public function delete($id){
        //dump($id);
        return redirect('index3');
    }

    //登録作業
    public function create(Request $request){

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
                $tables04->save();
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
            if(isset($request->type_money01)){
                //事業者ID
                $tables05->biz_id=1;
                //商品番号
                $tables05->ticket_code=$request->ticket_code;
                //キャンセル区分
                $tables05->cancel_type=1;

                $tables05->type_id=1;                           //券種ID
                $tables05->type_name=$request->type_name01;     //単価名称
                $tables05->cancel_rate=$request->cancel_rate01; //キャンセル料単価
                $tables05->type_money=$request->type_money01;  //単価！
                //tables05に値を入れる
                $tables05->save();
                $tables05 = new table05;
            }
            //券種ID２
            if(isset($request->type_money02)){
                //$tables05->create();
                //事業者ID
                $tables05->biz_id=1;
                //商品番号
                $tables05->ticket_code=$request->ticket_code;
                //キャンセル区分
                $tables05->cancel_type=1;

                $tables05->type_id=2;                           //券種ID
                $tables05->type_name=$request->type_name02;     //単価名称
                $tables05->cancel_rate=$request->cancel_rate02; //キャンセル料単価
                $tables05->type_money=$request->type_money02;  //単価！
                //tables05に値を入れる
                $tables05->save();
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

    //table3の中身を表示する
    public function index3(){
        $table = table03::paginate(10);
        return view("index_table3",compact('table'));
    }



}
