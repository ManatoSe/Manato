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
    <h1>＊＊編集機能＊＊</h1>
    <ul id="select">
    <form action="" method="post">
        ＊<?php  if(isset($_SESSION['username'])){echo $username."さんの";}else{echo "あなたの";}?>編集したい言葉を検索しよう：
        <input type="text" name="word" value="">
        <input type="submit" value="検索"><br>
    </form>
    </ul><br>
    <?php
    if($username!=""){
    
    /*単語の存在を確認し、あれば編集機能を表示する*/
    if(!empty($_POST["word"])){
        $word = $_POST["word"];
        
        //変数定義
        $newword = "";
        $newyomi = "";
        $newmeaning = "";
        $sql1 = 'SELECT * FROM word8';
        $stmt = $pdo->query($sql1);
        $results = $stmt->fetchAll();
        foreach($results as $r){
            if($word==$r['word']){
                $newword = $r['word'];
                $newyomi = $r['yomi'];
                $newmeaning = $r['meaning'];
                $newmeaning = str_replace('<br />', '', $newmeaning);
                $id=$r['id'];
                break;
            }
        }
        if($newword!=""&&$newyomi!=""&&$newmeaning!=""){
            
        //word, yomi, meaningをそれぞれいれたフォームを作る
        echo "<form action=\"\" method=\"post\">
        ・編集してください。：<br>
        言葉：<input type\"text\" name=\"newword\" value=\"".$newword."\" >
        読み：<input type\"text\" name=\"newyomi\" value=\"".$newyomi."\" ><br>
        説明：<textarea name=\"newmeaning\" rows=\"3\" cols=\"70\" maxlength=\"200\">".$newmeaning."</textarea>
        <input type=\"hidden\" name=\"searchid\" value=\"".$id."\">
        <input type=\"submit\" value=\"OK\"><br>
        </form><br>";
        }else{
            echo "その単語はありません。";
        }
        
        
        
    }
    }else{
        echo "ログインしてください。";
    }
    ?>
    <?php
    if(!empty($_POST["newword"])&&!empty($_POST["newyomi"])&&!empty($_POST["newmeaning"])&&!empty($_POST["searchid"])){
            echo "OK";
            $id=$_POST["searchid"];
            $word=$_POST["newword"];
            $yomi=$_POST["newyomi"];
            $meaning=nl2br($_POST["newmeaning"]);
            $name=$username;
            $date=$date;
            $sql = 'UPDATE word8 SET word=:word, yomi=:yomi, meaning=:meaning, date=:date, name=:name WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':word', $word, PDO::PARAM_STR);
            $stmt->bindParam(':yomi', $yomi, PDO::PARAM_STR);
            $stmt->bindParam(':meaning', $meaning, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
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
