<?php
/*
書き方はMission3-5と同じで、ファイル操作をDB(Mission4)に書き換えるだけ。
htmlのPOSTを送るフォーム、if(!empty~~~)の部分はMission3-5をコピペして残して、if文の書き込み、削除、編集のコードを書き換える。
*/
        /*
        ・グローバル変数
　      　Mission3-5同様に、どこでも使うものはif文の外に定義しておくのが無難。
        　今回は$date(日付を扱う)、データベースの接続設定。あとテーブルの作成もしておく。
        　$dsn, $user, $passwordはMission4で各自確認したものを入れる。
        */
        $date = date("Y/m/d/ H:i:s");

        // DB接続設定
        $dsn = 'データベース';
        $user = 'ユーザー';
        $password = 'パスワード';
        $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        //テーブル作成
        /*
        　"CREATE TABLE IF NOT EXISTS 名前"で「名前」というDBのテーブルを作る。
        　テーブルに加えるもの
         　id：1から順に番号を付ける
           name：登録する名前
           comment：登録するコメント
           time：日付、後で$dateを入れる
           pass：登録するパスワード
      　　
        */
        $sql = "CREATE TABLE IF NOT EXISTS m51"
        ." ("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."comment TEXT,"
        ."time TEXT,"
        ."pass char(32)"
        .");";
        $stmt = $pdo->query($sql);

        //編集機能
        /*
        POSTで送られてきたものを変数に入れる。変数名は何でもいいが、できればテーブルと同じ名前にした方が分かりやすい。
        編集の方法：
        　'UPDATE m51 SET name =:name,comment=:comment,time=:time, pass=:pass WHERE id=:id';
          UPDATE DBのテーブル名(CREATEで指定した名前) 
          SET テーブルの各データ名(name=:name等) でWHEREで探した場所のデータを取得
          WHERE id=:id(idが一致するものを探索)
          
          $stmt = $pdo->prepare($sql);でstmtにデータをそれぞれ入力
          $stmt->bindParam(~~~)でデータを書き換える
          　(':name', $name, PDO::PARAM_STR)
            「:name」はUPDATEで取得したデータで、$nameはPOSTで送られたものを入れた変数で、前者を後者に差し替える指示を出してる。
        */
        if(!empty($_POST["name"])&& !empty($_POST["comment"]) && !empty($_POST["pass"])&&!empty($_POST["upda"])){
            
            /*$変数名はできればテーブル名と同じ方が良いよ！*/
            $id = $_POST["upda"];
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $pass = $_POST["pass"];
            $time = $date;
            $sql = 'UPDATE m51 SET name =:name,comment=:comment,time=:time, pass=:pass WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            
            /*上記で変数名とテーブル名を一致させると、:nameと$nameみたいに分かりやすい。それだけだけど見やすさは大事*/
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':time', $time, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
        }
        //投稿機能
        /*
        POSTで送ったものを変数に代入する。
        prepareで書き込むようのテーブルを取得する。(idは33行目で「INT AUTO_INCREMENT PRIMARY KEY」にしているので自動だから、idは不要)
        bindParamは編集の時とは違う。
        「:name」はテーブル名、「$name」は引数で、ここに下で代入して流し込む。今回では「$name=$name1」のようなものをbindParamの下に書く。
        */
        else if(!empty($_POST["name"])&& !empty($_POST["comment"]) && !empty($_POST["pass"])){
            
            /*POSTで送られたものを変数に代入。今度は変数名とテーブル名が同じだと同じ変数を定義することになり分かりづらいので、別の名前を付ける。*/
            $name1 = $_POST["name"];
            $com1 = $_POST["comment"];
            $pas = $_POST["pass"];
            
            /*「:name」はテーブル名、$nameは後で値を代入するための変数名。ここ以下で代入。*/
            $sql = $pdo -> prepare("INSERT INTO m51 (name, comment,time,pass) VALUES (:name, :comment, :time, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':time', $time, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            
            /*bindParamで定義した$変数名に、POSTで送られたものと$dateをそれぞれ代入。*/
            $name = $name1;
            $comment = $com1;
            $time = $date;
            $pass = $pas;
            $sql -> execute();
        }

        //編集の調査
        /*
        Mission3-5と考え方は同じ。
        ファイルの部分はDBに変更する。
        'SELECT * FROM m51'でファイルを選択する。
        配列にして、foreachで回しながら、idとpasswordが一致するか調べ、一致したものの名前とコメントを既に定義した変数に代入。
        */
        if( !empty($_POST["edi"]) &&  !empty($_POST["pass2"])){
            
            /*POSTで送ったものを変数に代入*/
            $update = $_POST["edi"];
            $pass = $_POST["pass2"];
            
            /*変数の定義（Mission3-5同様、必ずforeachよりも外に。）*/
            $name2="";
            $comment2="";
            $id2 = "";
            
            /*DBを配列にするためのコード。*/
            $sql = 'SELECT * FROM m51';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            
            /*
            foreachで回して探索。
            データベース内の値の取得には['テーブル名前']と書く。
            今回では$row['id']のように。
            意味は$rowは配列の何番目か、['id']はその番号に入ってるIDは何か。
            */
            foreach ($results as $row){
                if($update == $row['id']){
                    if($pass == $row['pass']){
                        $name2=$row['name'];
                        $comment2=$row['comment'];
                        $id2 = $row['id'];
                    }
                }
            }
            
        }
        
        //削除機能
        /*
        Mission4参照。
        注意点は、「'delete from m51 where id=:id && pass=:pass'」の書き方
        deleat from DB名
        where　何が一致した時に削除するか（今回はidとpass、where複数選択の時は「&&」を使う）
        */
        if(!empty($_POST["cut"]) && !empty($_POST["pass1"])){
            $id = $_POST["cut"];
            $pass = $_POST["pass1"];
            $sql = 'delete from m51 where id=:id && pass=:pass';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->execute();
        }
        
        
    ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>コメント</title>
</head>
<body>
    
    <h1>ようこそ！掲示板へ！</h1>
    <form action="" method="post">
        名前：<input type="text" name="name" value="<?php if(!empty($_POST["edi"])){echo $name2;}?>">
        コメント：<input type="text" name="comment" value="<?php if(!empty($_POST["edi"])){echo $comment2;}?>">
        パスワード:<input type="text" name="pass">
        <input type="hidden" name="upda" value="<?php if (!empty($_POST["edi"])) { echo $id2;} ?>" />
        <input type="submit" value="コメント"><br>
    </form>
    <p>------------------------------------------------------------------------------------</p>
    <form action="" method="post">
        削除したい番号を指定<input type="number" name="cut"><br>
        そのパスワードを入力<input type="text" name="pass1">
        <input type="submit" value="削除"><br>
    </form>
    <p>------------------------------------------------------------------------------------</p>
    <form action="" method="post">
        編集したい番号を指定<input type="number" name="edi"><br>
        そのパスワードを入力<input type="text" name="pass2">
        <input type="submit" value="編集"><br>
    </form>
    <p>------------------------------------------------------------------------------------</p>
    
    
    <?php
        /*Mission4参照*/
        $sql = 'SELECT * FROM m51';
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

<!--
追記1：
今回は、削除機能を使っても、SQLの「AUTO_INCREMENT PRIMARY KEY」を使っているので、数字が戻って同じ数字（例えば1、3、3）になることはない。
逆に言えば、削除したところに穴ができる(例えば1、3、4で「2」にもう入れられない)が、気にしないなら、無視していい。というか、今回は無視してた。
ただ、表示するときに数字が飛んでるのが、見た目が悪いと思うなら、
最後の<?php?>の表示機能のところで、foreachの前に「$count=1;」を定義して、foreach内部で「$count++」しながら回して、「$row['id']」を「$count」に差し替えれば、見た目は修正できる。
ただし、編集機能、削除機能でIDは必要なので、後ろにくっつけないと、本来操作したいものと違うものを操作することになるので気をつけて。
例：
    <?php
        $count=1;
        $sql = 'SELECT * FROM m51';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $count.':';
            echo $row['name'].'「';
            echo $row['comment'].'」';
            echo $row['time'].'(ID';
            echo $row['id'].')<br>';
            echo "<hr>";
        }
    ?>

別パターンは、「AUTO_INCREMENT PRIMARY KEY」を使わない方法かな。
それで、Mission3-5同様、削除では削除機能使わずに、DBを配列化、DB自体削除、foreachか何かで配列データ取り出しながら、書き込み機能でIDを前に詰めていく。
でもコードも多くなるし、気にしないのが一番だと思う。時間あればやってみて。そしてできたら皆に共有お願い。
-->
