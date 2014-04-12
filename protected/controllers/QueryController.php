<?php

class QueryController extends Controller
{
    public function filters()
    {
        return array(
            'languageControl',
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

        $text = trim($text);
        $text = preg_replace('/\s+/su', ' ', $text);
        $text = str_replace(chr(0), '', $text);

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
        $query->results = new VkAudio($query->text, $page);

        /**
         * Сверяем найденные результаты с текстовым запросом,
         * при точном совпадении создаем трек
         */
        $track = NULL;
        foreach($query->results as $result)
        {
            if(mb_strtolower($query->text) == mb_strtolower($result['artist_title'].' - '.$result['title']))
            {
                $track = new Track;
                $track->artist_title = $result['artist_title'];
                $track->title = $result['title'];
                $track->data = $result;
            }
        }

        $pages->itemCount = $query->results->real_count;

        /**
         * Сохраняем запрос, т.к. он может быть новый, либо
         * в нем изменилось количество найденных результатов
         */
        $check = preg_match('/^[\w\d\s\.\(\)\-\!\;\%\&\*\+\_\/\[\]\<\>]+$/isu', $query->text);
        if($check)
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