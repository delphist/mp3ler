<?php

/**
 * Класс поиска музыки через вконтакте
 */
class VkAudio extends Audio
{
    /**
     * @var Results Обьект результатов поиска
     */
    protected $results;

    /**
     * @var VkCache Обьект модели кеша результатов поиска
     */
    protected $cache;

    /**
     * @var VkApi Обьекта доступа к API Вконтакте
     */
    protected $api;

    /**
     * @var int Количество загружаемых обьектов из api
     */
    protected $api_count = 300;

    /**
     * @var bool Пометка о том, что кеш изменился и его нужно сохранить
     */
    protected $cache_changed = FALSE;

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
        $this->api = new VkApi;
        $this->cache = VkCache::model()->findByQuery($this->query);

        if($this->cache === NULL)
        {
            /**
             * Если запись не найдена в кеше то создаем новый обьект кеша
             */
            $this->cache = new VkCache;
            $this->results = new Results;
        }
        else
        {
            /**
             * Иначе используем результаты из кеша
             */
            if($this->cache->response === NULL || ! ($this->cache->response instanceof Results))
            {
                $this->cache->vk_offset = NULL;
                $this->cache->response = new Results;
            }

            $this->results = $this->cache->response;
        }

        if( ! count($this->results))
        {
            $this->cache->vk_offset = NULL;
        }

        while($this->page * $this->perpage > count($this->results))
        {
            /**
             * Если запрашиваемая страница выходит за пределы найденных результатов,
             * то просим из API еще одну страницу
             */

            if( ! $this->add_results())
            {
                break;
            }

            if($this->page == 1)
            {
                /**
                 * Если на первой странице из API результатов меньше чем нам надо
                 * на первую страницу ($perpage), то дальше нет смысла листать и нагружать API
                 */
                break;
            }
        }

        if($this->cache_changed)
        {
            /**
             * Сохраняем кеш если он был помечен как измененный
             */
            $this->cache->query = $this->query;
            $this->cache->response = $this->results;

            try
            {
                /**
                 * Кеш на эту страницу может быть уже создан за время работы с API VK
                 */
                if( ! $this->cache->isNewRecord || VkCache::model()->findByQuery($this->query) === NULL)
                {
                    if(YII_DEBUG)
                    {
                        var_dump($this->cache);
                    }

                    $this->cache->save();
                }
            }
            catch(Exception $e) { }
        }

        /**
         * Выдаем результаты в соответствии с номером текущей страницы,
         * создавая новый обрезанный обьект Results (старый будет храниться в кеше)
         */
        $k = 0;
        $results = new Results;
        $results->count = $this->results->count;
        $results->real_count = count($this->results);
        for($i = ($this->page - 1) * $this->perpage; $i < $this->page * $this->perpage; $i++)
        {
            if( ! isset($this->results[$i]))
            {
                break;
            }

            $results[$k++] = $this->results[$i];
        }

        return $results;
    }

    /**
     * Загружает обьекты из API вконтакте начиная со смещением $offset
     *
     * @param int $offset смещение относительно начала результатов поиска
     */
    protected function add_results()
    {
        if($this->cache->vk_offset + $this->api_count >= 1000)
        {
            /**
             * Ограничение API VK на количество выдаваемых результатов
             */

            return FALSE;
        }

        if($this->cache->vk_offset === NULL)
        {
            $this->cache->vk_offset = 0;
        }
        else
        {
            $this->cache->vk_offset += $this->api_count;
        }

        /**
         * Ищем музыку через API Vkontakte
         */
        $response = $this->api->execute('audio.search', array(
            'q' => $this->query,
            'auto_complete' => 1,
            'sort' => 2,
            'count' => $this->api_count,
            'offset' => $this->cache->vk_offset,
        ));

        if($response != NULL && isset($response->count) && $response->count > 0 && isset($response->audio))
        {
            $this->results->count = (int) $response->count;

            foreach($response->audio as $audio)
            {
                $this->results[] = array(
                    'id' => (int) $audio->aid,
                    'type' => 'vk',
                    'artist_title' => $this->decode_string((string) $audio->artist),
                    'title' => $this->decode_string((string) $audio->title),
                    'duration' => (int) $audio->duration,
                    'url' => (string) $audio->url,
                );
            }
        }

        /**
         * Помечаем что кеш нужно сохранить
         */
        $this->cache_changed = TRUE;

        return TRUE;
    }
}