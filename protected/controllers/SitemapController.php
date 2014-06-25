<?php

class SitemapController extends Controller
{
    public function filters()
    {
        return array(
            'domainControl',
        );
    }

    public function actionIndex($lang)
    {
        $sitemap = new Sitemap();
        $path = $sitemap->generateIndexFilename($lang);
        if( ! file_exists($sitemap->generateFilePath($path)))
        {
            throw new CHttpException(404);
        }

        $url_path = '/storage/sitemaps/'.$path;

        if(YII_DEBUG)
        {
            echo $url_path;
        }
        else
        {
            header('X-Accel-Redirect: '.$url_path);

            Yii::app()->end();
        }
    }

    public function actionView($lang, $number)
    {
        $sitemap = new Sitemap();
        $path = $sitemap->generateFilename($lang, $number);

        if( ! file_exists($sitemap->generateFilePath($path)))
        {
            throw new CHttpException(404);
        }

        $url_path = '/storage/sitemaps/'.$path;

        if(YII_DEBUG)
        {
            echo $url_path;
        }
        else
        {
            header('X-Accel-Redirect: '.$url_path);

            Yii::app()->end();
        }
    }
}