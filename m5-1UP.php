<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>コメント</title>
</head>
<body>
    <?php
        $date = date("Y/m/d/ H:i:s");
        // DB接続設定
        $dsn = 'データベース';
        $user = 'ユーザー';
        $password = 'パスワード';
        $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //テーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS Nm51"
        ." ("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."comment TEXT,"
        ."time TEXT,"
        ."pass char(32)"
        .");";
        $stmt = $pdo->query($sql);

        if(!empty($_POST["name"])&& !empty($_POST["comment"]) && !empty($_POST["pass"])&&!empty($_POST["upda"])){
            echo "OK";
            $edi = $_POST["upda"];
            $ediname = $_POST["name"];
            $edistr = $_POST["comment"];
            $pass = $_POST["pass"];
            $id = $edi;
            $name = $ediname;
            $comment = $edistr;
            $time = $date;
            $sql = 'UPDATE Nm51 SET name =:name,comment=:comment,time=:time, pass=:pass WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':time', $time, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
        }else if(!empty($_POST["name"])&& !empty($_POST["comment"]) && !empty($_POST["pass"])){
            $name1 = $_POST["name"];
            $str1 = $_POST["comment"];
            $pas = $_POST["pass"];
            
            $sql = $pdo -> prepare("INSERT INTO Nm51 (name, comment,time,pass) VALUES (:name, :comment, :time, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':time', $time, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $name = $name1;
            $comment = $str1;
            $time = $date;
            $pass = $pas;
            $sql -> execute();
        }
        //編集機能
        if( !empty($_POST["edi"]) &&  !empty($_POST["pass2"])){
            $update = $_POST["edi"];
            $pass = $_POST["pass2"];
            $name2="";
            $comment2="";
            $id2 = "";
            $sql = 'SELECT * FROM Nm51';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if($update == $row['id']){
                    if($pass == $row['pass']){
                        $name2=$row['name'];
                        $comment2=$row['comment'];
                        $id2 = $row['id'];
                    }
                }
            }
            
        }
        if(!empty($_POST["cut"]) && !empty($_POST["pass1"])){
            $id = $_POST["cut"];
            $pass = $_POST["pass1"];
            $sql = 'delete from Nm51 where id=:id && pass=:pass';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        
        
    ?>
    <h1>ようこそ！まなと制作の掲示板へ！</h1>
    <form action="" method="post">
        名前：<input type="text" name="name" value="<?php if(!empty($_POST["edi"])){echo $name2;}?>">
        コメント：<input type="text" name="comment" value="<?php if(!empty($_POST["edi"])){echo $comment2;}?>">
        パスワード:<input type="text" name="pass">
        <input type="hidden" name="upda" value="<?php if (!empty($_POST["edi"])) { echo $id2;} ?>" />
        <input type="submit" value="コメント"><br>
    </form>
    <p>------------------------------------------------------------------------------------</p>
    <form action="" method="post">
        削除したい番号を指定<input type="number" name="cut"><br>
        そのパスワードを入力<input type="text" name="pass1">
        <input type="submit" value="削除"><br>
    </form>
    <p>------------------------------------------------------------------------------------</p>
    <form action="" method="post">
        編集したい番号を指定<input type="number" name="edi"><br>
        そのパスワードを入力<input type="text" name="pass2">
        <input type="submit" value="編集"><br>
    </form>
    <p>------------------------------------------------------------------------------------</p>
    <?php
        $sql = 'SELECT * FROM Nm51';
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
