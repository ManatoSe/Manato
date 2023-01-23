<?php
    $id = "";
    $username = "";
    $pass = "";
    session_start();

    /*ログインした場合に名前等が表示できるようになる
    ログイン状態で編集や新語登録ができるようにしたいので、パスワードや名前は使いまわせるようにする*/
    if(isset($_SESSION['username'])&&isset($_SESSION['id'])&&isset($_SESSION['pass'])){
        
        $id = $_SESSION['id'];
        $username = $_SESSION['username'];
        $pass = $_SESSION['pass'];
        
    }
    
    
    $date = date("Y/m/d/H:i:s");
    
    /*データベースの接続、include関数にすれば、いちいち接続のコードを書かなくてよい*/
    $dsn = 'データベース';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //Dictionary Infomation
    $word8 = "CREATE TABLE IF NOT EXISTS word8"
    ." ("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."word char(32),"
    ."yomi TEXT,"
    ."meaning TEXT,"
    ."date TEXT,"
    ."name TEXT"
    .");";
    $stmt = $pdo->query($word8);
?>
