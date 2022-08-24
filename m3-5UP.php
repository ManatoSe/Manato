<!--・Mission3-5の書き方
    <php>  //編集
    <html> //投稿フォーム
    <php>  //投稿内容の表示

    コードは上から順番に読まれるので、使いたい変数は、その場所より必ず上で定義する。
    例：
    /*<?php
    $a=1;
    echo $a;
    echo $b;
    $b=2;
    ?>*/
    /**/で囲まれている部分では「echo $b」でエラーになる。$bがまだ定義されてないのに、呼び出そうとしてるから。

    今回で言えば、編集機能を実行する時に、フォームで名前、コメントを表示する必要があるから、
    編集をフォームで選択した時に実行する部分で表示するものを変数に代入しないといけないので、その部分だけはhtmlより上に記述しなければいけない。
-->

<?php
/*
・グローバル変数
$fileはどこでも使うので、if文の外で定義しておく方が無難。
$date（日付の取得）も2箇所で使うので外に書いておく。

if文の中の変数は、その外で使えないので、if文の外に出さないと、毎回書くことになるので大変。
例：
$a=1;
if(){$b=2;}
echo $a.$b;
これでは、$bは使えないのでエラーになる。
今回で言えば、編集機能で$fileを定義すると、投稿機能等で使えないので、毎回if(!empty~~~)の中に$fileを書かないといけなくなる。
*/

//$file名は好きに変えてOK
$file = "m3_5UP.txt";
//$dateの中身も好きに変えてOK
$date = date("Y/m/d H:i:s");

/*
if(編集機能)elseif(投稿機能)の順にする。
理由は、編集機能でも「(!empty($_POST["comment"]) && !empty($_POST["name"])&&!empty($_POST["pass"]))」の条件を満たしてしまうから。
編集機能では、これに加えて編集用の変数（このコードではupda）もPOSTで送られていないといけないので、先にif文で条件が一致するか判断し、不一致なら編集機能ではないと判断してもらう。
なので、Mission3-5のコードは
if(!empty($_POST["comment"]) && !empty($_POST["name"])&&!empty($_POST["upda"])&&!empty($_POST["pass"])){編集機能}
else if (!empty($_POST["comment"]) && !empty($_POST["name"])&&!empty($_POST["pass"])) {投稿機能}
で書けば良い。

削除機能はMission3-3のコピペで問題なし。

・編集の判断方法
編集機能のイメージ：
Ⅰ：上記の編集の前に、パスワードの一致を調べ、ID、名前、コメントを取得し、フォームに送る
Ⅱ：フォームの内容で、上記の編集機能を実行する
この2段階で行う。

詳細は各機能の中に書いていく。
*/

// 編集機能
/*コメント、名前、編集機能モード、パスワードがPOSTで送られたか調査*/
if(!empty($_POST["comment"]) && !empty($_POST["name"])&&!empty($_POST["upda"])&&!empty($_POST["pass"])){
    
    /*　$_POST[]をそれぞれ変数に代入する　*/
    $upda = $_POST["upda"];
    $name1 = $_POST["name"];
    $comment1 = $_POST["comment"];
    $pass = $_POST["pass"];
    
    /*　fileを配列にする→ファイルを開く
    　　まず、fileを配列する。理由は、fopen("w")の時、ポインタが先頭に行くので、その後に配列にすると、ファイルの中身が取得できないから。
      　("w")で開くときは必ず配列を先にしておこう。　*/
    $file_arr = file($file);
    $fp = fopen($file, "w");
    
    /*　foreachで回しながらIDが編集機能モードで送った「自分が送った番号」と一致するかどうか調べる。
    　　一致したら編集、それ以外はそのまま書き写し。　*/
    foreach ($file_arr as $row) {
        $r = explode("<>", $row);
        /*$r[0]はID $r[1]は名前 $r[2]はコメント $r[3]は日付 $r[4]はパスワード　になっているよ。*/
        
        /*IDと一致してるか調べるif文*/
        if ($r[0] == $upda) {
            /*$r[0]でIDはそのまま、名前、コメント、日付（更新したい場合のみで、編集した時間は記憶しないなら$[3]で良い）、パスワードは新しいものに。*/
            fwrite($fp, $r[0]."<>".$name1."<>".$comment1."<>".$date."<>".$pass."<>".PHP_EOL);
        }else{
            /*そのまま*/
            fwrite($fp, $row);
        }
    }
    fclose($fp);
    
}
//投稿機能
/*コメント、名前、パスワードだけが送られてきたとき、それらをfwrite()で書く（Mission3-2にパスワードだけ追加）*/
else if (!empty($_POST["comment"]) && !empty($_POST["name"])&&!empty($_POST["pass"])) {
    $comment = $_POST["comment"];
    $name = $_POST["name"];
    $pass = $_POST["pass"];
    $fp = fopen($file, "a");
    $cou = count(file( $file));
    $count=$cou+1;
    fwrite($fp, $count . "<>" . $name . "<>" . $comment . "<>" . $date . "<>" .$pass."<>". PHP_EOL);
    fclose($fp);
}
    

