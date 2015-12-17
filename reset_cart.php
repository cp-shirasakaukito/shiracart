<?php
session_start();
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/05
 * Time: 1:33
 */
//クッキーをリセット
$_SESSION["cart"] = array();
header("location: look_cart.php");
?>
