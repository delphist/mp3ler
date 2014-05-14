<?php
return array(
    'class' => 'application.components.RedisSessionManager',
    'autoStart' => FALSE,
    'cookieMode' => 'allow',
    'useTransparentSessionID' => FALSE,
    'sessionName' => 'sid',
    'saveHandler' => 'redis',
    'savePath' => 'tcp://localhost:6379?database=2&prefix=s:',
    'timeout' => 28800, //8h
);