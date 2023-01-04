<?php
    include('./IncludeModel.php');
?>
<?php
$whatword ="";
if(!empty($_POST["whatword"])){
    $whatword = $_POST["whatword"];
}
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
        <?php if($whatword!=""){echo $whatword."を検索します。";}?><br>
    <?php
        echo "<ul id=\"meanings\">";
        $str = "";
        $sql1 = 'SELECT * FROM word8';
        $stmt = $pdo->query($sql1);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if ($whatword== $row["yomi"]) {
                $str=
                 "・".$row['word']."(".$row['yomi'].")<br>「".$row['meaning']."」<br>"."最終編集者：".$row['name']."(投稿日：".$row['date'].")<br>".
                 "<form method=\"post\" name=\"form\" action=\"Editing.php\">
                <input type=\"hidden\" name=\"word\" value=\"".$row["word"]."\">
                <a href=\"javascript:form.submit()\">編集</a></form>".
                "-----------------------------------------------------------------<br>";
                break;
            }else{
                $str="その単語は存在しません。";
            }
        }
        echo $str;
        echo "</ul>";
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