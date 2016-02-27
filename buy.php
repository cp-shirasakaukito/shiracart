<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/11
 * Time: 0:38
 */
include("core.php");
require_once("csrf.php");
require_once("./database/item.php");
require_once("./database/member.php");
require_once("./database/ship_detail.php");
require_once("./database/purchase_detail.php");
require_once("./database/purchase_log.php");
require_once("./database/creditcard_payment_log.php");

if(!$_SESSION["cart"]) {
    header("Location:".DOMAIN."/cookie_cart.php");
}
//csrf対策用チェック及びトークン発行用
$csrf = new Csrf();

//初期化
$db = new Database();
$connect = $db->connect();


//購入詳細を整理
//決済金額の計算＋購入明細登録のため
$item_obj = new Item();
$total_price = 0;
foreach ($_SESSION["cart"] as $key => $value){
    $item = $item_obj->and_search_item($connect,array("id"=>$value["cookie_id"]));
    $item[0]["subtotal"] = $item[0]["price"] * $value["number"];
    $total_price += $item[0]["subtotal"];
    $items[$key] = array(
        "item_id" => $item[0]["id"],
        "number" => $value["number"],
        "price" => $item[0]["price"],
        "subtotal" => $item[0]["subtotal"]
    );

}

//SSLでの決済はTLS1.1以上に対応するためにMAMPのPHPを再コンパイルする必要があり面倒なので断念orz
$url = "http://credit2.j-payment.co.jp/gateway/gateway.aspx";
if (isset($_POST["cn"])){
    if($csrf->check_csrf_token($_POST["token"])){

        $data = array(
            "aid" => "106904",
            "jb" => "CAPTURE",
            "rt" => "1",
            "cn" => $_POST["cn"],
            "ed" => $_POST["ed"],
            "fn" => $_POST["fn"],
            "ln" => $_POST["ln"],
            "em" => $_SESSION["member"]["email"],
            "pn" => $_POST["pn"],
            "am" => $_POST["price"],
            "tx" => 0,
            "sf" => 0
        );
        //決済システムとの通信
        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_POST,true);
        curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($data));
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);

        //決済システムとの通信失敗時のエラー処理を入れる必要あり

        //決済結果を整理
        $result = explode(",",$response);
        $result = [
            "gid" => $result[0],
            "rst" => $result[1],
            "ap" => $result[2],
            "ec" => $result[3],
            "god" => $result[4],
            "cod" => $result[5],
            "am" => $result[6],
            "tx" => $result[7],
            "sf" => $result[8],
            "ta" => $result[9],
            "id" => $result[10],
            "ps" => $result[11]
        ];

        $db->begin_transaction($connect);

        //購入履歴に格納する配送情報を取得する
        $ship_detail_obj = new Ship_detail();
        $ship_detail = $ship_detail_obj->and_search_ship_detail($connect, array("member_id"=>$_SESSION["member"]["id"]));


        //購入履歴をDBに保存する
        $purchase_log_rec = array(
            "member_id" => $_SESSION["member"]["id"],
            "payment_method" => 0,
            "result" => $result["rst"],
            "total_price" => $result["ta"],
            "subtotal_price" => $result["am"],
            "shipping_price" => $result["sf"],
            "tax" => $result["tx"],
            "zipcode" => $ship_detail[0]["zipcode"],
            "prefecture" => $ship_detail[0]["prefecture"],
            "address_1" => $ship_detail[0]["address_1"],
            "address_2" => $ship_detail[0]["address_2"],
            "name" => $ship_detail[0]["name"]
        );
        $purchase_log = new Purchase_log();
        if($purchase_log->is_valid_purchase_log($purchase_log_rec)){
            $purchase_log_id = $purchase_log->add_purchase_log($connect,$purchase_log_rec);
            if(!$purchase_log_id){
                $err_flg = 1;
                $err_msgs = "購入履歴登録エラー（要お問い合わせ）誤決済の可能性あり";
            }
        } else {
            $err_flg = 1;
            $err_msgs = "購入履歴登録エラーです。お問い合わせ下さい。誤決済の可能性あり";
        }


        //クレジットカード決済履歴をDBに保存する
        $card_pay_log_rec = array(
            "purchase_log_id" => $purchase_log_id,
            "gid" => $result["gid"],
            "total_price" => $result["ta"],
            "subtotal_price" => $result["am"],
            "shipping_price" => $result["sf"],
            "tax" => $result["tx"]
        );
        $card_pay_log = new Creditcard_payment_log();
        if($card_pay_log->is_valid_creditcard_payment_log($card_pay_log_rec)){
            if(!$card_pay_log->add_creditcard_payment_log($connect,$card_pay_log_rec)){
                $err_flg = 1;
                $err_msgs = "クレジットカード決済履歴登録エラー（要お問い合わせ）誤決済の可能性あり";
            }
        } else {
            $err_flg = 1;
            $err_msgs = "クレジットカード決済履歴登録エラーです。お問い合わせ下さい。誤決済の可能性あり";
        }

        //購入明細情報をDBに保存する
        $purchase_detail = new Purchase_detail();
        foreach($items as $key => $value) {
            $value["purchase_log_id"] = $purchase_log_id;
            $value["result"] = $result["rst"];
            if($purchase_detail->is_valid_purchase_detail($value)){
                if(!$purchase_detail->add_purchase_detail($connect,$value)) {
                    $err_flg = 1;
                    $err_msgs = "購入明細登録エラー（要お問い合わせ）誤決済の可能性あり";
                }
            } else {
                $err_flg = 1;
                $err_msgs = "購入明細登録エラーです。お問い合わせ下さい。誤決済の可能性あり";
            }
        }


        if($err_flg === 1){
            $db->rollback($connect);
        } else {
            $db->commit($connect);
            if ($result["rst"] === "1") {
                //セッションをリセット
                $success_flg = 1;
                $_SESSION["cart"] = array();
                header('location: thanks.php');
            } else {
                $err_msgs = "決済に失敗しました(".$result["ec"].")";
            }
        }
    } else {
        //CSRFのおそれがある場合の処理
        header("Location:".DOMAIN."/cookie_cart.php");
    }
}
//決済に失敗
//CSRF対策用トークンを発行
if($success_flg !== 1){
    $token = $csrf->generate_csrf_token();
}
?>



<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>クレジットカード情報入力ページ</title>
    <link rel=stylesheet type="text/css" href="css/core.css">
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/buy.js"></script>

</head>
<body>
<?php include("global_menu.php"); ?>
<div id="contents" class="container">
    <form action="" method="post" id="buy" class="form0">
        <legend class="form_title">決済情報を入力して下さい</legend>
        <?php
            if($err_msgs) {
                echo "<p class='error'>".$err_msgs."</p>";
            }
        ?>
        <p>
            決済金額：<?php echo $total_price."円"; ?>
        </p>
        <p>
            <label for="cn">クレジットカード番号</label>
            <input type="text" name="cn" id="cn" placeholder="4444333322221111">
        </p>
        <p>
            <label for="ed">有効期限</label>
            <input type="text" name="ed" id ="ed" value="" placeholder="YYMM">
        </p>
        <p>
            <label>お名前</label>
            <input type="text" name="ln" placeholder="性">
            <input type="text" name="fn" placeholder="名">
        <p>
            <label for="pn">電話番号</label>
            <input type="text" name="pn" id="pn">
        </p>
        <p>
            <input type="hidden" name="price" value="<?php echo $total_price; ?>">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
        </p>

        <p>
            <input type="submit" value="決済する">
        </p>
    </form>
</div>
</body>
</html>