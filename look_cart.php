<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/05
 * Time: 1:06
 */
include("core.php");
require_once(dirname(__FILE__)."/database/item.php");
$db = new Item();
$connect = $db->connect();
$items = $db->select_all_item($connect);
?>


<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel=stylesheet type="text/css" href="css/core.css">
</head>
<body>
<?php include("global_menu.php"); ?>
<div id="contents" class="container">
    <?php
    if($_SESSION['cart']){
        $total = 0;
        echo "<table>";
        echo "<tr><th>クッキーの名前</th><th>個数</th><th>単価</th><th>小計</th></tr>";
        foreach ($_SESSION["cart"] as $key => $value){
            $item = $db->and_search_item($connect,array("id"=>$value["cookie_id"]));
            $subtotal = $value['number'] * $item[0]["price"];
            echo "<tr><td>" . $item[0]["name"] . "</td><td>" . $value['number'] . "個</td><td>" . $item[0]["price"] . "円</td><td>" . $subtotal ."円</td></tr>";
            $total += $subtotal;
        }
        echo "</table>";
        echo "<form action='buy.php' method='post' >";
        echo "<input type='hidden' name='price' value='" . $total . "'>";
        if($total < 1000) {
            echo "<p>1,000円以上の購入が必要です。</p>";
        } else {
            if ($_SESSION["member"]){
                echo "<input type='submit' value='購入'>";
            } else {
                echo "<input type='button' onclick=\"location.href='login.php'\" value='ログインして購入' />";
                echo "<input type='button' onclick=\"location.href='register.php'\" value='会員登録をして購入' />";
            }
        }
    } else {
        echo "現在カートは空でございます。";
    }
    ?>
    <a href="./cookie_cart.php">商品選択画面に戻る</a>
    <a href="./reset_cart.php">カートを空にする</a>
</div>
</body>
</html>
