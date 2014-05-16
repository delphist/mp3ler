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
     * @var int Время жизни ссылки для скачивания
     */
    public $trackDownloadLinkTtl = 21600; // 6 часов

    /**
     * Создает ссылку для скачивания трека
     *
     * @param Track $track
     */
    protected function createTrackDownloadUrl(Track $track)
    {
        $downloadKey = $this->trackDownloadKey($track);

        Yii::app()->redis->getClient()->set('d:'.$downloadKey, $this->trackDownloadData($track), array('ex' => $this->trackDownloadLinkTtl));

        return $this->createUrl('track/download', array(
            'filename' => $track->filename,
            'id' => $downloadKey,
        ));
    }

    /**
     * Возвращает файл по идентификатору для скачивания
     *
     * @param string $key идентификатор
     * @return null|Track
     */
    public function findTrackByKey($key)
    {
        $data = Yii::app()->redis->getClient()->get('d:'.$key);

        if($data !== FALSE)
        {
            $data = json_decode($data, true);

            if(is_array($data) && isset($data['id']))
            {
                $track = Track::model()->findByData($data);

                if($track === NULL)
                {
                    $track = new Track;
                    $track->data = $data;
                }

                return $track;
            }
        }

        return NULL;
    }

    /**
     * Генерирует случайный ключ для хранения информации о скачивании файла
     *
     * @param Track $track
     * @return string уникальный хеш (40 символов)
     */
    protected function trackDownloadKey(Track $track)
    {
        $key = array(
            'a6dcc8b207f92718dc7251518d4db06a', // salt
            floor(time() / $this->trackDownloadLinkTtl),
        );

        if( ! $track->isNewRecord)
        {
            $key[] = 'saved';
            $key[] = $track->id;
        }
        else
        {
            $key[] = $track->data['type'];
            $key[] = $track->data['id'];
        }

        return sha1(implode($key, ':'));
    }

    /**
     * Генерирует строку с информацией о скачивании файле
     *
     * @param Track $track
     * @param string json-строка с информацией о скачивании
     */
    protected function trackDownloadData(Track $track)
    {
        if( ! $track->isNewRecord)
        {
            $data = array('id' => $track->id);
        }
        else
        {
            $data = $track->data;
        }

        return json_encode($data);
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

    /**
     * Создает URL, добавляя в него параметры текущего партнера
     * и выбранного языка
     */
    public function createUrl($route, $params = array(), $ampersand = '&')
    {
        if( ! isset($params['lang']))
        {
            if(Yii::app()->language != $this->defaultLanguage)
            {
                $params['lang'] = Yii::app()->language;
            }
        }
        else
        {
            unset($params['lang']);
        }

        if($this->partner)
        {
            $params['ref'] = $this->partner->sitename;
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
     * Редиректит на основной домен, если сайт запущен не из того места
     *
     * @param $filterChain
     */
    public function filterDomainControl($filterChain)
    {
        if(mb_strtolower($_SERVER['HTTP_HOST']) !== mb_strtolower(Yii::app()->serverManager->mainHost))
        {
            $this->redirect('http://'.Yii::app()->serverManager->mainHost.$_SERVER['REQUEST_URI']);
        }

        $filterChain->run();
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
            'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
            'referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
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