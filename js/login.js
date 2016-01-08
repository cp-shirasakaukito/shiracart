/**
 * Created by ukito on 16/01/03.
 */
$(document).ready(function(){
    //jqueryValidationPluginを使用する（http://jqueryvalidation.org/documentation/）
    $("#login").validate({
        onkeyup: false,
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                alphanumericsymbol: true,
                rangelength: [5,255]
            }
        },
        messages: {
            email: {
                required: "メールアドレスは必ず入力して下さい",
                email: "メールアドレス形式で入力して下さい"
            },
            password: {
                required: "パスワードは必ず入力して下さい",
                alphanumericsymbol: "半角英数で入力して下さい",
                rangelength: "5文字以上255文字以内で入力して下さい"
            }
        }
    });
});