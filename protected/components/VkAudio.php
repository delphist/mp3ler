<?php

/**
 * Класс поиска музыки через вконтакте
 */
class VkAudio extends Audio
{
    /**
     * Сначала проверяет, сохранены ли результаты поиска в кеше
     * (используется таблица mysql) и выдает результаты оттуда,
     * если кеш не найден то выполняет запрос к API вконтакте,
     * сохраняет их в кеш и отдает
     *
     * @return Results результаты поиска
     */
    public function results()
    {
        $api = new VkApi;
        $cache = NULL;

        if(YII_DEBUG)
        {
            $cache = VkCache::model()->findByQuery($this->query);
        }

        if($cache === NULL)
        {
            /**
             * Ищем музыку через API Vkontakte
             */
            $response = $api->execute('audio.search', array(
                'q' => $this->query,
                'auto_complete' => 1,
                'sort' => 2,
                'count' => 10
            ));

            $cache = new VkCache;
            $cache->query = $this->query;
            $cache->response = $response;

            try
            {
                if(YII_DEBUG)
                {
                    $cache->save();
                }
            }
            catch(Exception $e) {}
        }
        else
        {
            $response = $cache->response;
        }

        $results = new Results;

        /**
         * В случае какой-то ошибки результат поиска остается пустым
         */
        if($response != NULL && isset($response->count) && $response->count > 0 && isset($response->audio))
        {
            $results->count = (int) $response->count;

            foreach($response->audio as $audio)
            {
                $results[] = array(
                    'id' => (int) $audio->aid,
                    'type' => 'vk',
                    'artist_title' => $this->decode_string((string) $audio->artist),
                    'title' => $this->decode_string((string) $audio->title),
                    'duration' => (int) $audio->duration,
                    'url' => (string) $audio->url,
                );
            }
        }

        return $results;
    }
}