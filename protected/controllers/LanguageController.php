<?php

class LanguageController extends Controller
{
    /**
     * Смена языка и дальнейший редирект
     * на предыдущую страницу
     */
    public function actionChange($language)
    {
        if($language == 'geo')
        {
            /**
             * Алиас со старого сайта
             */
            $language = 'ge';
        }

        if(in_array($language, array('ru', 'en', 'az', 'ge', 'tr')))
        {
            $cookie = new CHttpCookie('dil', $language);
            $cookie->expire = time()+60*60*24*180;

            Yii::app()->request->cookies['dil'] = $cookie;
        }

        $uri = parse_url(Yii::app()->request->getRequestUri());

        if(isset($uri['query']))
        {
            parse_str($uri['query'], $query);
        }
        else
        {
            $query = array();
        }

        $redirect_uri = http_build_query(array_merge($query, array('lang' => NULL)));
        if($redirect_uri)
        {
            $redirect_uri = $uri['path'].'?'.$redirect_uri;
        }
        else
        {
            $redirect_uri = $uri['path'];
        }

        $this->redirect($redirect_uri);
    }
}