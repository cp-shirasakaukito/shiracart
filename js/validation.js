/**
 * Created by ukito on 15/12/27.
 */
$(document).ready(function() {
    //validationの対象となるフォーム
    var join_mail_submit = $("#join_mail_submit");


    join_mail_submit.bind("submit", function (e) {
        //イベント配下のネーム属性から取ってきたかったが断念
        //var email = e.target.find("[name=email]");
        var email = $("#join_mail_submit [name=email]");
        var confirm_email = $("#join_mail_submit [name=confirm_email]");

        var count_error = 0;
        var error_message = "";
        //メールアドレスのチェック
        if (!require_check(email.val())) {
            error_ = $("<li>メールアドレスを入力して下さい</li>",{
                "class": "error form0"
            });
            error_tag.insertAfter(email.parent());
            count_error++;
        } else if (!email_check(email.val())) {
            error_tag = $("<li>メールアドレス形式で入力して下さい</li>",{
                "class": "error form0"
            });
            error_tag.insertAfter(email.parent());
            count_error++;
        }

        //確認用メールアドレスのチェック
        if (!require_check(confirm_email.val())) {
            error_tag = $("<li>メールアドレスを入力して下さい</li>",{
                "class": "error form0"
            });
            error_tag.insertAfter(confirm_email.parent());
            count_error++;
        } else if (!email_check(confirm_email.val())) {
            error_tag = $("<li>メールアドレス形式で入力して下さい</li>",{
                "class": "error form0"
            });
            error_tag.insertAfter(confirm_email.parent());
            count_error++;
        } else if (email !== confirm_email){
            error_tag = $("<li>メールアドレス形式で入力して下さい</li>",{
                "class": "error form0"
            });
            error_tag.insertAfter(confirm_email.parent());
            count_error++;
        }


        //エラー結果に応じて返り値を決定
        if (count_error > 0) {
            return false;
        } else {
            return true;
        }
    });
});

//それぞれのバリデーションルールを作成する
//jsは関数のスコープがないのかいな？？

/*
* 必須チェック
* */
function require_check(target){
    if (target !== ""){
        return true;
    } else {
        return false;
    }
}
/*
* email形式チェック
* */
function email_check(target){
    var email_format = new RegExp();
    if (target.match(/^[\w\+\.]+@[\w\.-]+\.\w{2,}$/)) {
        return true;
    } else {
        return false;
    }
}