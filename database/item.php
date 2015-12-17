<?php

/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/16
 * Time: 23:38
 */
require_once("database.php");
class Item extends Database
{
    public function select_all_item(){
        $result = $this->select_all("item");
        return $result;
    }

    /*
     * レコードを入力して、商品を追加
     * 追加に成功したらtrue 失敗したらfalseを返す
     * */
    public function add_item($record=array()) {
        //有効な値かをチェック
        if(!$this->is_valid_item($record)) {
            return false;
        }
        foreach ($record as $header => &$value) {
            //varchar形式で保存するデータは'でくくる
            if($header === "name") {
                $value = "'".$value."'";
            }
        }
        unset($value);
        return $this->add("item",$record);
    }

    /*レコードを投げて無効なデータの場合false,有効なデータの場合
    * */
    public function is_valid_item($record=array()){
        $error_message = array();
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
                    if ($this->validate_price($value) !== ""){
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
    public function get_error_message_item($record=array()){
        $error_message = array();
        //foreachでまわしてそれぞれのバリデーションチェック
        foreach($record as $header => $value){
            //switchでカラムごとに分岐
            switch($header){
                case "name":
                    if ($this->validate_name($value) !== ""){
                        $error_message["name"] = $this->validate_name($value);
                    }
                    break;
                case "price":
                    if ($this->validate_price($value) !== ""){
                        $error_message["price"] = $this->validate_price($value);
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
                "message" => "商品名を入力して下さい"
            ),
            array(
                "rule" => array("between",1,20),
                "message" => "商品名は20文字以下で入力して下さい"
            )
        );
        return $this->validate($value,$rules);
    }

    /* priceのバリデーション
     * 問題がない場合は空文字列を返し、問題がある場合はエラーメッセージを返す
     * */
    private function validate_price($value){
        $rules = array(
            array(
                "rule" => array("required"),
                "message" => "金額を入力して下さい"
            ),
            array(
                "rule" => array("number"),
                "message" => "金額は数字で入力して下さい"
            ),
            array(
                "rule" => array("number_range",1,30),
                "message" => "金額は1円以上で入力して下さい"
            )
        );
        return $this->validate($value,$rules);
    }


}