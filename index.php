<?php

if (php_sapi_name() == 'cli-server')
{
    if (is_file($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI']))
    {
        return false;
    }
}

date_default_timezone_set('Europe/Moscow');

mb_internal_encoding('UTF-8');

defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

$webRoot=dirname(__FILE__);

if(strpos($_SERVER['HTTP_HOST'], 'localhost') === 0)
{
    define('YII_DEBUG', true);
    require_once($webRoot.'/../framework/yii.php');
    $configFile = $webRoot.'/protected/config/development.php';
}
else
{
    define('YII_DEBUG', false);
    require_once($webRoot.'/../framework/yii.php');
    $configFile = $webRoot.'/protected/config/production.php';
}

Yii::createWebApplication($configFile)->run();
