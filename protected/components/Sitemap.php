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
     * @var array Массив указателей на файлы сайтмапов для разных языков
     */
    protected $filePointers = array();

    /**
     * @var array Массив значений количества записанных ссылок в файлы
     */
    protected $fileUrlCounters = array();

    /**
     * Инициализация генерации сайтмапов, вызывать до начала генерации
     */
    public function beginGenerate()
    {
        $sitemaps_storage = $this->generateFilePath('');
        if( ! is_dir($sitemaps_storage))
        {
            mkdir($sitemaps_storage, 0755);
        }

        foreach($this->languages as $language)
        {
            $this->filePointers[$language] = NULL;
            $this->fileUrlCounters[$language] = 0;
        }
    }

    /**
     * Запись определенного урла
     *
     * @param $language название языка
     * @param $url url-адрес
     */
    public function addUrl($language, $url)
    {
        $this->fileUrlCounters[$language]++;

        if( ! isset($this->filePointers[$language]) || $this->filePointers[$language] === NULL)
        {
            $this->open($language);
        }

        fwrite($this->filePointers[$language], "\t<url>\n\t\t<loc>".$this->url($url)."</loc>\n\t</url>\n");

        if($this->fileUrlCounters[$language] % $this->limit == 0)
        {
            $this->close($language);
        }
    }

    /**
     * Финализация генерации сайтмапов, вызывать после добавления всех урлов
     */
    public function endGenerate()
    {
        foreach($this->languages as $language)
        {
            if($this->filePointers[$language] != NULL)
            {
                $this->close($language);
            }

            $file = fopen($this->generateFilePath($this->generateIndexFilename($language)), 'w+');
            fwrite($file, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n\t<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n");
            for($i = 1; $i <= ceil($this->fileUrlCounters[$language] / $this->limit); $i++)
            {
                fwrite($file, "\t\t<sitemap>\n\t\t\t<loc>".$this->url(Yii::app()->createAbsoluteUrl('/sitemap/view', array('number' => $i, 'lang' => $language)))."</loc>\n\t\t\t<lastmod>".date('r')."</lastmod>\n\t\t</sitemap>\n");
            }
            fwrite($file, "\t</sitemapindex>\n");
            fclose($file);
        }
    }

    /**
     * Генерирует имя файла очередного сайтмапа дял определенного языка
     *
     * @param $language название языка (ru, en, ...)
     * @param $number порядковый номер файла
     * @return string имя файла
     */
    public function generateFilename($language, $number)
    {
        return 'sitemap-'.$language.'-'.$number.'.xml';
    }

    /**
     * Генерирует имя индексного файла сайтмапа для определенного языка
     *
     * @param $language название языка (ru, en, ...)
     * @return string имя файла
     */
    public function generateIndexFilename($language)
    {
        return 'sitemap-index-'.$language.'.xml';
    }

    /**
     * Генерирует полный путь до файла в хранилище сайтмапов
     *
     * @param $filename имя файла
     * @return string полный путь
     */
    public function generateFilePath($filename)
    {
        return Yii::app()->params['storage_path'].'/sitemaps/'.$filename;
    }

    protected function open($language)
    {
        $filename = $this->generateFilePath($this->currentFilename($language));
        $this->filePointers[$language] = fopen($filename, 'w+');
        fwrite($this->filePointers[$language], "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n");
        chmod($filename, 0755);
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

    protected function getLanguages()
    {
        return Yii::app()->params['languages'];
    }

    protected function url($value)
    {
        return str_replace('&', '&amp;', $value);
    }
}