<?php

return CMap::mergeArray(
    require(dirname(__FILE__).'/environment.php'),
    array(
        'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
        'name' => 'Mp3ler.biz',
        'sourceLanguage' => 'en',
        'language' => 'en',
        'preload' => array('log'),
        'import'=>array(
            'application.models.*',
            'application.models.vk.*',
            'application.components.*',
            'application.vendors.*',
            'application.extensions.redis.*',
        ),
        'params' => CMap::mergeArray(
                require(dirname(__FILE__).'/params.php'),
                array(
                    'default_language' => 'tr',
                    'languages' => array('ru', 'en', 'az', 'tr', 'ge'),
                )
            ),
        'components'=>array(
            'user'=>array(
                'class' => 'WebUser',
                'allowAutoLogin' => TRUE,
                'loginUrl' => array('/user/login'),
                'returnUrl' => array('/user/index'),
                'autoUpdateFlash' => FALSE,
            ),
            'request' => array(
                //'baseUrl' => 'http://'.$_SERVER['HTTP_HOST'],
            ),
            'transitionStatistics' => array(
                'class' => 'application.components.TransitionStatistics',
            ),
            'session' => require(dirname(__FILE__).'/session.php'),
            'redis' => require(dirname(__FILE__).'/redis.php'),
            'captchaSolver' => require(dirname(__FILE__).'/captcha.php'),
            'urlManager' => require(dirname(__FILE__).'/urls.php'),
            'cache' => require(dirname(__FILE__).'/cache.php'),
            'db' => require(dirname(__FILE__).'/database.php'),
            'errorHandler' => array(
                'errorAction' => 'site/error',
            ),
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'error, warning',
                    ),
                    array(
                        'class' => 'CWebLogRoute',
                        'enabled' => YII_DEBUG,
                    ),
                ),
            ),
        ),
    )
);