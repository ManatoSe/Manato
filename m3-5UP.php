<?php
// グローバル変数
$file = "m3_5UP.txt";
$date = date("Y/m/d H:i:s");

// 投稿機能
if(!empty($_POST["comment"]) && !empty($_POST["name"])&&!empty($_POST["upda"])&&!empty($_POST["pass"])){
    
    //$file_arrはfopenより先に書かないといけないらしい
    $upda = $_POST["upda"];
    $file_arr = file($file);
    $fp = fopen($file, "w");
    $name1 = $_POST["name"];
    $comment1 = $_POST["comment"];
    $pass = $_POST["pass"];
    foreach ($file_arr as $row) {
        $r = explode("<>", $row);
        if ($r[0] == $upda&&$r[4]==$pass) {
            fwrite($fp, $r[0]."<>".$name1."<>".$comment1."<>".$date."<>".$pass."<>".PHP_EOL);
        }else{
            fwrite($fp, $row);
        }
    }
    fclose($fp);
    
}else if (!empty($_POST["comment"]) && !empty($_POST["name"])&&!empty($_POST["pass"])) {
    $comment = $_POST["comment"];
    $name = $_POST["name"];
    $pass = $_POST["pass"];
    $fp_all = fopen($file, "a");
    $data = file_get_contents($file);
    $file_data = explode("\n", $data);
    $count = count($file_data);
    fwrite($fp_all, $count++ . "<>" . $name . "<>" . $comment . "<>" . $date . "<>" .$pass."<>". PHP_EOL);
    fclose($fp_all);
}
    

//削除機能
if(!empty($_POST["deleteId"])&&!empty($_POST["pass1"])){
            $cut = $_POST["deleteId"];
            $pass = $_POST["pass1"];
            $lines = file($file);
            $fp = fopen($file,"w");
            $cou = 1;
            foreach ($lines as $line) {
                $de = explode("<>",$line);
                if ($cut == $de[0]&&$pass==$de[4]) {

                }else{
                    fwrite($fp,$cou."<>".$de[1]."<>".$de[2]."<>".$de[3]."<>".$de[4]."<>". PHP_EOL);
                    $cou++;
                }
            }
            fclose($fp);
}

//編集が押されたとき
if (!empty($_POST["updateId"])&&!empty($_POST["pass2"])) {
 $update = $_POST["updateId"];
 $pass = $_POST["pass2"];
 $name2="";
 $comment2="";
 $id2 = "";
 $file_arr = file($file);
 foreach ($file_arr as $row) {
     $r = explode("<>", $row);
  if ($r[0] == $update && $r[4]==$pass) {
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
    <form action="" method="post">
        編集対象番号：<input type="number" name="updateId"><br>
        パスワード：<input type="text" name="pass2">
        <input type="submit" value="編集"><br>
    </form>
 <br>

 <?php
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
