<?php

/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 16/01/10
 * Time: 19:22
 */
class Csrf
{
    public function generate_csrf_token() {
        $file_name = basename($_SERVER['PHP_SELF']);
        $file_name = substr($file_name,0,strlen($file_name)-4);
        $key = "csrftoken_" . $file_name;
        $tokens = $_SESSION[$key];
        if (count($tokens) >= 10) {
            array_shift($tokens);
        }

        $token = sha1(uniqid($file_name,true));
        $tokens[] = $token;

        $_SESSION[$key] = $tokens;

        return $token;
    }

    public function check_csrf_token($token) {
        $file_name = basename($_SERVER['PHP_SELF']);
        $file_name = substr($file_name,0,strlen($file_name)-4);
        $key = "csrftoken_" . $file_name;
        $tokens = $_SESSION[$key];

        if ($found_token_key = array_search($token,$tokens,true)) {
            unset($tokens[$found_token_key]);
            $_SESSION[$key] = $tokens;
            return true;
        } else {
            return false;
        }

    }

}