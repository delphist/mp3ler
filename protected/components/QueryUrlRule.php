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
            $query = $params['text'];

            unset($params['text'], $params['query']);

            return '?'.http_build_query(array_merge(
                array(Yii::app()->params['query_param'] => $query),
                $params
            ));
        }

        return false;
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        if ($request->getQuery(Yii::app()->params['query_param']) != NULL)
        {
            $_GET['text'] = $request->getQuery(Yii::app()->params['query_param']);

            return 'query/view';
        }

        return false;
    }
}