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
    public $possibleLanguages = array('en', 'ru', 'az', 'tr', 'ge');

    /**
     * @var string Дефолтный язык
     */
    public $defaultLanguage = 'tr';

    /**
     * @var string Имя куки в которой хранится язык
     */
    public $languageCookieName = 'dil';

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
        if(in_array($language, $this->possibleLanguages))
        {
            Yii::app()->setLanguage($language);

            if($set_cookie)
            {
                $cookie = new CHttpCookie($this->languageCookieName, $language);
                $cookie->expire = time()+60*60*24*180;

                Yii::app()->request->cookies[$this->languageCookieName] = $cookie;
            }

            return TRUE;
        }

        return FALSE;
    }

    public function createUrl($route, $params = array(), $ampersand = '&')
    {
        if(Yii::app()->language != $this->defaultLanguage)
        {
            $params['lang'] = Yii::app()->language;
        }

        if($this->partner)
        {
            $params['ref'] = $this->partner;
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
        $this->setLanguage($this->defaultLanguage);

        if(isset($_GET['lang']))
        {
            $this->setLanguage($_GET['lang'], TRUE);
        }
        elseif(Yii::app()->request->cookies->contains($this->languageCookieName))
        {
            $this->setLanguage(Yii::app()->request->cookies[$this->languageCookieName]->value);
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
        if(isset($_GET['lang']) && $_GET['lang'] == $this->defaultLanguage)
        {
            $this->redirect($this->createLanguageUrl(NULL));
        }
        elseif( ! isset($_GET['lang']) && Yii::app()->language != $this->defaultLanguage)
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
        $data = array(
            'ip' => $_SERVER['REMOTE_ADDR'],
            'referer' => $_SERVER['HTTP_REFERER'],
        );

        $isCommit = TRUE;

        if(isset($_GET['ref']))
        {
            $this->partner = User::model()->findByAttributes(array(
                'sitename' => $_GET['ref']
            ));

            /**
             * Не записываем переход если он уже был совершен внутри сайта
             */
            if( ! Yii::app()->transitionStatistics->compareDomains(
                Yii::app()->params->domain,
                $data['referer']
            ))
            {
                $isCommit = FALSE;
            }
        }

        if($this->partner !== NULL && $isCommit)
        {
            Yii::app()->transitionStatistics->commit($this->partner, $data);
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
        if( ! isset($_GET['period']))
        {
            $_GET['period'] = 'today';
        }

        $this->transitionChartData = Yii::app()->transitionStatistics->parsePeriodName($_GET['period']);

        if($this->transitionChartData === NULL)
        {
            throw new CHttpException(400, 'Bad time period');
        }

        $filterChain->run();
    }
}