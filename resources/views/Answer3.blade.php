<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset("css/homework.css")}}">
    <title>答えは</title>
</head>
<body>
    <script>
        let m_Id={{ $Id }};
        let idArray = new Array(100,102,103,104,105);
        let flg=false;

        //判断
        for(let i in idArray){
            if(m_Id==idArray[i]){
                flg=true;
                break;
            }
        }
        //答え
        if(flg==true){
            document.write("<p>ID："+m_Id+"は登録されています</p>");
        }else{
            document.write("<p>ID："+m_Id+"は登録されていません</p>");
        }
    </script>

    <form name="Answer3" action="Top">   <!-- action="/"" でも可 -->
        <input type="button" name="Send" id="id_Send" value="戻る">
    </form>

    <script>
    let sendBtn= document.getElementById("id_Send");
    sendBtn.addEventListener("click",function(){
        document.Answer3.submit();
    });
    </script>

</body>
</html>
