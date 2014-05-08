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
     * @var string Meta-заголовок description
     */
    public $metaDescription;

    /**
     * @var string Meta-заголовок keywords
     */
    public $metaKeywords;

    /**
     * @var string Meta-заголовок author
     */
    public $metaAuthor;

    /**
     * @var boolean Использовать ли h1 для заголовка
     */
    public $isH1 = TRUE;

    /**
     * @var User обьект партнера
     */
    public $partner;

    /**
     * @var array Список доступных языков
     */
    public $possible_languages = array('en', 'ru', 'az', 'tr', 'ge');

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

    /**
     * Создает ссылку для смены языка
     *
     * @param Track $track
     */
    protected function createLanguageUrl($language)
    {
        $uri = parse_url(Yii::app()->request->getRequestUri());

        if(isset($uri['query']))
        {
            parse_str($uri['query'], $query);
        }
        else
        {
            $query = array();
        }

        $language_uri = http_build_query(array_merge($query, array('lang' => $language)));

        if($language_uri)
        {
            $language_uri = $uri['path'].'?'.$language_uri;
        }
        else
        {
            $language_uri = $uri['path'];
        }

        return $language_uri;
    }

    /**
     * Проверяет и устанавливает язык приложения
     *
     * @param $language идентификатор языка
     */
    protected function setLanguage($language, $set_cookie = FALSE)
    {
        if(in_array($language, $this->possible_languages))
        {
            Yii::app()->setLanguage($language);

            if($set_cookie)
            {
                $cookie = new CHttpCookie('dil', $language);
                $cookie->expire = time()+60*60*24*180;

                Yii::app()->request->cookies['dil'] = $cookie;
            }

            return TRUE;
        }

        return FALSE;
    }

    public function createUrl($route, $params = array(), $ampersand = '&')
    {
        if(Yii::app()->language != Yii::app()->params['default_language'])
        {
            $params['lang'] = Yii::app()->language;
        }

        return parent::createUrl($route, $params, $ampersand);
    }

    /**
     * Нормализует текстовый запрос для поиска
     *
     * @param $text
     */
    public function normalizeQuery($text)
    {
        /**
         * Удаляем из запроса все кроме букв, цифр и пробелов
         */
        $text = str_replace(chr(0), '', $text);
        $text = preg_replace('/[^\w\d\s]/ius', ' ', $text);

        /**
         * Обрезаем двойные пробелмы и пробелы по краям
         */
        $text = preg_replace('/\s+/su', ' ', $text);
        $text = trim($text);

        return $text;
    }

    /**
     * Устанавливает системный язык
     *
     * @param $filterChain
     */
    public function filterLanguageControl($filterChain)
    {
        $this->setLanguage(Yii::app()->params['default_language']);

        if(isset($_GET['lang']))
        {
            $this->setLanguage($_GET['lang'], TRUE);
        }
        elseif(Yii::app()->request->cookies->contains('dil'))
        {
            $this->setLanguage(Yii::app()->request->cookies['dil']->value);
        }

        $filterChain->run();
    }

    /**
     * Выполняет редирект на страницу с правильным адресом дял текущего языка
     *
     * @param $filterChain
     */
    public function filterLanguageRedirect($filterChain)
    {
        if(isset($_GET['lang']) && $_GET['lang'] == Yii::app()->params['default_language'])
        {
            $this->redirect($this->createLanguageUrl(NULL));
        }
        elseif( ! isset($_GET['lang']) && Yii::app()->language != Yii::app()->params['default_language'])
        {
            $this->redirect($this->createLanguageUrl(Yii::app()->language));
        }

        $filterChain->run();
    }

    /**
     * Определяет и записывает переход с партнерки
     *
     * @param $filterChain
     */
    public function filterTransitionControl($filterChain)
    {
        if(isset($_GET['ref']))
        {
            $this->partner = User::model()->findByAttributes(array(
                'sitename' => $_GET['ref']
            ));

            if($this->partner !== NULL)
            {
                Yii::app()->transitionStatistics->commit($_SERVER['REMOTE_ADDR'], $this->partner->id);
            }
        }

        $filterChain->run();
    }

    /**
     * @var array данные о графике кликов
     */
    public $transitionChartData;

    /**
     * Обрабатывает информацию о данных графика кликов партнера
     *
     * @param $filterChain
     */
    public function filterTransitionChart($filterChain)
    {
        $this->transitionChartData = Yii::app()->transitionStatistics->parsePeriodName($_GET['periodName']);

        $filterChain->run();
    }
}