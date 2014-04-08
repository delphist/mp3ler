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

	'components' => array(
        'captchaSolver' => array(
            'apikey' => '7c3f691ed6aa723bd018d2896c39608b',
        ),
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=mp3ler',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);