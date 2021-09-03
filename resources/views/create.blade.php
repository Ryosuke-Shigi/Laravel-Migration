<!--createでリクエストがきて　postcontrollerから viewのここにとぶ-->
<!--フォームの表示　テキストボックス２つに送信ボタン-->
<form method ="POST" action="/posts"> <!--Restfullな作成： postリクエストでパラメータ送信することで新しいデータを作成する-->
    {{ csrf_field() }}  <!--CSRF（攻撃方法）に対処するための対策　CSRFトークンを作成し、フォームで入力されたパラメータを送信できるようにする-->
    <input type="text" name="title">
    <input type="text" name="content">
    <input type="submit">
</form>
