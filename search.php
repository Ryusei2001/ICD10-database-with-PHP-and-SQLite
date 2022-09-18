<title>検索結果 | <?php echo $_GET["search_name"]?></title>
<link rel="stylesheet" href="nomal.css">
<meta name="viewport" content="width=device-width, user-scale=yes, initial-scale=1.0, maximum-scale=5.0" />

<?php

if (isset($_COOKIE['history'])) {
    foreach ($_COOKIE['history'] as $value) {
        $i += 1;
    }
}
$i += 1;
if($i > 5){
    $val = $_COOKIE['history'];

    for($delete = -1; $delete < 100; $delete++){
        setcookie("history[$i]", '', time() - 1);
        }

    setcookie("history[1]",$val[2],time()+60*60*24*7);
    setcookie("history[2]",$val[3],time()+60*60*24*7);
    setcookie("history[3]",$val[4],time()+60*60*24*7);
    setcookie("history[4]",$val[5],time()+60*60*24*7);
    setcookie("history[5]",$val[6],time()+60*60*24*7);
}
setcookie("history[$i]",$_GET['search_name'],time()+60*60*24*7);



header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('Asia/Tokyo');
$type = $_GET['search_type'];
echo "データベースバージョン2.5";
try{
    $pdo = new PDO("sqlite:newdatabase_ver.3.db");

    //SQL文を実行して、結果を$stmtに代入する。
    if(empty($_GET["search_name"])){
        $stmt = $pdo->prepare(" SELECT * FROM users ORDER BY icd10_code ASC LIMIT 0");
    }else if($type == 2){//文字数の少ない順
        $stmt = $pdo->prepare(" SELECT * FROM users WHERE name LIKE ? OR name_hankaku LIKE ? OR icd10_code LIKE ? ORDER BY LENGTH(name) ASC,icd10_code ASC "); 
    }else if($type == 3){//●を含んでいる中で検索
        $stmt = $pdo->prepare(" SELECT * FROM users WHERE icd10_code LIKE '%●%' AND (name LIKE ? OR name_hankaku LIKE ? OR icd10_code LIKE ? ) ORDER BY icd10_code ASC ");
    }else if($type == 4){//●を含んでいない中で検索
        $stmt = $pdo->prepare(" SELECT * FROM users WHERE icd10_code NOT LIKE '%●%' AND (name LIKE ? OR name_hankaku LIKE ? OR icd10_code LIKE ? ) ORDER BY icd10_code ASC ");
    }else{//通常検索
        $stmt = $pdo->prepare(" SELECT * FROM users WHERE name LIKE ? OR name_hankaku LIKE ? OR icd10_code LIKE ? ORDER BY icd10_code ASC ");
        //$stmt->bindValue(1, $_GET[search_name], PDO::PARAM_STR);
        //$stmt->bindValue(1, '%' . preg_replace('/(?=[!_%])/', '!', $_GET[search_name) . '%', PDO::PARAM_STR);
    }    
    $stmt->bindValue(1, '%' . addcslashes($_GET['search_name'], '\_%') . '%', PDO::PARAM_STR);
    $stmt->bindValue(2, '%' . addcslashes($_GET['search_name'], '\_%') . '%', PDO::PARAM_STR);
    $stmt->bindValue(3, '%' . addcslashes($_GET['search_name'], '\_%') . '%', PDO::PARAM_STR);
    $stmt->execute();
    echo "[データ取得 : OK]";

} catch(PDOException $e){
    echo "データ取得 : 失敗" . $e->getMessage() . "\n";
    exit();
}
?>
<br>
<?php
try{

    //how many hits
    //$sql = "SELECT COUNT(icd10_code) FROM users WHERE name LIKE '%" . $_GET["search_name"] . "%' OR icd10_code LIKE '%" . $_GET["search_name"] . "%'";

    if ($type == 1){
        $sql = "SELECT COUNT(icd10_code) FROM users WHERE name LIKE '%" . $_GET["search_name"] . "%' OR name_hankaku LIKE '%" . $_GET["search_name"] . "%' OR icd10_code LIKE '%" . $_GET["search_name"] . "%' ORDER BY icd10_code ASC ";
    }else if($type == 2){//文字数の少ない順
        $sql = "SELECT COUNT(icd10_code) FROM users WHERE name LIKE '%" . $_GET["search_name"] . "%' OR name_hankaku LIKE '%" . $_GET["search_name"] . "%' OR icd10_code LIKE '%" . $_GET["search_name"] . "%' ORDER BY LENGTH(name) ASC,icd10_code ASC "; 
    }else if($type == 3){//●を含んでいる中で検索
        $sql = "SELECT COUNT(icd10_code) FROM users WHERE icd10_code LIKE '%●%' AND ( name LIKE '%" . $_GET["search_name"] . "%' OR name_hankaku LIKE '%" . $_GET["search_name"] . "%' OR icd10_code LIKE '%" . $_GET["search_name"] . "%' ) ORDER BY icd10_code ASC ";
    }else if($type == 4){//●を含んでいない中で検索
        $sql = "SELECT COUNT(icd10_code) FROM users WHERE icd10_code NOT LIKE '%●%' AND ( name LIKE '%" . $_GET["search_name"] . "%' OR name_hankaku LIKE '%" . $_GET["search_name"] . "%' OR icd10_code LIKE '%" . $_GET["search_name"] . "%' ) ORDER BY icd10_code ASC ";
    }else{
        $sql = "SELECT COUNT(icd10_code) FROM users WHERE name LIKE '%" . $_GET["search_name"] . "%' OR name_hankaku LIKE '%" . $_GET["search_name"] . "%' OR icd10_code LIKE '%" . $_GET["search_name"] . "%' ORDER BY icd10_code ASC ";
    }
    
    
    $sth = $pdo -> query($sql);
    $count = $sth -> fetch(PDO::FETCH_COLUMN);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if($count == 0){
        $NOT_FOUND_flag = 1;//1回目の検査（後ろを無視していく）
        $i = -1;
        $similar_word = $_GET["search_name"];
        while($count_similar <= 0){
            if(mb_strlen($similar_word) <= 2){
                $NOT_FOUND_flag = 2; //本当に見つからなかった。
                break;
            }
            $similar_word = mb_substr($_GET["search_name"], 0, $i, "UTF-8");
            $sql = "SELECT COUNT(icd10_code) FROM users WHERE name LIKE '%" . $similar_word . "%' OR icd10_code LIKE '%" . $similar_word . "%' ";

            $sth = $pdo -> query($sql);
            $count_similar = $sth -> fetch(PDO::FETCH_COLUMN);
            $i -= 1;

        }
        
        $stmt = $pdo->prepare(" SELECT * FROM users WHERE name LIKE '%" . $similar_word . "%' OR icd10_code LIKE '%" . $similar_word . "%' ORDER BY LENGTH(name) ASC,icd10_code ASC ");
        $stmt->execute();

        if($NOT_FOUND_flag == 2){
            $NOT_FOUND_flag = 1;//もう一回検査する（前を無視していく）
            $similar_word = $_GET["search_name"];            
            $i = 1;
            while($count_similar <= 0){
                if(mb_strlen($similar_word) <= 2){
                    $NOT_FOUND_flag = 2; //本当に見つからなかった。
                    break;
                }
                $similar_word = mb_substr($_GET["search_name"], $i,mb_strlen($_GET["search_name"]), "UTF-8");
                $sql = "SELECT COUNT(icd10_code) FROM users WHERE name LIKE '%" . $similar_word . "%' OR icd10_code LIKE '%" . $similar_word . "%' ";
                $sth = $pdo -> query($sql);
                $count_similar = $sth -> fetch(PDO::FETCH_COLUMN);
                $i += 1;
            }
        }
        $stmt = $pdo->prepare(" SELECT * FROM users WHERE name LIKE '%" . $similar_word . "%' OR icd10_code LIKE '%" . $similar_word . "%' ORDER BY LENGTH(name) ASC,icd10_code ASC ");
        $stmt->execute();

        if($NOT_FOUND_flag == 2){
            $pdo = new PDO("sqlite:newdatabase_ver.4.db");
            $sqlSimilarReset = $pdo->prepare("UPDATE `users` SET `similar` = NULL "); 
            $sqlSimilarReset->execute();
            $search_word = $_GET["search_name"];
            $i=1;
                for($i=1;$i<=38727;$i++){//9953 OR 38727
                    $word = "A".$i;
                    //echo $word."<br>";
                    $sql = $pdo->prepare(" SELECT * FROM `users` WHERE `id` = '" . $word . "' "); 
                    $sql->execute();
                    foreach($sql as $value):
                        $sim = similar_text($search_word, $value['name'], $percent);
                    $percent = round($percent);
                    //類似度である程度フィルターをかける
                    if($percent >= 50){
                        $sqlSimilarUpdate = $pdo->prepare("UPDATE `users` SET `similar` = '$percent' WHERE id = '" . $word . "' "); 
                        $sqlSimilarUpdate->execute();
                    }
                    endforeach;
                }
            $sim = similar_text($search_word, $b, $percent);
            $stmt = $pdo->prepare("SELECT `similar`,`icd10_code`,`name`,`大分類`,`中分類` FROM `users` WHERE `similar` IS NOT NULL ORDER BY `similar` DESC LIMIT 20"); 
            $stmt->execute();
        }




    }


} catch(PDOException $e){
    echo "失敗:" . $e->getMessage() . "\n";
    exit();
}

