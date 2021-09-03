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
        let m_Score={{ $Score }};
        document.write("<p>結果</p>");
        document.write("点数は"+m_Score+"点<br><br>");

        if(m_Score>=0 && m_Score<50){
            document.write("がんばりましょう。");
        }else if(m_Score>=50 && m_Score<80){
            document.write("よくできました。");
        }else if(m_Score>=80 && m_Score<=100){
            document.write("大変よくできました。");
        }else{
            document.write("０～１００を入力してください。<br>");
        }
        document.write("<br>");
    </script>

    <form name="answer" action="Top">   <!-- action="/"" でも可 -->
        <input type="button" name="Send" id="id_Send" value="戻る">
    </form>

    <script>
    let sendBtn= document.getElementById("id_Send");
    sendBtn.addEventListener("click",function(){
        document.answer.submit();
    });
    </script>

</body>
</html>
