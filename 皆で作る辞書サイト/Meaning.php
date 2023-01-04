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
    
    <div class="Main">
    <div class="left">
    <h1>＊＊辞典＊＊</h1>
    <ul id="select">
    <form method="post" name="form" action="Search.php">
    ＊<?php  if(isset($_SESSION['username'])){echo $username."さんの";}else{echo "あなたの";}?>知りたい言葉を検索しよう：
    <input type="text" name="whatword" value="">
    <a href="javascript:form.submit()">検索</a></form>
    </ul><br>
    <?php
    if(!empty($_POST["moji"])){
        $moji = $_POST["moji"];
        echo "「".$moji."」からはじまる語<br>";
        
        echo "<ul id=\"meanings\">";
        
        $sql1 = 'SELECT * FROM word8 ORDER BY yomi';
        $stmt = $pdo->query($sql1);
        $results = $stmt->fetchAll();
        $int = 1;
        foreach ($results as $row){
            if (preg_match("/^$moji/", $row["yomi"])) {
            echo "・".$row['word']."(".$row['yomi'].")<br>「".$row['meaning']."」<br>"."最終編集者：".$row['name']."(投稿日：".$row['date'].")<br>";
            echo "<form method=\"post\" name=\"form".$int."\" action=\"Editing.php\">
                <input type=\"hidden\" name=\"word\" value=\"".$row["word"]."\">
                <a href=\"javascript:form".$int.".submit()\">編集</a></form>";
            echo "-------------------------------------------------------------------------<br>";
            $int++;
            }
        }
        echo "</ul>";
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
</body>
</html>