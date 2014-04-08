<?php
/**
 * Реализация правил адресов текстовых запросов
 */
class QueryUrlRule extends CBaseUrlRule
{
    public $connectionID = 'db';

    public function createUrl($manager, $route, $params, $ampersand)
    {
        if ($route === 'query/view')
        {
            return '?'.http_build_query(array('query' => $params['text']));
        }

        return false;
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        if ($request->getQuery('query') != NULL)
        {
            $_GET['text'] = $request->getQuery('query');

            return 'query/view';
        }

        return false;
    }
}