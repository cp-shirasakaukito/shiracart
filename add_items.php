<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/07
 * Time: 22:17
 */
require_once("database/item.php");

//新規商品の追加
if($_POST){
    $record = array(
        "name" => $_POST["name"],
        "price" => $_POST["price"]
    );
    $connect = new Database();
    $link = $connect->connect();
    $connect->begin_transaction($link);
    $item = new Item();
    if($item->add_item($link,$record)) {
        $success = "登録に成功しました";
    } else {
        $error = $item->get_error_message_item($record);
    }
    $connect->commit($link);
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
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
<section>
    <h4>新しい商品を登録</h4>
    <form action="" method="post">
        商品名：<input type="text" name="name"><br />
        単価：<input type="text" name="price" placeholder="100"><br />
        <input type="submit" value="登録">
    </form>
</section>
<section>
    <a href="./cookie_cart.php">トップへ</a>
</section>

</body>
</html>
