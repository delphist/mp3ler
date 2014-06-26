<?php
$banners = array(
    /*array(
        'link' => 'https://play.google.com/store/apps/details?id=com.nutty.nuts&referrer=utm_source%3Dmp3ler',
        'market' => 'market://details?id=com.nutty.nuts&referrer=utm_source%3Dmp3ler',
        'image' => '/images/320x50-game.jpg',
    ),*/
    array(
        'link' => 'https://play.google.com/store/apps/details?id=chess.free&referrer=utm_source%3Dmp3ler',
        'market' => 'market://details?id=chess.free&referrer=utm_source%3Dmp3ler',
        'image' => '/images/turk-chess320x50.jpg'
    ),
    array(
        'link' => 'http://val.mobi',
        'market' => 'market://details?id=val.fm',
        'image' => '/images/320x50.jpg',
    ),
);

$random = array_rand($banners);
$banner = $banners[$random];

$ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
$link = $banner['link'];
$target=" target=\"_blank\"";
if(stripos($ua,'android') !== false) { // && stripos($ua,'mobile') !== false) {
    $link = $banner['market'];
    $target="";
}
?>

<center>
    <a href="<?=$link?>"<?=$target?>><img src="<?=$banner['image']?>" alt=""></a>
    <br/>
</center>