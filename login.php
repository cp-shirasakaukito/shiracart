<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/22
 * Time: 11:39
 */
include("core.php");
require_once("database/member.php");

//ログイン処理
if ($_POST){
    $db = new Database();
    $connect = $db->connect();
    $db_member = new Member();
    $conditions = array("email_address"=>$_POST["email"],"password"=>$_POST["password"]);
    if($login_info = $db_member->and_search_member($connect,$conditions)){
        $_SESSION["member"]["email"] = $_POST["email"];
        $_SESSION["member"]["password"] = $_POST["password"];
        $_SESSION["member"]["name"] = $login_info[0]["name"];
    } else {
        $err_msg = "メールアドレスもしくはパスワードが誤っております。";
    }
}


//ログイン時にはトップページに遷移
if($_SESSION["member"]) {
    header("Location:".DOMAIN."/cookie_cart.php");
}

?>


<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link rel=stylesheet type="text/css" href="css/core.css">
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/login.js"></script>
</head>
<body>
<?php include("global_menu.php"); ?>
    <div id="contents" class="container">
        <form action="" method="post" id="login" class="form0">
            <fieldset>
                <legend class="form_title">ログインフォーム</legend>
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
                    <label for="password">パスワード</label>
                    <input type="password" name="password" id="password">
                </p>
                <p>
                    <input type="submit" value="送信">
                </p>
            </fieldset>
        </form>
        <div>
            <a href="reset_pass_mail.php">パスワードをお忘れの方はこちら</a>
        </div>
    </div>
</body>
</html>
