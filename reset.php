<?php
for($i = -1; $i < 100; $i++){
    setcookie("history[$i]", '', time() - 1);
}
header('Location: ./index.php');
?>