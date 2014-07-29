<?php

/**
 * Класс для парсинга Get-tune.net
 */
class GettuneParser extends CComponent
{
    /**
     * URL API вконтакте
     */
    protected $search_url = 'http://get-tune.net/?a=music&q=#{query}&page=#{page}';

    /**
     * Выполняет запрос к Gettune и выдает ответ, упорядоченный массив
     * доступные параметры (первый аргумент):
     *  - query — текствоый запрос для поиска
     *  - page — номер страницы (по умолчанию: 1)
     *
     * @param bool $params параметры
     * @return array
     */
    public function execute($params)
    {
        Yii::beginProfile('gettuneParse');

        if( ! isset($params['page']))
        {
            $params['page'] = 1;
        }

        $url = strtr($this->search_url, array(
            '#{page}' => $params['page'],
            '#{query}' => rawurlencode($params['query'])
        ));

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
        ));

        $response = curl_exec($ch);

        $parsed_result = array();

        preg_match_all('/<li class="track".*?data\-id="(\d+)".*?data\-duration="(\d+)".*?>(.*?)<\/li>/is', $response, $results);

        foreach($results[3] as $i => $result)
        {
            preg_match('#<a class="playlist-btn-down no-ajaxy" href="(.*?)".*?>#is', $result, $download);
            preg_match('#<h2 class="playlist-name"><b>(.*?)</b>.*?<span>(.*?)</span></h2>#is', $result, $title);

            $parsed_result[] = array(
                'id' => $results[1][$i],
                'artist_title' => $title[1],
                'title' => $title[2],
                'url' => $download[1],
                'duration' => $results[2][$i],
            );
        }

        Yii::endProfile('gettuneParse');

        return array(
            'count' => count($parsed_result),
            'results' => $parsed_result,
        );
    }
}