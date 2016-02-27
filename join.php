<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/12/20
 * Time: 15:15
 */
session_start();
include("core.php");
require_once("database/member.php");
require_once("database/join_token.php");


//すでにログイン時にはトップページに遷移
if($_SESSION["member"]) {
    header("Location:".DOMAIN."/cookie_cart.php");
}

//トークンの有効期限
$expire_span = 24*60*60;

if(isset($_POST["email"])){
    if($_POST["email"] === $_POST["conf_email"] && $_POST["email"]!==""){
        //フォーム内容の有効性チェックは行わない。jsでおこなうぞ！
        //join_tokenテーブルに追加するレコードを発行
        $token = sha1(uniqid(rand(),true));
        $token_record = array(
            "email" => $_POST["email"],
            "join_token" => $token,
            "type" => 0,
            "expire_date" => date("Y-m-d H:i:s", time() + $expire_span),
        );
        //トークンの登録
        $jt = new Join_token();
        $connect = $jt->connect();
        if($jt->add_join_token($connect,$token_record)){
            //メールを送信
            $register_url = DOMAIN."/register.php";
            $to = $_POST["email"];
            $subject = CART_NAME."　本会員手続きのご案内";
            $body = CART_NAME."会員登録申込者の皆様へ\r\r".
                    "このメールは、".CART_NAME."より、会員登録の申込をされた方に送信しております。\r\r".
                    "下記のURLをクリックして、申込手続きを継続してください。\r" .
                    $register_url . "?token=" . $token . "\r".
                    "なお、上記のURLはメールアドレスの登録から24時間経過すると使用できなくなりますので、ご注意下さい。\r".
                    "メールアドレス登録より24時間以上経過してしまった場合は、再度新規申込からメールアドレスの登録をお願いいたします。";
            if(mb_send_mail($to, $subject, $body)){
                header("Location:".DOMAIN."/register_mail.php");
            } else {
                echo "メールの送信に失敗orz";
            }
            //完了ページにリダイレクト
        } else {
            echo "エラーが生じました。管理者に問い合わせて下さい";
        }

    } else {
        $err_msg = "メールアドレスが一致しません。";
    }
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>会員登録</title>
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/join_mail.js"></script>
    <link rel=stylesheet type="text/css" href="css/core.css">
</head>
<body>
<?php include("global_menu.php"); ?>
<div id="contents" class="container">
    <form action="" method="post" id="join_mail" class="form0">
        <fieldset>
            <legend class="form_title">会員登録メール送信フォーム</legend>
            <?php
            if($err_msg) {
                echo '<p class="error">'.$err_msg.'</p>';
            }
            ?>
            <p>
                <label for="email">メールアドレス</label>
                <input type="email" name="email" id="email">
            </p>
            <p>
                <label for="conf_email">確認用メールアドレス</label>
                <input type="conf_email" name="conf_email" id="conf_email">
            </p>
            <p>
                <input type="submit" value="送信">
            </p>
        </fieldset>
    </form>
</div>


</body>
</html>


