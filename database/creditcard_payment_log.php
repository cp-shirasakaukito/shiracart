<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/12/17
 * Time: 0:52
 */
require_once("database.php");
class Creditcard_payment_log extends Database
{
    /* レコードをクレジットカード決済履歴テーブルに追加する
     * レコードを投げると登録され、登録レコードのIDを返す
     * エラーの場合はfalseを返す
     * */
    public function add_creditcard_payment_log($link,$record=array())
    {
        foreach ($record as $header => &$value) {
            if(!$this->is_valid_creditcard_payment_log($record)){
                return false;
            }
            //varchar形式のカラム名を配列にいれる
            $varchar_columns = array();
            //varchar形式で保存するデータは'でくくる
            if (in_array($header, $varchar_columns)) {
                $value = "'" . $value . "'";
            }
        }
        unset($value);
        return $this->add($link,"creditcard_payment_log", $record);
    }

    /*レコードを投げて無効なデータの場合false,有効なデータの場合
     * */
    public function is_valid_creditcard_payment_log($record=array()){
        //foreachでまわしてそれぞれのバリデーションチェック
        foreach($record as $header => $value){
            //switchでカラムごとに分岐
            switch($header){
                default:
                    break;
            }
        }
        return true;
    }

    /*レコードを投げるとバリデーション結果が連想配列(カラム名=>エラーメッセージ）で返る
     * */
    public function get_error_message_creditcard_payment_log($record=array()){
        $error_message = array();
        //foreachでまわしてそれぞれのバリデーションチェック
        foreach($record as $header => $value){
            switch($header){
                default:
                    break;
            }
        }
        return $error_message;
    }

}
?>