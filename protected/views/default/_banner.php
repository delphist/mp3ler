<?php
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
$link = 'https://play.google.com/store/apps/details?id=com.nutty.nuts';
$target=" target=\"_blank\"";
if(stripos($ua,'android') !== false) { // && stripos($ua,'mobile') !== false) {
    $link = 'market://details?id=com.nutty.nuts';
    $target="";
}
?>

<center>
    <a href="<?=$link?>"<?=$target?>><img src="/images/320x50-game.jpg" alt=""></a>
    <br/>
</center>