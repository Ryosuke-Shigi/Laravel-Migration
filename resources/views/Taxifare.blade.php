<!DOCTYPE html>

<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset("css/taxistyle.css")}}">
    <title>答えは</title>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <table class="taxifare">    <!--　値とNOはビューまかせの作成　-->
                    <?php
                    for($i=0;$i<count($csv_data);$i++){
                        print("<tr>");
                        if($i!=0){
                            print("<td>$i</td>");
                        }
                        for($j=0;$j<count($csv_data[$i]);$j++){
                            print("<td>".$csv_data[$i][$j]."</td>");
                        }
                        print("</tr>");
                    }
                    ?>
            </table>
            <!--トップ画面に戻る-->
            <form class="formstyle" name="Back" action="Top">   <!-- action="/"" でも可 -->
                <input class="toTop" type="button" name="Send" id="id_Send" value="戻る">
            </form>
        </div>
    </div>
    <script>
    let sendBtn= document.getElementById("id_Send");
    sendBtn.addEventListener("click",function(){
        document.Back.submit();
    });
    </script>

</body>
</html>
