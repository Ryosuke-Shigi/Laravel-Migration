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
        let m_Value={{ $Value }};
        document.write("<p>結果</p>");
        document.write("1から"+m_Value+"の合計は<br><br>");
        document.write((m_Value*(m_Value+1))/2);
        document.write("<br>");
    </script>

    <form name="Answer2" action="Top">   <!-- action="/"" でも可 -->
        <input type="button" name="Send" id="id_Send" value="戻る">
    </form>

    <script>
    let sendBtn= document.getElementById("id_Send");
    sendBtn.addEventListener("click",function(){
        document.Answer2.submit();
    });
    </script>

</body>
</html>
