<?php

/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/12/20
 * Time: 17:37
 */
require_once("validation.php");
class only_check_form extends Validation
{
    /*
     * 会員登録時の登録URL通知メール送信フォームのバリデーション
     *レコードを投げて無効なデータの場合false,有効なデータの場合trueを返す
     * */
    public function is_valid_email($record=array()){
        //foreachでまわしてそれぞれのバリデーションチェック
        foreach($record as $header => $value){
            //switchでカラムごとに分岐
            switch($header){
                case "email":
                    if ($this->validate_email($value) !== ""){
                        return false;
                    }
                    break;
                case "confirm_email":
                    if ($this->validate_confirm_email($value) !== ""){
                        return false;
                    }
                default:
                    break;
            }
        }
        return true;
    }

    /*レコードを投げるとバリデーション結果が連想配列(カラム名=>エラーメッセージ）で返る
     * */
    public function get_error_message_purchase_log($record=array()){
        $error_message = array();
        //foreachでまわしてそれぞれのバリデーションチェック
        foreach($record as $header => $value){
            switch($header){
                case "email":
                    if ($this->validate_email($value) !== ""){
                        $error_message["email"] = $this->validate_email($value);
                    }
                    break;
                case "confirm_email":
                    if ($this->validate_confirm_email($value) !== ""){
                        $error_message["confirm_email"] = $this->validate_confirm_email($value);
                    }
                    break;
                default:
                    break;
            }
        }
        return $error_message;
    }

    /* emailのバリデーション
     * 問題がない場合は空文字列を返し、問題がある場合はエラーメッセージを返す
     * */
    private function validate_email($value){
        $rules = array(
            array(
                "rule" => array("required"),
                "message" => "メールアドレスを入力して下さい"
            ),
            array(
                "rule" => array("email"),
                "message" => "メールアドレス形式で入力して下さい"
            ),
            array(
                "rule" => array("is_unique","member"),
                "message" => "指定のメールアドレスはすでに登録されています"
            )
        );
        return $this->validate($value,$rules);
    }

    /* confirm_emailのバリデーション
    * 問題がない場合は空文字列を返し、問題がある場合はエラーメッセージを返す
    * */
    private function validate_confirm_email($value){
        $rules = array(
            array(
                "rule" => array("required"),
                "message" => "メールアドレスを入力して下さい"
            ),
            array(
                "rule" => array("email"),
                "message" => "メールアドレス形式で入力して下さい"
            )
        );
        return $this->validate($value,$rules);
    }
}