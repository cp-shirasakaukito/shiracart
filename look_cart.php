<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/05
 * Time: 1:06
 */
require_once("database/item.php");
session_start();
$db = new Item();
$item = $db->select_all_item();
var_dump($item);
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<?php
if($_SESSION['cart']){
    $total = 0;
    echo "<table>";
    echo "<tr><th>クッキーの名前</th><th>個数</th><th>単価</th><th>小計</th></tr>";
    foreach ($_SESSION["cart"] as $key => $value){
        $subtotal = $value['number'] * $item[$value['cookie_id']]["price"];
        echo "<tr><td>" . $item[$value['cookie_id']]["name"] . "</td><td>" . $value['number'] . "個</td><td>" . $item[$value['cookie_id']]["price"] . "円</td><td>" . $subtotal ."円</td></tr>";
        $total += $subtotal;
    }
    echo "</table>";
    echo "<form action='buy.php' method='post' >";
    echo "<input type='hidden' name='price' value='" . $total . "'>";
    echo "<input type='submit' value='購入'>";
    echo "<input type='button' onclick=\"location.href='register.php'\" value='会員登録をして購入' />";
    //echo "<input type='button' onclick=\"location.href='buy.php'\" value='購入' />";
} else {
    echo "現在カートはからでございます。";
}
?>
<form action="./buy.php" method="post">

</form>
<a href="./cookie_cart.php">商品選択画面に戻る</a>
<a href="./reset_cart.php">カートを空にする</a>
</body>
</html>
