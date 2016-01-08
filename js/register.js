/**
 * Created by ukito on 16/01/03.
 */
$(document).ready(function(){
    //jqueryValidationPluginを使用する（http://jqueryvalidation.org/documentation/）
    $("#register").validate({
        onkeyup: false,
        rules: {
            name: {
                required: true,
                rangelength: [1,255]
            },
            password: {
                required: true,
                alphanumericsymbol: true,
                rangelength: [5,255]
            },
            conf_password: {
                required: true,
                rangelength: [5,255],
                equalTo: "#password"
            },
            zipcode: {
                required: true,
                digits: true,
                rangelength: [7,7]
            },
            prefecture: {
                required: true,
                digits: true,
                rangelength:[1,2]
            },
            address_1: {
                required: true,
                rangelength: [1,255]
            },
            address_2: {
                required: true,
                rangelength: [1,255]
            }
        },
        messages: {
            name: {
                required: "氏名は必ず入力して下さい",
                rangelength: "255文字以内で入力して下さい"
            },
            password: {
                required: "パスワードは必ず入力して下さい",
                alphanumericsymbol: "半角英数で入力して下さい",
                rangelength: "5文字以上255文字以内で入力して下さい"
            },
            conf_password: {
                required: "確認用パスワードは必ず入力して下さい",
                rangelength: "5文字以上255文字以内で入力して下さい",
                equalTo: "入力されたパスワードと異なります"
            },
            zipcode: {
                required: "郵便番号は必ず入力して下さい",
                digits: "半角数字で入力して下さい",
                rangelength: "-（ハイフン）抜きの7桁で入力して下さい"
            },
            prefecture: {
                required: "都道府県は必ず選択して下さい",
                digits: "ドロップダウンボックスから選択して下さい",
                range: "ドロップダウンボックスから選択して下さい"
            },
            address_1: {
                required: "住所１は必ず入力して下さい",
                rangelength: "255文字以内で入力して下さい"
            },
            address_2: {
                required: "住所２は必ず入力して下さい",
                rangelength: "255文字以内で入力して下さい"
            }
        }
    });
});