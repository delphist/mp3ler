<?php
$links = array(
    '/?query=kid+rock+celebrate+wrestlemania+30&lang=az',
    '/?query=WWE+WrestleMania+XXX+30+2014+2+Legacy+by+Eminem',
    '/?query=WWE+WrestleMania+XXX+30+2014+1+Celebrate+by+Kid+Rock',
    '/?query=Eminem+WWE+Wrestlemania+30+XXX+John+Cena+vs+Bray+Wyatt+Theme+Song+Legacy&lang=en',
    '/?query=Kid+Rock+Celebrate+Wrestlemania+30'
);

if(in_array($_SERVER['REQUEST_URI'], $links))
{
    exit(header('HTTP/1.0 404 Not Found'));
}

if (php_sapi_name() == 'cli-server')
{
    if (is_file($_SERVER['DOCUMENT_ROOT'].parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)))
    {
        return false;
    }
}

date_default_timezone_set('Europe/Moscow');

mb_internal_encoding('UTF-8');

defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

$webRoot=dirname(__FILE__);
define('ROOT_DIR', $webRoot);
$configFile = $webRoot.'/protected/config/main.php';

if(strpos($_SERVER['HTTP_HOST'], 'localhost') === 0)
{
    define('YII_DEBUG', TRUE);
}
else
{
    define('YII_DEBUG', isset($_COOKIE['debug_mode_28f']));
}

require_once($webRoot.'/../framework/yii.php');

Yii::createWebApplication($configFile)->run();