?>
<br>
<h1>
<?php
    if(empty($_GET["search_name"])){
        echo '<FONT COLOR="red"> 検索ワードを入力してください </FONT>';
    }else{
        echo "「".$_GET["search_name"]."」";
        if($type == 2){
            echo " をヒット率の高い順";
        }else if($type == 3){
            echo " をレセプト電算システムマスタのみ";
        }else if($type == 4){
            echo " をレセプト電算システムマスタを含まない";
        }
    }

?>
</h1>
<?php
    if(!empty($_GET["search_name"])){
        echo "で検索しました、";
        echo "<h1 style='display:inline'>" . $count . "</h1>"." 件ヒットしました。";

    }
?>
<br>
<b>
<u>
<?php
echo "注意 : ICD10コードの先頭に ● がついているものがレセプト電算処理システムマスターです。"
?>
</b>
</u>

<hr size = "2" color = "green">

<!DOCTYPE HTML>
<html>
<body>
<br>
<!--<h1><a href= "./index.php" >再検索</a></h1>-->
<a href="./index.php" class="button01">再検索</a>
<br>
<br>


<style>
table td {
	background: #ccffcc;
}
table tr:nth-child(odd) td {
	background: #fff;
}
</style>
<table border="1" style="border-collapse: collapse">
<tr>

