<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404Error!</title>
    <div style="text-align:center;">
    <h1>404エラーです!</h1>
    入力したURLが見つかりませんでした。<br><br>
    このページが何度も表示されるようでしたら
    <?php
    header('Content-Type: text/html; charset=UTF-8');
    date_default_timezone_set('Asia/Tokyo');
    $today = date("Y-m-d H:i:s");
    echo '<a href="mailto:support@icd10kensakukun.com?subject=404エラーの報告&body=##############本文をこのまま変更せず送信してください##############%0D%0A404エラーが発生しました。%0D%0A'.$today.'">こちらをクリックして</a><br>';
?>
    support@icd10kensakukun.comまでご報告ください。

    </div>
</head>
<body>
    
</body>
</html>