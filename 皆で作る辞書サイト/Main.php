<?php

    /*便利なコードをまとめているファイル「IncludeModel.php」をインクルードファイルに入れて、使い回せるようにしている*/
    include('./IncludeModel.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    
    <!-- cssファイルの読み込み -->
    <link rel="stylesheet" href="Design.css">
    
    <title>Mission7.Main</title>
</head>
<body>
<?php

/* 
あ段を全て表示するプログラム
あ段が入ったListを使って一文字ずつ表示していく
いちいち書かなくても良くて楽
*/ 
function gyou($str, $int){
    $moji = $str;
    $num = $int;
    return "<li><form method=\"post\" name=\"form".$int."\" action=\"Dictionary.php\">
    <input type=\"hidden\" name=\"gyou\" value=\"".$moji."\">
    <a href=\"javascript:form".$int.".submit()\">".$moji."行</a></form></li>";
}
?>
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
    ＊平がな検索<br>
    <div id="gyou">
    <?php
        
    /*
    24行目のfanction()発動のためのList
    */
    $head = array("あ", "か", "さ", "た", "な", "は", "ま", "や", "ら", "わ");
    $count = 1;
    
    /*
    Listの中身を一つずつ入れながら、24行目のfanction()を起動して
    あ段に遷移するリンクを作成していく
    */
    foreach($head as $he){
        
        if($count == 6){
            echo "</div><br><div id=\"gyou\">";
        }
        if($count <=5){
            $a = gyou($he, $count);
            echo $a;
            $count++;
        }else{
            $a = gyou($he, $count);
            echo $a;
            $count++;
        }
    }
    ?>
    
    </div>
    </div>
    <div class="right">
    <ul id="menu">
    <li><a href="Login.php">ログイン</a></li>
    <li><a href="Registration.php">新規登録</a></li>
    <li><a href="NewWord.php">新語登録</a></li>
    <li><a href="Editing.php">編集機能</a></li>
    <li><a href="Controler.php">管理者ページ</a></li>
    
    </ul>
    </div>
    </div>
</div>
</body>
</html>
