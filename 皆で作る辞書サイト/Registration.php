<?php
    include('./IncludeModel.php');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規登録</title>
    <link rel="stylesheet" href="Design.css">
</head>
<body>
<div id="pagebody">
    <ul id="title">
    <h1>新規登録画面</h1></ul>
    <p>新規登録には名前とパスワードを設定してください。</p>
    <ul id="login">
    <p>新規登録はこちら</p>
    <form action="" method="post">
    登録する名前の入力:<input type="text" name="newname"><br>
    パスワードの設定:<input type="text" name="newpass">
    <input type="submit" value="新規登録"><br>
    </form>
    </ul>
    <br>
    
    <?php
        
    $user7 = "CREATE TABLE IF NOT EXISTS user7"
    ." ("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."username char(32),"
    ."pass char(32)"
    .");";
    $stmtUs = $pdo->query($user7);

        //登録の処理
        if(!empty($_POST["newname"])&& !empty($_POST["newpass"])){

            $nam = $_POST["newname"];
            $pas = $_POST["newpass"];
            
            $sql = $pdo -> prepare("INSERT INTO user7 (username, pass) VALUES (:username, :pass)");
            $sql -> bindParam(':username', $username, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $username = $nam;
            $pass = $pas;
            $sql -> execute();
            echo "**************************************************<br>";
            echo "新規登録完了！".$nam."さん、よろしくね！<br>";
            echo "ログイン画面へ<form method=\"post\" name=\"form1\" action=\"Login.php\">
                    <input type=\"hidden\" name=\"nam\" value=\"".$nam."\">
                    <input type=\"hidden\" name=\"pas\" value=\"".$pas."\">
                    <a href=\"javascript:form1.submit()\">ログイン</a></form>";
            echo "**************************************************<br>";
            
        }
    ?>
    
<h2>・登録方法</h2>
<strong>　新規登録はこちらから名前/パスワードを入力し、「新規登録」ボタンを押してください。<br>
　もし新規登録ができた場合、「新規登録完了」メッセージが出てきます。</strong>
<h2>・ログイン方法</h2>
<strong>既に新規登録が完了している場合は、名前/パスワードを入力し、ログイン処理を行ってください。</strong>

</div>
</body>
</html>