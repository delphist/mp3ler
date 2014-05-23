<?php
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
$link = 'http://val.fm';
$target=" target=\"_blank\"";
if(stripos($ua,'android') !== false) { // && stripos($ua,'mobile') !== false) {
    $link = 'market://details?id=val.fm';
    $target="";
}
?>

<center>
    <a href="<?=$link?>"<?=$target?>><img src="/images/320x50.jpg" alt=""></a>
    <br/>
</center>