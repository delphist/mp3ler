<?php

class SitemapCommand extends CConsoleCommand
{
    /**
     * Генерирует файлы сайтмапов
     */
    public function actionGenerate()
    {
        date_default_timezone_set('Europe/Moscow');
        #$_SERVER['HTTP_HOST'] = Yii::app()->params->domain; // TODO: get from params

        Yii::app()->urlManager->setBaseUrl('http://'.Yii::app()->params->domain);

        $sitemap = new Sitemap;
        $sitemap->limit = 50000;
        $sitemap->beginGenerate();

        foreach($sitemap->languages as $language)
        {
            $sitemap->addUrl($language, Yii::app()->createAbsoluteUrl('site/index', array('lang' => $language)));
            $sitemap->addUrl($language, Yii::app()->createAbsoluteUrl('query/top', array('lang' => $language)));
            $sitemap->addUrl($language, Yii::app()->createAbsoluteUrl('track/top', array('lang' => $language)));
            $sitemap->addUrl($language, Yii::app()->createAbsoluteUrl('site/partnerInfo', array('lang' => $language)));
        }

        $last_id = 0;
        while(TRUE)
        {
            $queries = Query::model()->findAll(array(
                'condition' => 'id > '.$last_id,
                'limit' => 1000,
            ));

            foreach($queries as $query)
            {
                foreach($sitemap->languages as $language)
                {
                    $sitemap->addUrl($language, Yii::app()->createAbsoluteUrl('query/view', array('text' => $query->text, 'lang' => $language)));
                }

                $last_id = $query->id;
            }

            if(count($queries) == 0)
            {
                break;
            }
        }

        $sitemap->endGenerate();
    }
}