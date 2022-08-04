<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>コメント</title>
</head>
<body>
    <h1>ようこそ！Manato制作の掲示板へ！</h1>
    <p>名前・コメント(何でも良い)・パスワード(削除と編集に必要なので覚えてね,abcdとかで大丈夫)、全て入力してボタンを押すと下部に投稿内容が表示される<br>
    削除は、対象番号とパスワードの両方を入力し、編集はそれに追加で編集内容を入力することで実行可能</p>
    <form action="" method="post">
        <p>名前を入力</p>
        <input type="text" name="name"><?php if(isset($editname)) {echo $editname;} ?>
        <p>コメントの入力</p>
        <input type="text" name="str"><?php if(isset($editstr)) {echo $editstr;} ?>
        <p>パスワードを設定してください</p>
        <input type="text" name="pass">
        <input type="submit" value="コメント"><br>
    </form>
    <p>------------------------------------------------------------------------------------</p>
    <form action="" method="post">
        <p>削除したい番号を指定</p>
        <input type="number" name="cut">
        <p>そのパスワードを入力してください</p>
        <input type="text" name="pass1">
        <input type="submit" value="削除"><br>
    </form>
    <p>------------------------------------------------------------------------------------</p>
    <form action="" method="post">
        <p>編集したい番号を指定</p>
        <input type="number" name="edi">
        <p>編集する名前の入力</p>
        <input type="text" name="ediname">
        <p>編集するコメントの入力</p>
        <input type="text" name="edistr">
        <p>そのパスワードを入力してください</p>
        <input type="text" name="pass2">
        <input type="submit" value="編集"><br>
    </form>
    <p>------------------------------------------------------------------------------------</p>
    <?php
        $date = date("Y/m/d/ H:i:s");
        // DB接続設定
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //テーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS m51UP"
        ." ("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."comment TEXT,"
        ."time TEXT,"
        ."pass char(32)"
        .");";
        $stmt = $pdo->query($sql);

        if(!empty($_POST["name"])&& !empty($_POST["str"]) && !empty($_POST["pass"])){
            $name1 = $_POST["name"];
            $str1 = $_POST["str"];
            $pas = $_POST["pass"];
            
            $sql = $pdo -> prepare("INSERT INTO m51UP (name, comment,time,pass) VALUES (:name, :comment, :time, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':time', $time, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $name = $name1;
            $comment = $str1;
            $time = $date;
            $pass = $pas;
            $sql -> execute();
            
        } else if( !empty($_POST["edi"]) && !empty($_POST["edistr"]) && !empty($_POST["pass2"]) && !empty($_POST["ediname"])){
            $edi = $_POST["edi"];
            $ediname = $_POST["ediname"];
            $edistr = $_POST["edistr"];
            $pass = $_POST["pass2"];
            $id = $edi;
            $name = $ediname;
            $comment = $edistr;
            $time = $date;
            $sql = 'UPDATE m51UP SET name =:name,comment=:comment,time=:time WHERE id=:id && pass=:pass';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':time', $time, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
        }
        if(!empty($_POST["cut"]) && !empty($_POST["pass1"])){
            $id = $_POST["cut"];
            $pass = $_POST["pass1"];
            $sql = 'delete from mi51UP where id=:id && pass=:pass';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        
        $sql = 'SELECT * FROM m51UP';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].':';
            echo $row['name'].'「';
            echo $row['comment'].'」';
            echo $row['time'].'<br>';
            echo "<hr>";
        }
    ?>
</body>
</html>
