<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset("css/homework.css")}}">
    <title>TOP</title>
</head>
<body>


    <!-- 点数評価 -->

    <p>点数（１～１００）を入力してください！！</p>
    <form name="work1" method="POST" action="Answer">
        @csrf
        <input type="text" name="Score" placeholder="１～１００を入力してください。" autocomplete="OFF" id="idScore">
        <input type ="button" name="Send" id="id_Send" value="送信">
    </form>


    <!-- １～nの和 -->

    <p>数字を入力してください（入力して数字までの合計値を表示します。)</p>
    <form name="work2" method="POST" action="Answer2">
        @csrf
        <input type="text" name="Value" placeholder="数字を入力してください。" autocomplete="OFF" id="idScore2">
        <input type ="button" name="Send2" id="id_Send2" value="送信">
    </form>

<!--ID　有無の管理-->

    <p>IDを入力してください</p>
    <form name="work3" method="POST" action="Answer3">
        @csrf
        <input type="text" name="Id" placeholder="数字を入力してください。" autocomplete="OFF" id="idScore3">
        <input type ="button" name="Send3" id="id_Send3" value="送信">
    </form>

    <br><br>

<!--タクシーｃｓｖ-->
    <p>料金表ファイルを選択してください（CSV）</p>
    <form name = "Taxi" method="POST" action="Taxifare" enctype="multipart/form-data"><!--enctype"multipart/form-data必須！！-->
        @csrf
        <input type="file" name="Taxicsv" accept=".csv">
        <input type="button" name="Send4" id="id_Send4" value="送信">
    </form>





</body>




<!--サブミット管理　最終的にまとめます-->
<script>
let sendBtn = document.getElementById("id_Send");
let sendBtn2 = document.getElementById("id_Send2");
let sendBtn3 = document.getElementById("id_Send3");
let sendBtn4 = document.getElementById("id_Send4"); //タクシー用の送信ボタン
sendBtn.addEventListener("click",function(){    //クリックされた時 callback関数で処理を書く
    document.work1.submit(); //送信処理(submit)を行う　これを使えば普通のボタンでも送信処理ができる
});
sendBtn2.addEventListener("click",function(){    //クリックされた時 callback関数で処理を書く
    document.work2.submit(); //送信処理(submit)を行う　これを使えば普通のボタンでも送信処理ができる
});
sendBtn3.addEventListener("click",function(){    //クリックされた時 callback関数で処理を書く
    document.work3.submit(); //送信処理(submit)を行う　これを使えば普通のボタンでも送信処理ができる
});
sendBtn4.addEventListener("click",function(){
    document.Taxi.submit();
});
</script>

</html>
