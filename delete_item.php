<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/09
 * Time: 3:07
 */
//クッキーをリセット
foreach($_COOKIE['cart'] as $item_num => $items){
    if ($items["id"] == $_POST["id"]) {
        foreach($items as $info => $value){
            setcookie("cart[".$item_num."][".$info."]",$info,time() - 1000);
        }
    }
}

//商品を削除。未完・・・無念
//まずはCSVを配列化する
//POSTとIDを比較してUPDATE列を特定する
//
