<?php
    $id = "";
    $username = "";
    $pass = "";
    session_start();
    if(isset($_SESSION['username'])&&isset($_SESSION['id'])&&isset($_SESSION['pass'])){
        
        $id = $_SESSION['id'];
        $username = $_SESSION['username'];
        $pass = $_SESSION['pass'];
        
    }
    
    
    $date = date("Y/m/d/H:i:s");
    
    $dsn = 'mysql:dbname=tb240189db;host=localhost';
    $user = 'tb-240189';
    $password = 'gsa8auKbX7';
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