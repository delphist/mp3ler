<?php

class SitemapCommand extends CConsoleCommand
{
    /**
     * Генерирует файлы сайтмапов
     */
    public function actionGenerate()
    {
        $sitemap = new Sitemap;
        $sitemap->beginGenerate();

        foreach($sitemap->languages as $language)
        {
            $sitemap->addUrl($language, Yii::app()->urlManager->createUrl('site/index', array('lang' => $language)));
            $sitemap->addUrl($language, Yii::app()->urlManager->createUrl('query/top', array('lang' => $language)));
            $sitemap->addUrl($language, Yii::app()->urlManager->createUrl('track/top', array('lang' => $language)));
            $sitemap->addUrl($language, Yii::app()->urlManager->createUrl('site/partnerInfo', array('lang' => $language)));
        }

        $sitemap->endGenerate();
    }
}