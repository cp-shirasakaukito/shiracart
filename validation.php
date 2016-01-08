<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/23
 * Time: 13:28
 */


class Validation {

    /* $rules配列に従って$valueのバリデーションを行いエラー文言を返す
     *
     * */
    protected function validate($value,$rules=array()){
        //上から順番にチェックする
        foreach($rules as $pattern){
            //呼び出したいバリデーション関数を取得
            $validate_func = $pattern["rule"][0];
            unset($pattern["rule"][0]);
            //引数を調整
            $args[0] = $value;
            $args = array_merge($args,$pattern["rule"]);
            //チェック！！！
            $result = call_user_func_array(array($this,$validate_func), $args);
            if (!$result) {
                return $pattern["message"];
            }
        }
        return "";
    }

    /* 必須チェック
     * input:　
     * $input　入力した値
     * $message エラー時のエラー文言
     * output:　問題がない場合true、問題がある場合false
     * */
    private function required($input) {
        if (empty($input)) {
            return false;
        } else {
            return true;
        }
    }

    /*ユニークチェック
     * input:　
     * $input　入力した値
     * $table　チェックするテーブル名
     * $column　チェックするカラム名
     * $message エラー時のエラー文言
     * output:　問題がない場合true、問題がある場合false
     * */
    private function is_unique($input,$table,$column){

    }

    /*文字数チェック
     * */
    private function between($input,$min = PHP_INT_MIN,$max = PHP_INT_MAX){
        $length = mb_strlen($input);
        if($length < $min || $length > $max){
            return false;
        } else {
            return true;
        }
    }

    /*数字チェック
     * */
    private function number($input){
        return is_numeric($input);
    }

    /*数値の上限、下限チェック
     * */
    private function number_range($input,$min = PHP_INT_MIN, $max = PHP_INT_MAX) {
        if ($input < $min || $input > $max) {
            return false;
        } else {
            return true;
        }
    }

    /*e-mail形式チェック
     * */
    private function email($input) {
        if (preg_match('/^[\w\+\.]+@[\w\.-]+\.\w{2,}$/', $input)) {
            return true;
        } else {
            return false;
        }
    }

    /*半角英数チェック
     * */
    private function alphanumericsymbol($input) {
        if (preg_match('/^[ -~]+$/', $input)) {
            return true;
        } else {
            return false;
        }
    }
}