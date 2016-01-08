<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 16/01/03
 * Time: 22:55
 */
require_once("core.php");
$_SESSION["member"] = "";
header("Location:".DOMAIN."/cookie_cart.php");
