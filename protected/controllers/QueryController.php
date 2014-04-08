<?php

class QueryController extends Controller
{
    /**
     * Страница поиска по текстовому запросу
     *
     * @param $text
     */
    public function actionView($text)
    {
        $text = trim($text);
        $text = preg_replace('/\s+/su', ' ', $text);
        $text = str_replace(chr(0), '', $text);

        /**
         * Ищем запрос либо создаем новый
         */
        $query = Query::model()->findByAttributes(array(
            'text' => $text,
        ));
        if( ! $query)
        {
            $query = new Query;
            $query->text = $text;
        }

        /**
         * Сначала пытаемся найти результаты среди файлов
         * старой версии сайта
         */
        if(count($query->results) === 0)
        {
            $query->results = new Mp3lerAudio($query->text);
        }

        /**
         * Если результатов на старом сайте нет то ищем вконтакте
         */
        if(count($query->results) === 0)
        {
            $query->results = new VkAudio($query->text);
        }

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
        ));
    }
}