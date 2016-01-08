<?php

/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/12/20
 * Time: 17:27
 */
require_once("database.php");
class Join_token extends Database
{
    /* レコードをクレジットカード決済履歴テーブルに追加する
 * レコードを投げると登録され、登録レコードのIDを返す
 * エラーの場合はfalseを返す
 * */
    public function add_join_token($link,$record=array())
    {
        foreach ($record as $header => &$value) {
            if(!$this->is_valid_join_token($record)){
                return false;
            }
            //varchar形式,datetime形式のカラム名を配列にいれる
            $varchar_columns = array("email","join_token","expire_date");
            //varchar形式で保存するデータは'でくくる
            if (in_array($header, $varchar_columns)) {
                $value = "'" . $value . "'";
            }
        }
        unset($value);
        return $this->add($link,"join_token", $record);
    }

    /*レコードを投げて無効なデータの場合false,有効なデータの場合
     * */
    public function is_valid_join_token($record=array()){
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
    public function get_error_message_join_token($record=array()){
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

    /*検索条件を連想配列で投げるとマッチングしたレコードが連想配列で返る
     * */
    public function and_search_join_token($link,$conditions){
        return $this->and_search($link,"join_token",$conditions);
    }
}