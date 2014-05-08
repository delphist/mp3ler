<?php

/**
 * Класс сайтмапов
 */
class Sitemap extends CComponent
{
    /**
     * @var int Максимальное количетсво ссылок в одном сайтмапе
     */
    public $limit = 10000;

    /**
     * @var array Массив указателей на файлы сайтмапов дял разных языков
     */
    protected $filePointers = array();

    /**
     * @var array Массив значений количества записанных ссылок в файлы
     */
    protected $fileUrlCounters = array();

    public function beginGenerate()
    {
        foreach($this->languages as $language)
        {
            $this->filePointers[$language] = NULL;
            $this->fileUrlCounters[$language] = 0;
        }
    }

    public function addUrl($language, $url)
    {
        $this->fileUrlCounters[$language]++;

        if( ! isset($this->filePointers[$language]) || $this->filePointers[$language] === NULL)
        {
            $this->open($language);
        }

        fwrite($this->filePointers[$language], "\t<url>\n\t\t<loc>$url</loc>\n\t</url>\n");

        if($this->fileUrlCounters[$language] % $this->limit == 0)
        {
            $this->close($language);
        }
    }

    public function endGenerate()
    {
        foreach($this->languages as $language)
        {
            if($this->filePointers[$language] != NULL)
            {
                $this->close($language);
            }
        }
    }

    protected function open($language)
    {
        $this->filePointers[$language] = fopen($this->generateFilePath($this->currentFilename($language)), 'w+');
        fwrite($this->filePointers[$language], "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n");
    }

    protected function close($language)
    {
        fwrite($this->filePointers[$language], "</sitemap>");
        fclose($this->filePointers[$language]);
        $this->filePointers[$language] = NULL;
    }

    protected function currentFilename($language)
    {
        return $this->generateFilename($language, ceil($this->fileUrlCounters[$language] / $this->limit));
    }

    protected function generateFilename($language, $number)
    {
        return 'sitemap-'.$language.'-'.$number.'.xml';
    }

    protected function generateFilePath($filename)
    {
        return Yii::app()->params['storage_path'].'/sitemaps/'.$filename;
    }

    protected function getLanguages()
    {
        return Yii::app()->params['languages'];
    }
}