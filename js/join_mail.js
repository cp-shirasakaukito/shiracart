/**
 * Created by ukito on 15/12/29.
 */
$(document).ready(function(){
    //jqueryValidationPluginを使用する（http://jqueryvalidation.org/documentation/）
    $("#join_mail").validate({
        onkeyup: false,
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 255
            },
            conf_email: {
                required: true,
                email: true,
                equalTo: "#email"
            }
        },
        messages: {
            email: {
                required: "メールアドレスは必ず入力して下さい",
                email: "メールアドレス形式で入力して下さい"
            },
            conf_email: {
                required: "確認用メールアドレスは必ず入力して下さい",
                email: "メールアドレス形式で入力して下さい",
                equalTo: "初めに入力したメールアドレスと異なるメールアドレスが入力されています"
            }
        }
    });
});