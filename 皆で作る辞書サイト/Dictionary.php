<?php
    include('./IncludeModel.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Design.css">
    <title>Mission7.Dictionary</title>
</head>
<body>
    <?php
    $gyou = "";
    if(!empty($_POST["gyou"])){
        $gyou = $_POST['gyou'];
    }
    ?>
    <?php
    function result($str, $int){
        $moji = $str;
        $num = $int;
        $word1 = array("");
        return "<li><form method=\"post\" name=\"form".$int."\" action=\"Meaning.php\">
        <input type=\"hidden\" name=\"moji\" value=\"".$moji."\">
        <a href=\"javascript:form".$int.".submit()\">".$moji."</a></form></li>";
        
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
    
    <div id="gyouD">
    <?php
    echo $gyou."行一覧<br>";
    $moji = $gyou;
    $word1 = array("");
    if($moji == "あ"){
            $word1 = array("あ", "い", "う", "え", "お");
        }else if($moji == "か"){
            $word1 = array("か", "き", "く", "け", "こ");
        }else if($moji == "さ"){
            $word1 = array("さ", "し", "す", "せ", "そ");
        }else if($moji == "た"){
            $word1 = array("た", "ち", "つ", "て", "と");
        }else if($moji == "な"){
            $word1 = array("な", "に", "ぬ", "ね", "の");
        }else if($moji == "は"){
            $word1 = array("は", "ひ", "ふ", "へ", "ほ");
        }else if($moji == "ま"){
            $word1 = array("ま", "み", "む", "め", "も");
        }else if($moji == "や"){
            $word1 = array("や", "ゆ", "よ");
        }else if($moji == "ら"){
            $word1 = array("ら", "り", "る", "れ", "ろ");
        }else if($moji == "わ"){
            $word1 = array("わ", "を", "ん");
        }
    $cou = 0;
    foreach($word1 as $w){
        $res = result($w, $cou);
        echo $res;
        $cou++;
    }
    
    ?>
    
    </div>
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