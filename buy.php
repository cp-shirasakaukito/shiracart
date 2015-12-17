<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/11
 * Time: 0:38
 */
session_start();
//SSLでの決済はTLS1.1以上に対応するためにMAMPのPHPを再コンパイルする必要があり面倒なので断念orz
$url = "http://credit2.j-payment.co.jp/gateway/gateway.aspx";
if (isset($_POST["cn"])){
    //決済
    $data = array(
        "aid" => "106904",
        "jb" => "CAPTURE",
        "rt" => "1",
        "cn" => htmlspecialchars($_POST["cn"],ENT_QUOTES,"UTF-8"),
        "ed" => htmlspecialchars($_POST["ed"],ENT_QUOTES,"UTF-8"),
        "fn" => htmlspecialchars($_POST["fn"],ENT_QUOTES,"UTF-8"),
        "ln" => htmlspecialchars($_POST["ln"],ENT_QUOTES,"UTF-8"),
        "em" => htmlspecialchars($_POST["em"],ENT_QUOTES,"UTF-8"),
        "pn" => htmlspecialchars($_POST["pn"],ENT_QUOTES,"UTF-8"),
        "am" => htmlspecialchars($_POST["price"],ENT_QUOTES,"UTF-8")
    );
    $curl = curl_init($url);
    curl_setopt($curl,CURLOPT_POST,true);
    curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($data));
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);

    //決済結果を整理
    $result = explode(",",$response);
    $result = [
        "gid" => $result[0],
        "rst" => $result[1],
        "ap" => $result[2],
        "ec" => $result[3],
        "god" => $result[4],
        "cod" => $result[5],
        "am" => $result[6],
        "tx" => $result[7],
        "sf" => $result[8],
        "ta" => $result[9],
        "id" => $result[10],
        "ps" => $result[11]
    ];
    //結果に応じて処理を分岐
    if ($result["rst"] === 1) {
        //購入履歴をDBに保存する

        //決済結果をDBに保存する

        //

        //セッションをリセット
        $_SESSION["cart"] = array();
        header('location: thanks.php');
        exit();
    } else {
        $error = "決済に失敗しました(".$result["ec"].")";
    }
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>クレジットカード情報入力ページ</title>
</head>
<body>
<?php

//決済エラー時の表示処理
if (isset($error)){
    echo $error;
}

//クレジットカード情報入力フォーム表示とその制御
if (!isset($_POST["price"]) || $_POST["price"]<1000) {
    $html = <<<EOT
    <section>クレジットカードのご利用は1000円以上の購入が必要です</section>
EOT;
} else {
    $html = <<<EOT
    <section>
        <section>決済金額：{$_POST['price']}</section>
        <form action="" method="post">
            <section>クレジットカード番号：<input type="text" name="cn" value="" placeholder="4444333322221111"></section>
            <section>有効期限：<input type="text" name="ed" value="" placeholder="YYMM"></section>
            <section>お名前：<input type="text" name="ln" placeholder="性"><input type="text" name="fn" placeholder="名"></section>
            <section>メールアドレス：<input type="text" name="em"></section>
            <section>電話番号：<input type="text" name="pn"></section>
            <section><input type="hidden" name="price" value="{$_POST['price']}"></section>
            <section><input type="submit" value="決済する"></section>
        </form>
    </section>
EOT;
}
echo $html;
?>
</body>
</html>