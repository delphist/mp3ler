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
        $path = $sitemap->generateFilePath($sitemap->generateIndexFilename($lang));
        if( ! file_exists($path))
        {
            throw new CHttpException(404);
        }

        if(YII_DEBUG)
        {
            echo $path;
        }
        else
        {
            header('X-Accel-Redirect: '.$path);

            Yii::app()->end();
        }
    }

    public function actionView($lang, $number)
    {
        $sitemap = new Sitemap();
        $path = $sitemap->generateFilePath($sitemap->generateFilename($lang, $number));

        if( ! file_exists($path))
        {
            throw new CHttpException(404);
        }

        if(YII_DEBUG)
        {
            echo $path;
        }
        else
        {
            header('X-Accel-Redirect: '.$path);

            Yii::app()->end();
        }
    }
}