//削除機能
/*IDとパスワードが送られてきた時に実行する（Mission3-3にパスワードを追加）*/
if(!empty($_POST["deleteId"])&&!empty($_POST["pass1"])){
            $cut = $_POST["deleteId"];
            $pass = $_POST["pass1"];
            $lines = file($file);
            $fp = fopen($file,"w");
            /*削除したときに番号がめちゃくちゃになる疑問の解決
            　$cou=1を用意し、fwriteのときにID（$de[0]）のところに$couを入れる。
            　$couが使われた（fwriteが実行された）とき、$cou++を下に書き、カウントを1ずつ増やしていくことで番号が飛んだり、同じ数字になることはない。*/
            $cou = 1;
            foreach ($lines as $line) {
                $de = explode("<>",$line);
                
                /*番号とパスワードが一致したときのみ書き込まない*/
                if ($cut == $de[0]&&$pass==$de[4]) {

                }else{
                    fwrite($fp,$cou."<>".$de[1]."<>".$de[2]."<>".$de[3]."<>".$de[4]."<>". PHP_EOL);
                    $cou++;
                }
            }
            fclose($fp);
}

//編集が押されたとき
/*編集フォームから、IDとパスワードをPOSTで取得する*/
if (!empty($_POST["updateId"])&&!empty($_POST["pass2"])) {
    
    /*POSTで送ったものを変数に代入*/
 $update = $_POST["updateId"];
 $pass = $_POST["pass2"];
    
    /*htmlで編集時に投稿フォームに内容を表示するために使う変数を定義（中身は今は入れない）*/
 $name2="";
 $comment2="";
 $id2 = "";
    
    /*fileを配列にして編集したい番号とそのパスワードが一致したときに、上で定義した変数に各情報を定義する。*/
 $file_arr = file($file);
 foreach ($file_arr as $row) {
     $r = explode("<>", $row);
     
     /*IDとパスワードの一致を調べるif文*/
  if ($r[0] == $update && $r[4]==$pass) {
      
      /*IDとパスワードが一致したときの、名前、コメント、IDを代入。これらはhtmlで使ってるよ！*/
      $name2 = $r[1];
      $comment2 = $r[2];
      $id2 = $r[0];
  }
 }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

<body>
    
    <!--
    if(!empty($_POST["updateId"])){echo $name2;}のように、編集フォームが押されたときのみ名前、コメント、IDを取得する。
    ただし、IDは変更したくないので、type="hidden"としてブラウザには表示せずに値だけ内部で取得する。
    パスワードに関しては、個人的には必ず変更してもらいたいのと、セキュリティ的に表示したくないので、取得せず、投稿機能と同じ状態にしている。
    -->
 <form action="" method="post">
  名前：<input type="text" name="name" value="<?php if(!empty($_POST["updateId"])){echo $name2;}?>"><br>
  コメント：<input type="text" name="comment" value="<?php if(!empty($_POST["updateId"])){echo $comment2;}?>"><br>
  パスワード：<input type="text" name="pass">
  <input type="submit" value="送信">
  <input type="hidden" name="upda" value="<?php if (!empty($_POST["updateId"])) { echo $id2;} ?>" />
 </form>
 <br>
 <form action="" method="post">
        削除対象番号：<input type="number" name="deleteId"><br>
        パスワード：<input type="text" name="pass1">
        <input type="submit" value="削除"><br>
    </form>
    
    <!--投稿用のフォームで使われているupdateIdというのはここでPOSTで送ったもの。
    　　formの送信では、毎度最初からコードを読み直すので、ここで利用しているupdateIdも上のphpやformでも使えるよ。-->
    <form action="" method="post">
        編集対象番号：<input type="number" name="updateId"><br>
        パスワード：<input type="text" name="pass2">
        <input type="submit" value="編集"><br>
    </form>
 <br>

    

 <?php
    /*全部のファイル内容を表示するphp。一番下の方が見やすいかなと思うので、下に書く。*/
 if(file_exists($file)){
 $file_arr = file($file);
 foreach ($file_arr as $file_txt) {
  $file_split = explode("<>", $file_txt);
  $id = $file_split[0];
  $name = $file_split[1];
  $comment = $file_split[2];
  $date = $file_split[3];  
  print_r($id . "：" . $name . "「" . $comment . "」" . $date);
  echo "<br>";
 }
 }
 ?>
</body>

</html>
