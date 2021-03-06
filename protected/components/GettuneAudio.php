<?php

/**
 * Класс поиска музыки через get-tune
 */
class GettuneAudio extends Audio
{
    /**
     * @var Results Обьект результатов поиска
     */
    protected $results;

    /**
     * @var GettuneCache Обьект модели кеша результатов поиска
     */
    protected $cache;

    /**
     * @var bool Пометка о том, что кеш изменился и его нужно сохранить
     */
    protected $cache_changed = FALSE;

    /**
     * @var GettuneParser обьект парсера
     */
    protected $parser;

    /**
     * @var bool Пометка о том, что нужно обновить кеш
     */
    public $flush_cache = FALSE;

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
        $this->parser = new GettuneParser;
        $this->cache = GettuneCache::model()->findByQuery($this->query);

        if($this->cache === NULL)
        {
            /**
             * Если запись не найдена в кеше то создаем новый обьект кеша
             */
            $this->cache = new GettuneCache;
            $this->results = new Results;
        }
        else
        {
            /**
             * Иначе используем результаты из кеша
             */
            if($this->cache->response === NULL || ! ($this->cache->response instanceof Results))
            {
                $this->cache->page = NULL;
                $this->cache->response = new Results;
            }

            $this->results = $this->cache->response;
        }

        if($this->flush_cache)
        {
            /**
             * Обнуляем результаты если была пометка о сбросе кеша
             */

            $this->results = new Results;
        }

        if( ! count($this->results))
        {
            $this->cache->page = NULL;
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
                if( ! $this->cache->isNewRecord || GettuneCache::model()->findByQuery($this->query) === NULL)
                {
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
     * Загружает обьекты из парсера
     */
    protected function add_results()
    {
        if($this->cache->page > 10)
        {
            /**
             * Не просим больше 10й страницы
             */

            return FALSE;
        }

        if($this->cache->page === NULL)
        {
            $this->cache->page = 1;
        }
        else
        {
            $this->cache->page++;
        }

        /**
         * Ищем музыку через парсер
         */
        $response = $this->parser->execute(array(
            'query' => $this->query,
            'page' => $this->cache->page,
        ));

        if($response != NULL && isset($response['count']) && $response['count'] > 0 && isset($response['results']))
        {
            $this->results->count = (int) $response['count'];

            foreach($response['results'] as $audio)
            {
                $this->results[] = array(
                    'id' => (int) $audio['id'],
                    'type' => 'gt',
                    'artist_title' => $this->decode_string((string) $audio['artist_title']),
                    'title' => $this->decode_string((string) $audio['title']),
                    'duration' => (int) $audio['duration'],
                    'server_id' => Yii::app()->serverManager->currentServerId,
                    'url' => (string) $audio['url'],
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