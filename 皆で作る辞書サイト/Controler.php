<?php
    include('./IncludeModel.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Design.css">
    <title>Mission7.Main</title>
</head>
<body>
<div id="pagebody">
<ul id="title"><h1>みんなで作るおもしろ辞典</h1></ul>
    <form method="post" name="" >
    <input type="text" name="name" value="<?php if(isset($_SESSION['username'])&&isset($_SESSION['pass'])){ echo $username;}?>">
    <input type="password" name="pass" value="<?php if(isset($_SESSION['username'])&&isset($_SESSION['pass'])){ echo $pass;}?>">
    <input type="submit" value="管理者ページへ"><br></form>
    <?php
    //管理者IDの判定
    if(!empty($_POST["name"])&&!empty($_POST["pass"])){
        $controlerName = $_POST["name"];
        $controlerPass = $_POST["pass"];
        if($controlerName=="さくらんぼ"&&$controlerPass=="teamSAKURANBO0726"){
            $sql = 'SELECT * FROM user7';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if($row['username']==$controlerName && $row['pass']==$controlerPass){
                    header("Location:https://tech-base.net/tb-240189/Mission7/Control.php");
                    exit();
                    break;
                }
            }
        }else{
            echo "あなたは管理者ではありません。";
        }
    }
    ?>
</div>
</body>
</html>