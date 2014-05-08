<?php
return array(
    'urlFormat' => 'path',
    'showScriptName' => FALSE,
    'rules' => array(
        /**
         * Часть адресов пришлось роутить через кастомные правила,
         * потому что нужно было сохранять структуру адресов предыдущей
         * версии сайта
         */
        array(
            /** Поисковые запросы */
            'class' => 'application.components.QueryUrlRule',
        ),

        /**
         * Скачивание файла
         */
        'download/<filename:.*?\.mp3>' => 'track/download',

        /**
         * Топ треков
         */
        'top.php' => 'track/top',

        /**
         * Топ поисковых запрсоов
         */
        'search.php' => 'query/top',

        /**
         * Старая партнерка
         */
        'x.php' => 'site/partner',
        'wmall.php' => 'site/partnerInfo',

        /**
         * Редирект со старых адресов
         */
        '<vkid:[\d\-]+_[\d\-]+>' => 'track/vkId',
        'mp3/<vkid:[\d\-]+_[\d\-]+>.php' => 'track/vkId',

        /**
         * Партнерская панель
         */
        'partner/transitions/<periodName:\w+>' => 'partner/transitions',
        'partner/<action:\w+>' => 'partner/<action>',

        /**
         * Панель юзер
         */
        'user/<action:\w+>' => 'user/<action>',
    ),
);