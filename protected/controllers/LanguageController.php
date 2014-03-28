<?php

class LanguageController extends Controller
{
    /**
     * Смена языка и дальнейший редирект
     * на предыдущую страницу
     */
    public function actionChange($language)
    {
        $cookie = new CHttpCookie('dil', $language);
        $cookie->expire = time()+60*60*24*180;

        Yii::app()->request->cookies['dil'] = $cookie;

        $this->redirect(Yii::app()->request->urlReferrer);
    }
}