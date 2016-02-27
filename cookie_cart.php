<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/04
 * Time: 0:57
 */
include("core.php");
require_once(dirname(__FILE__)."/database/item.php");


//商品一覧を取得
$db = new Item();
$link = $db->connect();
$item = $db->select_all_item($link);
$cart = $_SESSION["cart"];

if($_POST) {
    if($cart){
        $number_of_items = count($cart);
        for ($i = 0; $i < $number_of_items; $i++) {
            if ($cart[$i]["cookie_id"] === htmlspecialchars($_POST["cookie_id"],ENT_QUOTES,"utf-8")) {
                //同じ商品があったらnumberを増やす
                $cart[$i]["number"] += $_POST["number"];//htmlspecialcharsで無効化するとstringとして取られてしまうため。どうしたらよい？
                $_SESSION["cart"][$i]["number"] = $cart[$i]["number"];
                break;
            } elseif ($i === $number_of_items - 1) {
                //同じ商品がなければ新しい商品枠を作る
                $_SESSION["cart"][$number_of_items]["cookie_id"] = $_POST["cookie_id"];
                $_SESSION["cart"][$number_of_items]["number"] = htmlspecialchars($_POST["number"],ENT_QUOTES,"utf-8");
            }
        }
    } else {
        //カートに中身がなければ、
        $_SESSION["cart"][0]["cookie_id"] = $_POST["cookie_id"];
        $_SESSION["cart"][0]["number"] = htmlspecialchars($_POST["number"],ENT_QUOTES,"utf-8");
    }
    header("location: look_cart.php");
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>クッキーの店</title>
    <link rel=stylesheet type="text/css" href="css/core.css">
</head>
<body>
<?php include("global_menu.php"); ?>
<?php
if ($_POST) {
    echo "<div class='notice'>カートに" . $item[$_POST['cookie_id']]['name'] . "を" . $_POST['number'] . "個追加しました</div>";
}
foreach($item as $key => $value) {
    if($value["delete_flg"] == 0){
        //var_dump($value);
        echo "<section>";
            echo $value["name"] . ":" . $value["price"] . "円";
            echo "<form action='' method='post'>";
                echo "<input type='hidden' name='cookie_id' value='" . $value['id'] . "'>";
                echo "<select name='number'>";
                    for ($i=1;$i <= 10; $i++){
                        echo"<option value=" . $i . ">" . $i . "個</option>";
                    }
                echo "</select>";
                echo "<input type=submit value='カートに入れる'>";
            echo "</form>";
            echo "<form action='./delete_item.php' method='post'>";
                echo "<input type='hidden' name='id' value='" . $value["id"] . "'>";
                echo "<input type='submit' value='商品を削除する（未実装）'>";
            echo "</form>";
        echo "</section>";
    }
}
?>
<div class="look_cart">
    <a href="./look_cart.php">カートの中身を確認する</a>
    <a href="./add_items.php">商品を追加</a>
</div>
</body>
</html>
