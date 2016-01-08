<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/23
 * Time: 10:27
 */
require_once("database.php");
class Member extends Database
{
    /* レコードを会員テーブルに追加する
     * レコードを投げると登録され、登録レコードのIDを返す
     * エラーの場合はfalseを返す
     * */
    public function add_member($link,$record=array()){
        if (!$this->is_valid_member($record)){
            return false;
        }
        foreach ($record as $header => &$value) {
            //varchar形式のカラム名を配列にいれる
            $varchar_columns = array("cod","name","email_address","password");
            //varchar形式で保存するデータは'でくくる
            if(in_array($header, $varchar_columns)) {
                $value = "'".$value."'";
            }
        }
        unset($value);
        return $this->add($link,"member",$record);
    }

    /*検索条件を連想配列で投げるとマッチングしたレコードが連想配列で返る
         * */
    public function and_search_member($link,$conditions){
        return $this->and_search($link,"member",$conditions);
    }

    /*レコードを投げて無効なデータの場合false,有効なデータの場合
* */
    public function is_valid_member($record=array()){
        //foreachでまわしてそれぞれのバリデーションチェック
        foreach($record as $header => $value){
            //switchでカラムごとに分岐
            switch($header){
                case "name":
                    if ($this->validate_name($value) !== ""){
                        return false;
                    }
                    break;
                case "price":
                    if ($this->validate_email_address($value) !== ""){
                        return false;
                    }
                    break;
                case "password":
                    if ($this->validate_password($value) !== ""){
                        return false;
                    }
                    break;
                default:
                    break;
            }
        }
        return true;
    }

    /*レコードを投げるとバリデーション結果が連想配列(カラム名=>エラーメッセージ）で返る
     * */
    public function get_error_message_member($record=array()){
        $error_message = array();
        //foreachでまわしてそれぞれのバリデーションチェック　それぞれでvalidate_{key}のfunctionを行えば共通化できそう！だが、すべての要素のバリデーションが必要になる
        foreach($record as $header => $value){
            switch($header){
                case "name":
                    if ($this->validate_name($value) !== ""){
                        $error_message["name"] = $this->validate_name($value);
                    }
                    break;
                case "email_address":
                    if ($this->validate_email_address($value) !== ""){
                        $error_message["email_address"] = $this->validate_email_address($value);
                    }
                    break;
                case "password":
                    if ($this->validate_password($value) !== ""){
                        $error_message["password"] = $this->validate_password($value);
                    }
                    break;
                default:
                    break;
            }
        }
        return $error_message;
    }


    /* nameのバリデーション
     * 問題がない場合は空文字列を返し、問題がある場合はエラーメッセージを返す
     * */
    private function validate_name($value){
        $rules = array(
            array(
                "rule" => array("required"),
                "message" => "氏名を入力して下さい"
            ),
            array(
                "rule" => array("between",1,255 ),
                "message" => "氏名は255文字以下で入力して下さい"
            )
        );
        return $this->validate($value,$rules);
    }

    /* priceのバリデーション
     * 問題がない場合は空文字列を返し、問題がある場合はエラーメッセージを返す
     * */
    private function validate_email_address($value){
        $rules = array(
            array(
                "rule" => array("required"),
                "message" => "メールアドレスを入力して下さい"
            ),
            array(
                "rule" => array("alphanumericsymbol"),
                "message" => "メールアドレスは半角英数記号で入力して下さい"
            ),
            array(
                "rule" => array("email"),
                "message" => "メールアドレスはメールアドレス形式で入力して下さい"
            )
        );
        return $this->validate($value,$rules);
    }

    /* passwordのバリデーション
    * 問題がない場合は空文字列を返し、問題がある場合はエラーメッセージを返す
    * */
    private function validate_password($value){
        $rules = array(
            array(
                "rule" => array("required"),
                "message" => "パスワードを入力して下さい"
            ),
            array(
                "rule" => array("alphanumericsymbol"),
                "message" => "パスワードは半角英数字記号で入力して下さい"
            ),
            array(
                "rule" => array("between",5,255 ),
                "message" => "パスワードは5文字以上255文字以下で入力して下さい"
            )
        );
        return $this->validate($value,$rules);
    }
}