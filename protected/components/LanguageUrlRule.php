<?php
/**
 * Реализация правил адресов смены языка
 *
 * Почему так? Так стояла задача (нужно было сделать
 * такой же механизм, как на прошлом сайте)
 */
class LanguageUrlRule extends CBaseUrlRule
{
    public $connectionID = 'db';

    /**
     * Для роута language/change создает относительный адрес,
     * содержащий в себе GET-параметр lang
     *
     * Пример:
     *  - Находимся на http://mp3ler.biz/ , создаст адрес http://mp3ler.biz/?lang=en
     *  - Находимся на http://mp3ler.biz/download/test?prev_query=eminem , создаст адрес http://mp3ler.biz/download/test?prev_query=eminem&lang=en
     *
     * В дальнейшем, все такие адреса роутятся обратно (см. след метод)
     */
    public function createUrl($manager, $route, $params, $ampersand)
    {
        if ($route === 'language/change')
        {
            $uri = parse_url(Yii::app()->request->getRequestUri());
            parse_str($uri['query'], $query);

            return ltrim($uri['path'].'?'.http_build_query(array_merge($query, array('lang' => $params['language']))), '/');
        }

        return false;
    }

    /**
     * Реализует правило распознавания URL-адресов
     * в которых содержится GET-параметр query,
     * перенаправляя все такие запросы в контроллер language/change
     *
     * Пример URL-адресов, которые будут перенаправлены в контроллер:
     *  - http://mp3lez.biz/?lang=ru
     *  - http://mp3lez.biz/index.php?lang=ru
     *  - http://mp3lez.biz/directory/test.html?lang=ru&test=false
     */
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        if ($request->getQuery('lang') != NULL)
        {
            $_GET['language'] = $request->getQuery('lang');

            return 'language/change';
        }

        return false;
    }
}