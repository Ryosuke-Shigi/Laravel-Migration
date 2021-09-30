//ボタンを増やす処理　ID=itemaddがクリックされたらinputareaへ下の文を追加していく
$(function () {
    $('#itemadd').on('click', function() {
        $('#inputarea').append(
            '<div class="item_section"><div class="item_name">名称</div><input class="item_text" type="text" id="type_name01" name="type_name[]"></input></div>'
            ,'<div class="item_section"><div class="item_name">単価</div><input class="item_text" type="text" id="type_money01" name="type_money[]"></input></div>'
            ,'<div class="item_section"><div class="item_name">キャンセル料</div><input class="item_text" type="text" id="cancel_rate01" name="cancel_rate[]"></input></div>'
        );
    })
})
