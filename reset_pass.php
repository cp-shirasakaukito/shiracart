<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 16/01/08
 * Time: 0:15
 */
include("core.php");
require_once("database/member.php");
require_once("database/join_token.php");

//すでにログイン時にはトップページに遷移
if($_SESSION["member"]) {
    header("Location:".DOMAIN."/cookie_cart.php");
}

$db = new Database();
$connect = $db->connect();

if($_GET["token"]) {
    $join_token = new Join_token();
    $match_token = $join_token->and_search_join_token($connect,array("join_token"=>$_GET["token"],"type"=>1));
    if($match_token){
        if(strtotime($match_token[0]["expire_date"]) > strtotime("now")){
            $member = new Member();
            $match_member = $member->and_search_member($connect,array("email_address"=>$match_token[0]["email"]));
            if(!$match_member){
                exit("こちらのメールアドレスは登録されておりません");
            }
        } else {
            exit("有効期限が切れています");
        }
    } else {
        header("Location:".DOMAIN."/cookie_cart.php");
    }
} else {
    header("Location:" . DOMAIN . "/cookie_cart.php");
}

if($_POST["password"]){
    //パスワードのリセット処理を記述する2016/01/08は寝ますzzz
}

?>


<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>会員登録</title>
    <link rel=stylesheet type="text/css" href="css/core.css">
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/register.js"></script>
</head>
<body>
<?php include("global_menu.php"); ?>
<div id="contents" class="container">
    <form action="" method="post" id="reset_pass">
        <fieldset>
            <legend class="form_title">変更後のパスワードを入力して下さい</legend>
            <?php
            if (!empty($err_msgs)){
                foreach ($err_msgs as $value){
                    echo "<p class='error'>".$value."</p>";
                }
            }
            if ($success){
                echo "<section class='success'>".$success."</section>";
            }
            ?>
            <p>
                <label for="password">パスワード</label>
                <input type="password" name="password" id="password">
            </p>
            <p>
                <label for="conf_password">確認用パスワード</label>
                <input type="password" name="conf_password" id="conf_password">
            </p>
            <input type="hidden" value="<?php echo $match_token[0]["email"];?>">
            <p>
                <input type="submit" value="送信">
            </p>
        </fieldset>
    </form>
</div>
</body>
</html>