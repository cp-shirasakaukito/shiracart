<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 16/01/06
 * Time: 23:37
 */
include("core.php");
require_once("database/member.php");
require_once("database/join_token.php");

//すでにログイン時にはトップページに遷移
if($_SESSION["member"]) {
    header("Location:".DOMAIN."/cookie_cart.php");
}

//トークンの有効期限
$expire_span = 24*60*60;

if($_POST["email"]){
    $db = new Database();
    $connect = $db->connect();
    $member = new Member();
    if($member->and_search_member($connect,array("email_address"=>$_POST["email"]))){
        $token = sha1(uniqid(rand(),true));
        $token_record = array(
            "email" => $_POST["email"],
            "join_token" => $token,
            "type" => 1,
            "expire_date" => date("Y-m-d H:i:s", time() + $expire_span),
        );
        $join_token = new Join_token();

        if($join_token->add_join_token($connect,$token_record)){
            $reset_url = DOMAIN."/reset_pass.php";
            $to = $_POST["email"];
            $subject = CART_NAME."のパスワードリセット";
            $message =  CART_NAME."会員の皆様へ\r\r".
                "このメールは、".CART_NAME."より、パスワードリセットの申込をされた方に送信しております。\r\r".
                "下記のURLをクリックして、申込手続きを継続してください。\r" .
                $reset_url . "?token=" . $token . "\r".
                "なお、上記のURLはメールアドレスの登録から24時間経過すると使用できなくなりますので、ご注意下さい。\r".
                "メールアドレス登録より24時間以上経過してしまった場合は、再度新規申込からメールアドレスの登録をお願いいたします。";
            if($a = mb_send_mail($to, $subject, $message)){
                $success_msg = "登録されたメールアドレスにメールを送信しました";
            } else {
                $err_msg = "メールの送信に失敗いたしました。お問い合わせ下さい";
            }
        }
    } else {
        $err_msg = "メールアドレスが使用されていません。";
    }
}

?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>パスワードリセット</title>
    <link rel=stylesheet type="text/css" href="css/core.css">
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/reset_pass_mail.js"></script>
</head>
<body>
<?php include("global_menu.php"); ?>
<div id="contents" class="container">
    <form action="" method="post" id="reset_pass_mail" class="form0">
        <fieldset>
            <legend class="form_title">パスワードリセット用のメールを送信します。</legend>
            <?php
            if($err_msg) {
                echo '<p class="error">'.$err_msg.'</p>';
            }
            if($success_msg) {
                echo '<p class="success">'.$success_msg.'</p>';
            }
            ?>
            <p>
                <label for="email">登録したメールアドレスを入力して下さい</label>
                <input type="email" name="email" id="email">
            </p>
            <p>
                <input type="submit" value="送信">
            </p>
        </fieldset>
    </form>
</div>
</body>
</html>
