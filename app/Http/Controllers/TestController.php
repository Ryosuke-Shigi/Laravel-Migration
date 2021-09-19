<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//ｃｓｖファイル取り扱い
use SplFileObject;



class TestController extends Controller
{
    //課題作成用

    public function Top(){
        return view("Kadai");
    }
    public function Answer(Request $request){
        $Score=$request->Score;
        dump($Score);
        return view("Answer",compact("Score"));
    }
    public function Answer2(Request $request){
        $Value=$request->Value;
        dump($Value);
        return view("Answer2",compact("Value"));
    }
    public function Answer3(Request $request){
        $Id=$request->Id;
        return view("Answer3",compact("Id"));
    }



    //タクシー料金
    public function Taxifare(Request $request){
        //setlocale(LC_ALL,"ja_JP.UTF-8");    //windows環境　php7になってからは動かなくなった　参考：https://mgng.mugbum.info/1014
        /*Windows　これがないと正確に　カンマ区切り　ができない*/
        /*PHP7の仕様　エンコードの問題？？*/
        /*これがないと　日本語が含まれた時にちゃんと区切ることができない */
        setLocale(LC_ALL, 'English_United States.1252');    //　＜重要！！＞　これがないと　，　でうまく区切れない！！windows!



        $uploadfile=$request->file("Taxicsv");                  //ファイル名
       // dump($uploadfile);
        $file_path=$request->file("Taxicsv")->path($uploadfile);//フルパスの取得
       // dump($file_path);
        $file=new SplFileObject($file_path);    //フルパスを入れる　ファイル操作オブジェクト作成 fopen みたいなもの？


/*ファイル名の取り方　これも可能か試し*/
/*      $filepath2=$_FILES["Taxicsv"]["tmp_name"];  //試し  成功
        $file2 = new SplFileObject($filepath2);     //試し  成功 */


        //CSV取り込みの設定
        $file->setFlags(SplFileObject::READ_CSV);
        //空の配列宣言
        $csv_data[] = array("NO","車両名","車種","距離","待ち時間","料金");
        //配列を追加しながら最後まで回す。(最初のカラム名は飛ばす　関数に値がはいらないようにするため)
        $forcount=0;//CSVの１行目を読まない　純粋にデータだけを取り出す
        foreach($file as $row){
            if($forcount!=0){
                $pay=$this->money_distance($row[1],$row[2])+$this->money_waittime($row[1],$row[3]);
                array_push($row,$pay);
                $csv_data[]=$row;
            }
            $forcount++;
        }

        return view("Taxifare",compact("csv_data"));
    }

   //運賃を返す
   private function money_distance($Size,$Distance){
        $Distance=($Distance*1000)-1500;  //距離　mに変換して、先に１５００引いておく
        $fare_Starting=0;       //初回運賃
        $fare_Additional=0;     //加算運賃
        $distance_Additional=0; //加算距離
        //車体サイズによる金額設定
        if($Size=="普通"){
            $fare_Starting=690;
            $fare_Additional=80;
            $distance_Additional=272;
        }else{
            $fare_Starting=760;
            $fare_Additional=90;
            $distance_Additional=231;
        }
        //dump($fare_Starting+(ceil($Distance/$distance_Additional)*$fare_Additional));
        //運賃
        if($Distance<0){          //１５００引いてマイナスなら　初回運賃を返す
            return $fare_Starting;
        }else{                      //まだ距離があるなら 初回運賃＋　（距離/加算距離）＊加算運賃（切り上げ分もプラス）
            return $fare_Starting+(ceil($Distance/$distance_Additional)*$fare_Additional);
        }
    }
//待ち時間の金額を返す
    private function money_waittime($Size,$Time){
        $add_pay=0;//待ち時間による加算額（サイズによって変わる）
        $add_waittime=0;   //待ち時間　普通なら１００秒　大型なら８５秒

        if($Size=="普通"){
            $add_pay=80;
            $add_waittime=100;
        }else{
            $add_pay=90;
            $add_waittime=85;
        }
        //分を秒にしてから時間ごとで切り上げの割り算　その結果を加算額でかけて返す。
        //dump(ceil($Time*60/$add_waittime)*$add_pay);

        return ceil($Time*60/$add_waittime)*$add_pay;
    }

    //チケット登録画面
    public function index_ticket(Request $request){
        return view('index_ticket');
    }
    //新規登録画面
    public function create_ticket(Request $request){
        return view('create_ticket');
    }



}



?>
