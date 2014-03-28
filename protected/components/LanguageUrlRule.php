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
     * В дальнейшем, все такие адреса роутятся обратно (см. след метод)
     */
    public function createUrl($manager, $route, $params, $ampersand)
    {
        if ($route === 'language/change')
        {
            return '?lang='.$params['language'];
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