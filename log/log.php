<?php
header('Content-Type: text/html; charset=UTF-8');

date_default_timezone_set('Asia/Tokyo');
// ファイルの指定
$dataFile = './log.txt';
//一行ずつデータを取り出して配列に入れる
$post_list = file($dataFile,FILE_IGNORE_NEW_LINES);
//逆順に並べ替える
$post_list = array_reverse($post_list);
//$post_list =  mb_convert_encoding($post_list, "utf-8");

?>
<!DOCTYPE html>
<html>
<body bgcolor="#EDF7FF"> 
<meta charset="utf-8">
<title>アクセスログ</title>
</head>
<style>
#lv1{
  background:#EDF7FF;
}
#lv2{
  background:#FFFACD;
}
#lv3{
  background:#FA8072;
}
</style>
<body>
<?php 
$today = date("Y-m-d H:i:s");
print_r($today . '<br>');
printf('<br><a href="./log.txt" target="_blank">オリジナルデータを表示する</a><br><br>');


$counter_file = './counter.txt';
$counter_lenght = 8;
$fp = fopen($counter_file, 'r+');

if ($fp){
  if (flock($fp, LOCK_EX)){
    $counter = fgets($fp, $counter_lenght);
    flock($fp, LOCK_UN);
  }
}
fclose($fp);
print('累計検索回数 : '.$counter." 回");
echo "<br>";

$num = 0;
if(!empty($post_list)){
    foreach($post_list as $post){
        if($num >= 500){//1000行分だけ表示(１ログ=10行) よって50ログ分を表示する
            break;
        }
        
        // $post =  mb_convert_encoding($post, "SJIS","UTF-8");
        echo $post;?><br><?php
        $num += 1;

    }
}
?>