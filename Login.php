<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Mission6-2</title>
</head>
<body>
    ログインには、ユーザー名とパスワードが必要です。
    <form action="" method="post">
    ユーザー名:<input type="text" name="name"><br>
    パスワード:<input type="password" name="pass">
    <input type="submit" value="ログイン"><br>

    新規登録はこちら
    <form action="" method="post">
    登録する名前の入力:<input type="text" name="newname"><br>
    パスワードの設定:<input type="text" name="newpass">
    <input type="submit" value="新規登録"><br>
    </form>
    <br>
    <?php
        /*Sessionの開始
        Sessionはログイン処理で、他のページに飛んでも変数値を使えるようにするために必要。
        */ 
        session_start();

        // DB接続設定：自身のDBの接続をコピペ
        $dsn = 'mysql:dbname=データベース名;host=localhost';
        $user = '名前';
        $password = 'パスワード';
        $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        //テーブル作成：名前とパスワード
        $sql = "CREATE TABLE IF NOT EXISTS m62"
        ." ("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."pass char(32)"
        .");";
        $stmt = $pdo->query($sql);

        //新規登録の処理
        /*POSTで送る名前もしっかり確認 */
        if(!empty($_POST["newname"])&& !empty($_POST["newpass"])){
            
            /*新規登録方法
            前のMissionでやったDB登録を使い、名前とパスワードを流し込む
            テーブルのカラム名と違うものを入れないように気をつけて。*/
            $name1 = $_POST["newname"];
            $pas = $_POST["newpass"];
            
            $sql = $pdo -> prepare("INSERT INTO m62 (name, pass) VALUES (:name,  :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $name = $name1;
            $pass = $pas;
            $sql -> execute();
            echo "**************************************************<br>";
            echo "新規登録完了！".$name."さん、よろしくね！<br>";
            echo "**************************************************<br><br>";
        }

        //ログインの処理
        if(!empty($_POST["name"])&& !empty($_POST["pass"])){

            /*ログイン機能
            登録内容をSessionして、header()を使って画面遷移。
            header()の中身は絶対パスでいい。
             */
            $name = $_POST["name"];
            $pass = $_POST["pass"];
            /*DB「m62」を配列化しforeachでログインでPOSTした名前とパスワードが一致するものを取得し、Sessionに入れる */
            $sql = 'SELECT * FROM m62';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){

                /*名前とパスワードの一致を調べる。
                　['']の中がしっかりとテーブルのカラム名と同じか確認*/
                if($row['name']==$name && $row['pass']==$pass){

                    /*Sessionの書き方
                    $_SESSION['id']=$row['id']
                    左辺は変数名で他のファイルで「$id=$_SESSION['id']」で呼び出せる。（Main.php参照）
                    右辺はDB配列から獲得したいテーブル */
                    $_SESSION['id']=$row['id'];
                    $_SESSION['username'] = $row['name'];
                    $_SESSION['pass'] = $row['pass'];
                    /*上でSessionをスタートしたので、ここで閉じなければいけない。そういうもんだと思って、とにかく書いて。 */
                    session_write_close();

                    /*header("Locatin:絶対パス")で画面遷移し、exit();で必ず閉じる。
                    　・絶対パスの取得
                    　仮にMain.phpに遷移したい場合
                    　1：Main.phpを実行(これはLogin.phpなので別のファイルを作って実行するということ)
                    　2：何も処理を書いてなくてもURLは取得できる(「http～～～/Main.php」的なものが取得できるはずで、それが絶対パス)
                    　3：Location: の右側にコピペ */
                    header("Location:絶対パスを取得してそのまま書き込む");
                    exit();
                    break;
                }
            }
        }
    ?>
<h2>・登録方法</h2>
<strong>　新規登録はこちらから名前/パスワードを入力し、「新規登録」ボタンを押してください。<br>
　もし新規登録ができた場合、「新規登録完了」メッセージが出てきます。</strong>
<h2>・ログイン方法</h2>
<strong>既に新規登録が完了している場合は、名前/パスワードを入力し、ログイン処理を行ってください。</strong>

</body>
</html>