<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/22
 * Time: 11:38
 */
require_once("member.php");
require_once("ship_detail.php");
if($_POST){
    //ユニークなランダム文字列のcodを作成
    //※※※※※※※※※※※※※※※※会員登録だけ成功するケースが生じる。
    $cod = md5(uniqid(rand(), true));

    $record = array(
        "cod" => $cod,
        "type" => "1",
        "name" => htmlspecialchars($_POST["name"],ENT_QUOTES,"UTF-8"),
        "email_address" => htmlspecialchars($_POST["email_address"],ENT_QUOTES,"UTF-8"),
        "password" => htmlspecialchars($_POST["password"],ENT_QUOTES,"UTF-8")
    );
    $db_member = new Member();
    if($member_id = $db_member->add_member($record)) {
        $record = array(
            "member_id" => $member_id,
            "zipcode" => htmlspecialchars($_POST["zipcode"],ENT_QUOTES,"UTF-8"),
            "prefecture" => htmlspecialchars($_POST["prefecture"],ENT_QUOTES,"UTF-8"),
            "address_1" => htmlspecialchars($_POST["address_1"],ENT_QUOTES,"UTF-8"),
            "address_2" => htmlspecialchars($_POST["address_2"],ENT_QUOTES,"UTF-8"),
            "name" => htmlspecialchars($_POST["name"],ENT_QUOTES,"UTF-8"),
        );
        $db_ship_detail = new Ship_detail();
        if ($db_ship_detail->add_ship_detail($record)) {
            $success = "登録に成功しました。";
        }else {
            $error = $db_ship_detail->get_error_message_ship_detail($record);
        }
    } else {
        $error = $db_member->get_error_message_member($record);
    }



}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>会員登録</title>
</head>
<body>
<?php
if (!empty($error)){
    echo "<section class='error'>";
    foreach ($error as $value){
        echo "<p>".$value."</p>";
    }
    echo "</section>";
}
if ($success){
    echo "<section class='success'>".$success."</section>";
}
?>
    <div>
        <p>
            会員情報を入力して下さい。
        </p>
        <form action="register.php" method="post">
            <ul>
                <li>氏名<input type="text" name="name"></li>
                <li>メールアドレス<input type="text" name="email_address"></li>
                <li>パスワード<input type="password" name="password"></li>
                <li>郵便番号<input type="text" name="zipcode"></li>
                <li>都道府県
                    <select name="prefecture">
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
                </li>
                <li>住所１<input type="text" name="address_1"></li>
                <li>住所２<input type="text" name="address_2"></li>
                <li><input type="submit" value="送信"></li>
            </ul>
        </form>
    </div>
</body>
</html>
