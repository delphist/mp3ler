<?php

class Controller extends CController
{
    public $layout = '//layouts/main';

    /**
     * @var string поисковой запрос в лейауте
     */
    public $searchQuery;

    /**
     * @var string заголовок в лейауте
     */
    public $headerTitle;

    /**
     * Создает ссылку для скачивания трека
     *
     * @param Track $track
     */
    protected function createTrackDownloadUrl(Track $track)
    {
        return $this->createUrl('track/download', array(
            'filename' => $track->filename,
            'data' => base64_encode(json_encode($track->data))
        ));
    }

    public function init()
    {
        parent::init();

        $possible_values = array(
            'en', 'ru', 'az', 'tr', 'ge'
        );

        if(Yii::app()->request->cookies->contains('dil') && in_array(Yii::app()->request->cookies['dil']->value, $possible_values))
        {
            Yii::app()->setLanguage(Yii::app()->request->cookies['dil']->value);
        }
    }
}