<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/11/22
 * Time: 11:39
 */
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>
<body>
    <div>
        <section>
            <form action="" method="post">
                <input type="text" name="email" placeholder="メールアドレスを入力して下さい"><br>
                <input type="password" name="password" placeholder="パスワード"><br>
                <input type="submit" value="ログイン">
            </form>
        </section>
    </div>
    <div>
        <section>
            <input type="button" onclick="location.href = 'register.php'" value="会員登録する" />
        </section>
    </div>
</body>
</html>
