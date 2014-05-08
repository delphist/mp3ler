<?php

class QueryController extends Controller
{
    public function filters()
    {
        return array(
            'languageControl', 'languageRedirect', 'transitionControl'
        );
    }

    /**
     * Страница поиска по текстовому запросу
     *
     * @param $text текст запроса
     * @param $page номер страницы
     */
    public function actionView($text, $page = 1)
    {
        $pages = new CPagination;
        $pages->pageSize = 20;

        /**
         * Приводим запрос к нормализованному виду
         */
        if($text != $this->normalizeQuery($text))
        {
            $this->redirect($this->createUrl('query/view', array(
                'text' => $this->normalizeQuery($text)
            )), TRUE, 301);
        }

        /**
         * Ищем запрос либо создаем новый
         */
        $query = Query::model()->findByText($text);
        if( ! $query)
        {
            $query = new Query;
            $query->text = $text;
        }

        /**
         * Ищем вконтакте
         */
        $vk_audio = new VkAudio($query->text, $page);
        $vk_audio->flush_cache = TRUE;
        $query->results = $vk_audio;

        /**
         * Сверяем найденные результаты с текстовым запросом,
         * при точном совпадении создаем трек
         */
        $track = NULL;
        foreach($query->results as $k => $result)
        {
            if(mb_strtolower($this->normalizeQuery($query->text)) == mb_strtolower($this->normalizeQuery($result['artist_title'].' - '.$result['title'])))
            {
                $track = new Track;
                $track->artist_title = $result['artist_title'];
                $track->title = $result['title'];
                $track->data = $result;

                unset($query->results[$k]);
            }
        }

        $pages->itemCount = $query->results->real_count;

        /**
         * Сохраняем запрос, т.к. он может быть новый, либо
         * в нем изменилось количество найденных результатов
         */
        $check = preg_match('/^[\w\d\s\.\(\)\-\!\;\%\&\*\+\_\/\[\]\<\>]+$/isu', $query->text);
        if($check && $track === NULL)
        {
            /**
             * Сохраняем запрос в том случае если он состоит из слов, цифр
             * и некоторых знаков
             */
            $query->save();

            $queue = new QueryQueue;
            $queue->query_id = $query->id;
            $queue->save();
        }

        $this->render('view', array(
            'track' => $track,
            'query' => $query,
            'pages' => $pages,
        ));
    }

    /**
     * Страница последних поисковых запросов
     */
    public function actionTop()
    {
        $criteria = new CDbCriteria(array(
            'order' => 'id DESC',
        ));

        $pages = new CPagination;
        $pages->pageSize = 20;
        $pages->itemCount = 1000;
        $pages->applyLimit($criteria);

        $queries = Query::model()->cache(60)->findAll($criteria);

        $this->render('top', array(
            'queries' => $queries,
            'pages' => $pages,
        ));
    }
}