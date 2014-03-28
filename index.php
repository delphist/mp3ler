<?php

date_default_timezone_set('Europe/Moscow');

defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

$webRoot=dirname(__FILE__);

if($_SERVER['HTTP_HOST'] == 'localhost')
{
    define('YII_DEBUG', true);
    require_once(dirname($webRoot).'/../framework/yii.php');
    $configFile = $webRoot.'/../app/config/development.php';
}
else
{
    define('YII_DEBUG', false);
    require_once(dirname($webRoot).'/../framework/yiilite.php');
    $configFile = $webRoot.'/../app/config/production.php';
}

Yii::createWebApplication($configFile)->run();
