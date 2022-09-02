<?php
    /*Login.phpでSessionしたものを変数に入れる */
    session_start();
    $id = $_SESSION['id'];
    $name2 = $_SESSION['username'];
    $pass1 = $_SESSION['pass'];
    session_write_close();

    // DB接続設定
    $dsn = 'mysql:dbname=名前;host=localhost';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
?>

<?php
        $date = date("Y/m/d/H:i:s");
        /*Loginとは別のテーブル作成
        Mission5-1と同じのを使えば良いけど(一番安全だからむしろ推奨)、↓のCREATEする名前は変えてね。*/
        $sql = "CREATE TABLE IF NOT EXISTS comment62"
        ." ("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."comment TEXT,"
        ."time TEXT,"
        ."pass char(32)"
        .");";
        $stmt = $pdo->query($sql);
        
        /*DBの書き込み
        nameとpassはPOSTで受け取ってたと思うけど、それを一番上のSessionでいれた変数に変えればOK！
        その他テーブルの違いとかは調整する必要はある。
        */
        if(!empty($_POST["comment"]) && !empty($_POST["upda"])){
            $edi = $_POST["upda"];
            $ediname = $name2;
            
            /*<textarea>で送った場合は以下のような記述になる(nl2br()で改行コードを書き込む)
            <input>で今まで通りなら、nl2br()は取り外して*/
            $edistr = nl2br($_POST["comment"]);
            $pass = $pass1;

            $id = $edi;
            $name = $ediname;
            $comment = $edistr;
            $time = $date;
            $sql = 'UPDATE comment62 SET name =:name,comment=:comment,time=:time, pass=:pass WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':time', $time, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
        }else if(!empty($_POST["comment"])){

            //name1とpasには、Sessionで送ってきた変数を代入する。
            $name1 = $name2;
            /*<textarea>で送った場合は以下のような記述になる(nl2br()で改行コードを書き込む)
            <input>で今まで通りなら、nl2br()は取り外して*/
            $str1 = nl2br($_POST["comment"]);
            $pas = $pass1;
            
            $sql = $pdo -> prepare("INSERT INTO comment62 (name, comment,time,pass) VALUES (:name, :comment, :time, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':time', $time, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $name = $name1;
            $comment = $str1;
            $time = $date;
            $pass = $pas;
            $sql -> execute();
        }
        //編集機能
        if( !empty($_POST["edi"])){
            $update = $_POST["edi"];
            $comment2="";
            $id2 = "";
            $sql = 'SELECT * FROM comment62';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //POSTで送った数字が一致するIDを探索
                if($update == $row['id']){
                    //一番上でSessionした$pass1と一致するパスワードを探索
                    if($pass1 == $row['pass']){
                        $comment2=$row['comment'];
                        $id2 = $row['id'];
                    }
                }
            }
            
        }
        if(!empty($_POST["cut"])){
            $id = $_POST["cut"];
            //Sessionした$pass1を$passに入れる。ここ以外はMission5-1と同じ
            $pass = $pass1;
            $sql = 'delete from comment62 where id=:id && pass=:pass';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->execute();
        }


    ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Mission6-2</title>
</head>
<body>
    　<a href="Login.php">ログイン画面に戻る</a><br>
    --------------------------------------------------------------------------------------------------------------------<br>
    
    <?php echo $name2;//Sessionしてるから他のファイルの値を使える ?>さん、何をしますか？<br>
    <!--名前、パスワードは入力しなくていい。
        Sessionしたものを入れられるから。-->
    <form action="" method="post">
        【コメントの入力】<br><!--<textarea>は改行を含む文章を投稿できるもの
                            valueは存在しないので、編集時にコメントを表示するには以下のような記述が必要
                            無理して<textarea>を使う必要はないので、今まで通りでも大丈夫。-->
        <textarea name="comment" rows="3" cols="50" maxlength="100"><?php if(!empty($_POST["edi"])){echo $comment2;}?></textarea>
        <input type="hidden" name="upda" value="<?php if (!empty($_POST["edi"])) { echo $id2;} ?>" />
        <input type="submit" value="コメント"><br>
    </form>
    <!--パスワードの入力はいらない。
        Sessionで登録したパスワードと照合するから。-->
    <form action="" method="post">
        【削除したい番号】<br>
        <input type="number" name="cut">
        <input type="submit" value="削除"><br>
    </form>
    -------------------------------------------------------------------------------------------------------------------
    <!--パスワードの入力はいらない。
        Sessionで登録したパスワードと照合するから。-->
    <form action="" method="post">
        【編集したい番号】<br>
        <input type="number" name="edi">
        <input type="submit" value="編集"><br>
    </form>
    
    -------------------------------------------------------------------------------------------------------------------
    <h2>Comments</h2>
    
    
    <?php
        /*コメント表示のための処理 Mission5-1(何か変えてるならそこは気をつけて。)*/
        $sql = 'SELECT * FROM comment62';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].':';
            echo $row['name'].'「';
            echo $row['comment'].'」';
            echo $row['time'].'<br>';
            echo "<hr>";
        }
    ?>
</body>
</html>