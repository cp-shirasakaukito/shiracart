<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/22
 * Time: 11:38
 */
include("core.php");
require_once("database/member.php");
require_once("database/ship_detail.php");
require_once("database/join_token.php");

//すでにログイン時にはトップページに遷移
if($_SESSION["member"]) {
    header("Location:".DOMAIN."/cookie_cart.php");
}

$db = new Database();
$connect = $db->connect();

//登録権限があるかを確認する
if($_GET["token"]) {
    $join_token = new Join_token();
    $match_token = $join_token->and_search_join_token($connect,array("join_token"=>$_GET["token"],"type"=>0));
    if($match_token){
        if(strtotime($match_token[0]["expire_date"]) > strtotime("now")){
            $member = new Member();
            $match_member = $member->and_search_member($connect,array("email_address"=>$match_token[0]["email"]));
            if($match_member){
                exit("こちらのメールアドレスはすでに登録済みです");
            }
        } else {
            exit("有効期限が切れています");
        }
    } else {
        header("Location:".DOMAIN."/cookie_cart.php");
    }
} else {
    header("Location:".DOMAIN."/cookie_cart.php");
}


if($_POST){
    //会員情報、配送情報を登録する
    $cod = md5(uniqid(rand(), true));

    $member_record = array(
        "cod" => $cod,
        "type" => "1",
        "name" => htmlspecialchars($_POST["name"],ENT_QUOTES,"UTF-8"),
        "email_address" => htmlspecialchars($_POST["email_address"],ENT_QUOTES,"UTF-8"),
        "password" => htmlspecialchars($_POST["password"],ENT_QUOTES,"UTF-8")
    );

    $db->begin_transaction($connect);

    $db_member = new Member();

    $member_id = $db_member->add_member($connect,$member_record);

    $ship_detail_record = array(
        "member_id" => $member_id,
        "zipcode" => htmlspecialchars($_POST["zipcode"],ENT_QUOTES,"UTF-8"),
        "prefecture" => htmlspecialchars($_POST["prefecture"],ENT_QUOTES,"UTF-8"),
        "address_1" => htmlspecialchars($_POST["address_1"],ENT_QUOTES,"UTF-8"),
        "address_2" => htmlspecialchars($_POST["address_2"],ENT_QUOTES,"UTF-8"),
        "name" => htmlspecialchars($_POST["name"],ENT_QUOTES,"UTF-8"),
    );

    $db_ship_detail = new Ship_detail();
    $ship_detail_id = $db_ship_detail->add_ship_detail($connect,$ship_detail_record);


    $err_msgs = array();
    if(!$member_id || !$ship_detail_id){
        if(!$member_id) {
            $err_msgs = $db_member->get_error_message_member($member_record);
        }
        if (!$ship_detail_id) {
            $err_msgs = array_merge($err_msgs, $db_ship_detail->get_error_message_ship_detail($ship_detail_record));
        }
        $db->rollback($connect);
    } else {
        $success = "登録が完了しました。";
        $db->commit($connect);
    }
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
        <form action="" method="post" id="register">
            <fieldset>
                <legend class="form_title">会員情報を入力して下さい</legend>
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
                    <label for="name">氏名</label>
                    <input type="name" name="name" id="name">
                </p>
                <p>
                    <label for="email_address">メールアドレス：</label>
                    <input type="hidden" name="email_address" id="email_address" value="<?php echo $match_token[0]["email"]; ?>">
                    <?php echo $match_token[0]["email"]; ?>
                </p>
                <p>
                    <label for="password">パスワード</label>
                    <input type="password" name="password" id="password">
                </p>
                <p>
                    <label for="conf_password">確認用パスワード</label>
                    <input type="password" name="conf_password" id="conf_password">
                </p>
                <p>
                    <label for="zipcode">郵便番号</label>
                    <input type="zipcode" name="zipcode" id="zipcode">
                </p>
                <p><label for="prefecture">都道府県</label>
                    <select name="prefecture" id="prefecture">
                        <option value="" selected>都道府県
                        <option value="1">北海道
                        <option value="2">青森県
                        <option value="3">岩手県
                        <option value="4">宮城県
                        <option value="5">秋田県
                        <option value="6">山形県
                        <option value="7">福島県
                        <option value="8">茨城県
                        <option value="9">栃木県
                        <option value="10">群馬県
                        <option value="11">埼玉県
                        <option value="12">千葉県
                        <option value="13">東京都
                        <option value="14">神奈川県
                        <option value="15">新潟県
                        <option value="16">富山県
                        <option value="17">石川県
                        <option value="18">福井県
                        <option value="19">山梨県
                        <option value="20">長野県
                        <option value="21">岐阜県
                        <option value="22">静岡県
                        <option value="23">愛知県
                        <option value="24">三重県
                        <option value="25">滋賀県
                        <option value="26">京都府
                        <option value="27">大阪府
                        <option value="28">兵庫県
                        <option value="29">奈良県
                        <option value="30">和歌山県
                        <option value="31">鳥取県
                        <option value="32">島根県
                        <option value="33">岡山県
                        <option value="34">広島県
                        <option value="35">山口県
                        <option value="36">徳島県
                        <option value="37">香川県
                        <option value="38">愛媛県
                        <option value="39">高知県
                        <option value="40">福岡県
                        <option value="41">佐賀県
                        <option value="42">長崎県
                        <option value="43">熊本県
                        <option value="44">大分県
                        <option value="45">宮崎県
                        <option value="46">鹿児島県
                        <option value="47">沖縄県
                    </select>
                </p>
                <p>
                    <label for="address_1">住所１</label>
                    <input type="text" name="address_1" id="address_1">
                </p>
                <p>
                    <label for="address_2">住所２</label>
                    <input type="text" name="address_2" id="address_2">
                </p>
                <p>
                    <input type="submit" value="送信">
                </p>
            </fieldset>
        </form>
    </div>
</body>
</html>
