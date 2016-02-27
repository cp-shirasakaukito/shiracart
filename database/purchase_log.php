<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/12/17
 * Time: 0:23
 */
require_once("database.php");
class Purchase_log extends Database
{
    /* レコードを購入履歴テーブルに追加する
     * レコードを投げると登録され、登録レコードのIDを返す
     * エラーの場合はfalseを返す
     * */
    public function add_purchase_log($link,$record=array())
    {
        foreach ($record as $header => &$value) {
            if(!$this->is_valid_purchase_log($record)){
                return false;
            }
            //varchar形式のカラム名を配列にいれる
            $varchar_columns = array("address_1", "address_2", "name");
            //varchar形式で保存するデータは'でくくる
            if (in_array($header, $varchar_columns)) {
                $value = "'" . $value . "'";
            }
        }
        unset($value);
        return $this->add($link,"purchase_log", $record);
    }

    /*レコードを投げて無効なデータの場合false,有効なデータの場合
     * */
    public function is_valid_purchase_log($record=array()){
        //foreachでまわしてそれぞれのバリデーションチェック
        foreach($record as $header => $value){
            //switchでカラムごとに分岐
            switch($header){
                case "zipcode":
                    if ($this->validate_zipcode($value) !== ""){
                        return false;
                    }
                    break;
                case "address_1":
                    if ($this->validate_address_1($value) !== ""){
                        return false;
                    }
                    break;
                case "address_2":
                    if ($this->validate_address_2($value) !== ""){
                        return false;
                    }
                    break;
                case "name":
                    if ($this->validate_name($value) !== ""){
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
    public function get_error_message_purchase_log($record=array()){
        $error_message = array();
        //foreachでまわしてそれぞれのバリデーションチェック
        foreach($record as $header => $value){
            switch($header){
                case "zipcode":
                    if ($this->validate_zipcode($value) !== ""){
                        $error_message["zipcode"] = $this->validate_zipcode($value);
                    }
                    break;
                case "address_1":
                    if ($this->validate_address_1($value) !== ""){
                        $error_message["address_1"] = $this->validate_address_1($value);
                    }
                    break;
                case "address_2":
                    if ($this->validate_address_2($value) !== ""){
                        $error_message["address_2"] = $this->validate_address_2($value);
                    }
                    break;
                case "name":
                    if ($this->validate_name($value) !== ""){
                        $error_message["name"] = $this->validate_name($value);
                    }
                    break;
                default:
                    break;
            }
        }
        return $error_message;
    }

    /* zipcodeのバリデーション
     * 問題がない場合は空文字列を返し、問題がある場合はエラーメッセージを返す
     * */
    private function validate_zipcode($value){
        $rules = array(
            array(
                "rule" => array("required"),
                "message" => "郵便番号を入力して下さい"
            ),
            array(
                "rule" => array("number"),
                "message" => "郵便番号は半角数字で入力して下さい"
            ),
            array(
                "rule" => array("between",7,7),
                "message" => "郵便番号は7桁で入力して下さい"
            )
        );
        return $this->validate($value,$rules);
    }

    /* address_1のバリデーション
    * 問題がない場合は空文字列を返し、問題がある場合はエラーメッセージを返す
    * */
    private function validate_address_1($value){
        $rules = array(
            array(
                "rule" => array("required"),
                "message" => "住所１を入力して下さい"
            ),
            array(
                "rule" => array("between",1,255 ),
                "message" => "住所１は255文字以下で入力して下さい"
            )
        );
        return $this->validate($value,$rules);
    }

    /* address_2のバリデーション
    * 問題がない場合は空文字列を返し、問題がある場合はエラーメッセージを返す
    * */
    private function validate_address_2($value){
        $rules = array(
            array(
                "rule" => array("between",1,255),
                "message" => "住所２は255文字以下で入力して下さい"
            )
        );
        return $this->validate($value,$rules);
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
}

?>