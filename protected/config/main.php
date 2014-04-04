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
            'baseUrl' => 'http://'.$_SERVER['HTTP_HOST'],
        ),
		'user'=>array(
			'allowAutoLogin'=>true,
		),
		'urlManager'=>array(
			'urlFormat'=>'query',
            'showScriptName'=>false,
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
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
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
	),
);