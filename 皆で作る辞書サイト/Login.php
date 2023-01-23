<?php
    include('./IncludeModel.php');
    $nam = "";
    $pas = "";
    /*新規登録から送信された場合に、名前とパスワードを自動的に登録する*/
    if(!empty($_POST["nam"])&&!empty($_POST["pas"])){
        $nam = $_POST["nam"];
        $pas = $_POST["pas"];
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="Design.css">
</head>
<body>
<div id="pagebody">
    <ul id="title">
    <h1>ログイン画面</h1></ul>
    <p>ログインには、ユーザー名とパスワードが必要です。</p>
    <form action="" method="post">
    ユーザー名:<input type="text" name="name" value="<?php if(!empty($_POST["nam"])){echo $nam;}?>"><br>
    パスワード:<input type="password" name="pass" value="<?php if(!empty($_POST["pas"])){echo $pas;}?>">
    <input type="submit" value="ログイン"><br>
    
    <?php
        
        /*POSTで送った名前とパスワードを取得*/
        if(!empty($_POST["name"])&& !empty($_POST["pass"])){
            $name = $_POST["name"];
            $pass = $_POST["pass"];
            
            /*データベースに接続し、変数resultにユーザー一覧を入れる*/
            $sql = 'SELECT * FROM user7';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                
                /*名前・パスワードが一致するものがあるかどうかを判定し、あればログインは完了し、Main.phpのメイン画面へ移動*/
                if($row['username']==$name && $row['pass']==$pass){
                    
                    /*ログイン時の名前やID、パスワードは使いまわせるようにincludeに入れておく*/
                    $_SESSION['id']=$row['id'];
                    $_SESSION['username'] = $name;
                    $_SESSION['pass'] = $row['pass'];
                    session_write_close();
                    header("Location:https://tech-base.net/tb-240189/Mission7/Main.php");
                    exit();
                    break;
                }
            }
        }
    ?>

</div>
</body>
</html>
