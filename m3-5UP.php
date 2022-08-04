<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>コメント</title>
</head>
<body>
    <h1>ようこそ！Manato制作の掲示板へ！</h1>
    <p>名前・コメント(何でも良い)・パスワード(削除と編集に必要なので覚えてね,aaaとかで大丈夫)、全て入力してボタンを押すと下部に投稿内容が表示される<br>
    削除は、対象番号とパスワードの両方を入力し、編集はそれに追加で編集内容を入力することで実行可能</p>
    <form action="" method="post">
        <p>名前を入力</p>
        <input type="text" name="name"><?php if(isset($editname)) {echo $editname;} ?>
        <p>コメントの入力</p>
        <input type="text" name="str"><?php if(isset($editstr)) {echo $editstr;} ?>
        <p>パスワードを設定してください</p>
        <input type="text" name="pass">
        <input type="submit" value="コメント"><br>
    </form>
    <p>------------------------------------------------------------------------------------</p>
    <form action="" method="post">
        <p>削除したい番号を指定</p>
        <input type="number" name="cut">
        <p>そのパスワードを入力してください</p>
        <input type="text" name="pass1">
        <input type="submit" value="削除"><br>
    </form>
    <p>------------------------------------------------------------------------------------</p>
    <form action="" method="post">
        <p>編集したい番号を指定</p>
        <input type="number" name="edi">
        <p>編集するコメントの入力</p>
        <input type="text" name="edistr">
        <p>そのパスワードを入力してください</p>
        <input type="text" name="pass2">
        <input type="submit" value="編集"><br>
    </form>
    <p>------------------------------------------------------------------------------------</p>
    <?php
        $num = 1;
        $date = date("Y/m/d/ H:i:s");
        $filename="m3-5.txt";
        
        
        if(!empty($_POST["name"])&& !empty($_POST["str"]) && !empty($_POST["pass"])){
            $name = $_POST["name"];
            $str = $_POST["str"];
            $pass = $_POST["pass"];
            
            $fp = fopen($filename,"a");
            $count = count( file( $filename ) );
            if($count >= 1){
                $num = $num + $count;
            }
            fwrite($fp, $num."<>".$name."<>".$str."<>".$date."<>".$pass."<>".PHP_EOL);
            fclose($fp);
            
        } else if( !empty($_POST["edi"]) && !empty($_POST["edistr"]) && !empty($_POST["pass2"])){
            $edi = $_POST["edi"];
            $edistr = $_POST["edistr"];
            $pass2 = $_POST["pass2"];
            $lines = file($filename);
            $fp = fopen($filename, "w");
            foreach ($lines as $line) {
                $de = explode("<>", $line);
                if ($de[0] == $edi) {
                    if($pass2 == $de[4]){
                        fwrite($fp, $edi."<>".$de[1]."<>".$edistr."<>".$date."<>".$de[4]."<>".PHP_EOL);
                    }else{
                        fwrite($fp, $edi."<>".$de[1]."<>".$de[2]."<>".$de[3]."<>".$de[4]."<>".PHP_EOL);
                    }
                } else {
                    fwrite($fp, $line);
                }
            }
            fclose($fp);
            
        }
        if(!empty($_POST["cut"]) && !empty($_POST["pass1"])){
            $cut = $_POST["cut"];
            $pass1 = $_POST["pass1"];
            $lines = file($filename);
            $fp = fopen($filename,"w");
            $cou = 1;
            foreach ($lines as $line) {
                $de = explode("<>",$line);
                if ($cut == $de[0] && $pass1 == $de[4]) {
                    
                }else{
                    fwrite($fp,$cou."<>".$de[1]."<>".$de[2]."<>".$de[3]."<>".$de[4]."<>".PHP_EOL);
                    $cou++;
                }
                
            }
            fclose($fp);
            
        }
        if(file_exists($filename)){
            $lines = file($filename,FILE_IGNORE_NEW_LINES);
            foreach($lines as $line){
                $ex = explode("<>", $line);
                echo $ex[0]."：".$ex[1]."「".$ex[2]."」".$ex[3]."<br>";
            }
        }
        
    ?>
</body>
</html>