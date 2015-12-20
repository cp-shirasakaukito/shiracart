<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/10
 * Time: 21:48
 */
require_once(dirname(__FILE__)."/../validation.php");
class Database extends Validation {
    //データベースアクセス情報
    const SERVER = "localhost:8889";
    const USER = "root";
    const PASS = "root";
    const DATABASE = "cart";

    public function __construct(){
        //php.iniのタイムゾーンをいじっても変わらなかったので・・・(core.phpなんかのもっと上流の共通クラスを作ったらそっちへ移動）
        date_default_timezone_set("Asia/Tokyo");
    }

    public function __destruct(){
    }

    //データベースにアクセスする
    //成功した場合サーバーへの接続を表すオブジェクトを返す
    public function connect(){
        $link = new mysqli(self::SERVER,self::USER,self::PASS,self::DATABASE);
        if($link->connect_errno){
            die("接続失敗".$link->error);

        }
        $link->set_charset("utf8");
        return $link;
    }

    //トランザクションを開始する。
    //成功true　失敗falseを返却
    public function begin_transaction($link){
        return $link->begin_transaction();
    }

    //コミットする
    //成功true　失敗falseを返却
    public function commit($link){
        return $link->commit();
    }

    //ロールバックする
    //成功true　失敗falseを返却
    public function rollback($link){
        return $link->rollback();
    }

    //テーブル内のすべてのレコードを返します。
    protected function select_all($link,$table) {
        $query = "SELECT * FROM " .$table;
        $result = $link->query($query);
        $result_array = $result->fetch_all(MYSQLI_ASSOC);
        return $result_array;
    }

    //テーブルと「カラム名=>データ」の連想配列から
    //データを追加
    //登録に成功すると登録レコードのIDを返す。失敗すると、falseを返す。
    protected function add($link,$table,$record=array()){
        //登録日時を追加
        $record["register_time"] = "'".date("Y-m-d H:i:s",time())."'";
        //インサート文に入れるヘッダとデータのカンマ区切りデータ作成
        $header ="";
        $data ="";
        foreach($record as $key => $value) {
            if ($header === "") {
                $header = $key;
            } else {
                $header .= ",".$key;
            }
            if ($data === "") {
                $data = $value;
            } else {
                $data .= ",".$value;
            }
        }
        $query = "INSERT INTO ".$table." (".$header.") VALUES (".$data.")";
        if($link->query($query)){
            return $link->insert_id;
        } else {
            return false;
        }
    }

}
?>