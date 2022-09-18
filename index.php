<title>ICD10コードけんさく君</title>
<link rel="stylesheet" href="nomal.css?v=2">
<meta name="description" content="疾患の名称からICD10の検索をすることができます。また、ICD10コードから登録されている疾患の名称を検索することも可能です。
検索結果画面には、ヒットした病名・ICD10コードに加え、大分類と中分類が表示されます。レセプト電算処理システムマスターを含んで検索、含まないで検索することや疾患の名称をヒット率の高いに並び替えることも可能です。"/>
<meta name="keywords" content="icd10,ICD10,検索,疾患コード,国際疾患分類,疾患分類" />
<meta name="viewport" content="width=device-width, user-scale=yes, initial-scale=1.0, maximum-scale=5.0" />
<meta name="theme-color" content=#7ed338 />

<script data-ad-client="ca-pub-1600481504925778" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<?php 
header('Content-Type: text/html; charset=UTF-8');
?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-HPJQJ8ER5S"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-HPJQJ8ER5S');
</script>
<section class="spikes">
    <svg  data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" ><path  d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill" fill="#7ED321" fill-opacity="1"></path><path  d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill" fill="#7ED321" fill-opacity="1"></path><path  d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill" fill="#7ED321" fill-opacity="1"></path></svg>
<br>
<div style="text-align: center">
<span class="logo">ICD10コードけんさく君 Ver.2.5</span>
<br><br>
<h3>疾患の名称またはICD10コードの一部から</h3>
<h3>データ検索をすることができます。</h3>
</div>
<form action="search.php" method="get">
    <div style="text-align: center">
    <!-- 任意の<input>要素＝入力欄などを用意する -->
    <input type="text" autofocus name="search_name" style="width:50%;height:50px;font-size:200%;">
    <!-- 送信ボタンを用意する -->
    <input type="submit" value="検索する" class="button03" style="cursor:pointer">
    <br><br>
    <label><span class="no-wrap"><input type="radio" name="search_type" style="transform:scale(1.5);" value="1" checked="checked"> 通常検索</span></label>
    <label><span class="no-wrap"><input type="radio" name="search_type" style="transform:scale(1.5);" value="2">ヒット率の高い順</span></label>
    <label><span class="no-wrap"><input type="radio" name="search_type" style="transform:scale(1.5);" value="3">レセプトのみ</span></label>
    <label><span class="no-wrap"><input type="radio" name="search_type" style="transform:scale(1.5);" value="4">レセプトを検索しない</span></label>
    </div>
</form>
</section>
<br><br>
<h2><span class="under">検索履歴</span></h2>
<?php
  setcookie("refreshed[1]", '', time() - 1);
?>
<?php

$val = $_COOKIE['history'];
for($i = 6; $i > 0; $i--){
  if(isset($val[$i])){
    echo '<div class="button04">';
    echo '<a href="'. "./search.php?search_name=" .$val[$i].'">'. '「' .$val[$i] . '」' ."でもう一度検索する" .'</a><br>';
    echo '</div>';
  }
}
if(isset($val[1])){
  echo '<div style="text-align: center">';
  echo '<a href="./reset.php" class="btn-border">履歴情報のリセット</a>';
  echo '</div>';
}else{
  echo '<div style="text-align: center">'.'検索履歴はありません。'.'</div>';
}
?>

<h2><span class="under">アンケート</span></h2>
<a href="./surveyform.php" >アンケートに回答</a>
</div>
<br><br><br>
<script src='https://storage.ko-fi.com/cdn/scripts/overlay-widget.js'></script>
<script>
  kofiWidgetOverlay.draw('ryusei_coffee', {
    'type': 'floating-chat',
    'floating-chat.donateButton.text': 'Support Me',
    'floating-chat.donateButton.background-color': '#f45d22',
    'floating-chat.donateButton.text-color': '#fff'
  });
</script>
<h2><span class="under">更新情報</span></h2>
Ver.2.5  検索履歴を表示できるようにしました。(2022年1月6日)<br>
Ver.2.4  検索結果が0件の時には、より詳細なあいまい検索を行えるようにしました。(2021年7月16日)<br>
Ver.2.3  検索結果のページを一部見やすくしました。疾患の名称を全角半角を無視して検索できるようにしました。(2021年5月1日)<br>
Ver.2.2  サイトのデザインを変更しました。(2021年3月21日)<br>
Ver.2.1  検索の優先順位をを選べるようにしました。(2021年2月22日)<br>
Ver.2　  レセプト電算処理システムマスターより引用したデータを登録しました。(2021年2月19日)<br>
Ver.1　  ICD10コードより疾患の名称を検索できるようになりました。ICD10コードの一部からも検索ができます。(2021年2月10日)<br>
<br><br><br>
<h2><span class="under">その他</span></h2>
<?php
$counter_file = './log/counter.txt';
$counter_lenght = 8;
$fp = fopen($counter_file, 'r+');

if ($fp){
  if (flock($fp, LOCK_EX)){
    $counter = fgets($fp, $counter_lenght);
    flock($fp, LOCK_UN);
  }
}
fclose($fp);
print('累計検索回数 : '.$counter." 回<br>");
print('(2022年1月12日より)');


?>


<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#7ED321" fill-opacity="1" d=
"M0,160L48,186.7C96,213,192,267,288,256C384,245,480,171,576,165.3C672,160,768,224,864,208C960,192,1056,96,
1152,58.7C1248,21,1344,43,1392,53.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,
864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>


</svg>
</body>
</html>