<?php
if($NOT_FOUND_flag == 1 && $NOT_FOUND_flag != 2){
    echo "<span class='no-wrap'>もしかして ...    " . "<h1 style='display:inline'><span class='under'>" . $similar_word . "</h1></span>" ." で検索した場合、</span>" . "<span class='no-wrap'><h1 style='display:inline'>" .  $count_similar . "</h1>" . " 件ヒットします。</span>";
    echo "<br><br>";
}else if($NOT_FOUND_flag == 2 ){
    echo "<b><u>";
    echo "データと一致するデータがなかったため、検索した文字列に類似している疾患名を表示します。";
    echo "</b></u>";
    echo "<br><br>";
}
?>

<th nowrap>ICD10</th>
<th>名称</th>
<th>大分類</th>
<th>中分類</th>
</tr>
<?php foreach($stmt as $value): ?>
<tr>
<td><?= "$value[icd10_code]"; ?></td>
<td><?= "$value[name]"; ?></td>
<td><?= "$value[大分類]"; ?></td>
<td><?= "$value[中分類]"; ?></td>
</tr>
<?php endforeach; ?>
</table>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
//ログ監視用
{

	$filename = "./log/log.txt"; //ログファイル名
	$time = date("Y/m/d H:i:s"); //アクセス時刻
	$ip = getenv("REMOTE_ADDR"); //IPアドレス
    // $search_word = mb_convert_encoding($_GET["search_name"], "SJIS", "UTF-8");
    $search_word = mb_convert_encoding($_GET["search_name"],  "UTF-8");

    $search_type = $_GET['search_type'];
	$host = getenv("REMOTE_HOST"); //ホスト名
	$referer = getenv("HTTP_REFERER"); //リファラ（遷移元ページ）
	$uri = getenv("REQUEST_URI"); //URI取得
	$requestbrowser=$_SERVER['HTTP_USER_AGENT'];//ブラウザ情報の取得
	$requestMethod=$_SERVER['REQUEST_METHOD'];//リクエストメソッドの取得
	

    $str = mb_convert_encoding($_GET["search_name"], "SJIS", "UTF-8");
	//ログ本文
    if($NOT_FOUND_flag == 2 ){
        $similar_word = "-";
        $WordColor = '<div id="lv3">';
    }else if($NOT_FOUND_flag == 1 ){
        $WordColor = '<div id="lv2">';
    }else{
        $WordColor = '<div id="lv1">';
    }
	$log = 


            "\nMETHOD:". $requestMethod ."</div>".
            "\nBROWSER:". $requestbrowser. 
            "\nREFERER:". $referer.
            "\nURI:". $uri.
            "\nHITS:". $count. " ( " . $count_similar . " ) ".
            "\nTYPE:". $search_type .
            "\nWORD:". $search_word . " ( " . $similar_word . " ) ".
			"\nIP:". $ip .
            "\n".$WordColor."DATE:".$time .
            "\n---------------------------------";

            
	
	//ログ書き込み
	$fp = fopen($filename, "a");
	fputs($fp, $log);
	fclose($fp);
	
	//echo $log;
}
?>
<?php
//アクセスログ開始
$counter_file = './log/counter.txt';
$counter_lenght = 8;
$fp = fopen($counter_file, 'r+');

if ($fp){
    if (flock($fp, LOCK_EX)){
        $counter = fgets($fp, $counter_lenght);
        $counter++;
        rewind($fp);
        if (fwrite($fp,  $counter) === FALSE){
            print('ファイル書き込みに失敗しました');
        }
        flock($fp, LOCK_UN);
    }
}
fclose($fp);
?>

</body>
</html>
