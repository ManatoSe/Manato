<?php
    include('./IncludeModel.php');
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
    <div class="Main">
    <div class="left">
    <h1>＊＊新語登録＊＊</h1>
    <ul id="select">
    <form action="" method="post">
        ＊あなたが新しい言葉を登録できます：<br>
        言葉：<input type"text" name="word" value="" >
        読み：<input type"text" name="yomi" value="" ><br>
        説明：<textarea name="meaning" rows="3" cols="70" maxlength="200"></textarea>
        <input type="submit" value="登録"><br>
    </form><br>
    </ul><br>
    <?php
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
    
    if(!empty($_POST["word"])&&!empty($_POST["yomi"])&&!empty($_POST["meaning"])){
    if($username!=""&&$pass!=""){
    /*読みがひらがなかを判定*/
    if (preg_match('/^[ぁ-ゞ]+$/u', $_POST["yomi"])) {
        
        /*登録したい単語の読みや意味などを取得していく*/
        $word1 = $_POST["word"];
        $yomi1 = $_POST["yomi"];
        $meaning1 = nl2br($_POST["meaning"]);
        
        /*データベースに書いていく処理*/
        $sql = $pdo -> prepare("INSERT INTO word8 (word, yomi,meaning,date,name) VALUES (:word, :yomi, :meaning, :date, :name)");
        $sql -> bindParam(':word', $word, PDO::PARAM_STR);
        $sql -> bindParam(':yomi', $yomi, PDO::PARAM_STR);
        $sql -> bindParam(':meaning', $meaning, PDO::PARAM_STR);
        $sql -> bindParam(':date', $time, PDO::PARAM_STR);
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $word = $word1;
        $yomi = $yomi1;
        $meaning = $meaning1;
        $time = $date;
        $name = $username;
        $sql -> execute();
        echo "「".$word1."」を登録しました。";
    }else{
        echo "読みは平がなのみご利用いただけます。";
    }    
    }else{
        echo "ログインしてください。";
    }
    }
    
    ?>
    </div>
    <div class="right">
    <ul id="menu">
    <li><a href="Main.php">メイン画面に戻る</a></li>
    <li><a href="Login.php">ログイン</a></li>
    <li><a href="Registration.php">新規登録</a></li>
    <li><a href="NewWord.php">新語登録</a></li>
    <li><a href="Editing.php">編集機能</a></li>
    </ul>
    </div>
    </div>
</div>
    

</div>
</body>
</html>
