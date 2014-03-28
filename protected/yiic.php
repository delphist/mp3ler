<?php

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../../framework/yiic.php';

if($_SERVER['HOSTNAME'] == 'mp3ler.biz')
{
    $config=dirname(__FILE__).'/config/console_production.php';
}
else
{
    $config=dirname(__FILE__).'/config/console.php';
}

require_once($yiic);
