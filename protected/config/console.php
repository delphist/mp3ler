<?php
return array(
    'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name' => 'Mp3ler.biz console',
    'preload' => array('log'),
    'import'=>array(
        'application.models.*',
        'application.models.vk.*',
        'application.components.*',
        'application.vendors.*'
    ),
    'params' => CMap::mergeArray(
            require(dirname(__FILE__).'/params.php'),
            array(
                'default_language' => 'tr',
                'languages' => array('ru', 'en', 'az', 'tr', 'ge'),
            )
        ),
    'components' => array(
        'captchaSolver' => require(dirname(__FILE__).'/captcha.php'),
        'urlManager' => require(dirname(__FILE__).'/urls.php'),
        'cache' => require(dirname(__FILE__).'/cache.php'),
        'db' => require(dirname(__FILE__).'/database.php'),
        'errorHandler'=>array(
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
            ),
        ),
    ),
);