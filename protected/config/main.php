<?php

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Mp3ler.biz',

	'preload'=>array('log'),

	'import'=>array(
		'application.models.*',
        'application.models.vk.*',
		'application.components.*',
        'application.vendors.*'
	),

	'modules'=>array(
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),
	'components'=>array(
        'request' => array(
            //'baseUrl' => 'http://'.$_SERVER['HTTP_HOST'],
        ),
		'user'=>array(
			'allowAutoLogin'=>true,
		),
        'captchaSolver'=>array(
            'class' => 'application.components.Antigate',
        ),
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName' => false,
			'rules'=>array(
                /**
                 * Часть адресов пришлось роутить через кастомные правила,
                 * потому что нужно было сохранять структуру адресов предыдущей
                 * версии сайта
                 */
                array(
                    /** Смена языка сайта */
                    'class' => 'application.components.LanguageUrlRule',
                ),
                array(
                    /** Поисковые запросы */
                    'class' => 'application.components.QueryUrlRule',
                ),
				'download/<filename:.*?\.mp3>' => 'track/download',
			),
		),
        'cache'=>array(
            'class'=>'system.caching.CMemCache',
            'servers'=>array(
                array('host'=>'localhost', 'port'=>11211, 'weight'=>60),
            ),
        ),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=mp3ler',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
                array(
                    'class'=>'CWebLogRoute',
                    'enabled' => isset($_GET['debug_mode_28f'])
                ),
            ),
        ),
	),
);