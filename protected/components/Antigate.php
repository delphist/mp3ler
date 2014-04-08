<?php

/**
 * Класс распознавания каптчи
 */
class Antigate extends CApplicationComponent
{
    public $api_request = 'http://antigate.com/in.php';

    public $api_response = 'http://antigate.com/res.php';

    public $apikey;

    public $default_params = array(
        'phrase' => 0,
        'regsense' => 0,
        'numeric' => 0,
        'min_len' => 3,
        'max_len' => 9,
        'russian' => 0,
    );

    public $request_timeout = 2;

    public $timeout = 60;

    /**
     * Создает запрос на решение каптчи, возвращает
     * идентификатор, по которому можно получить результат
     * решения через метод response
     *
     * @param $url URL-адрес картинки каптчи
     * @param array $params дополнительные параметры antigate
     * @return bool|string идентификатор каптчи либо false в случае ошибки
     */
    public function request($url, array $params = NULL)
    {
        /**
         * Скачиваем картинку и сохраняем ее во временный файл
         */
        $temp_filename = tempnam(sys_get_temp_dir(), 'VKC');
        $temp_file = fopen($temp_filename, 'w+');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_FILE => $temp_file,
            CURLOPT_FOLLOWLOCATION => TRUE,
        ));
        $result = curl_exec($curl);

        if(curl_errno($curl))
        {
            Yii::log('Curl download captcha error: '.curl_errno($curl).' (url: '.$url.')', 'error', 'antigate');

            return FALSE;
        }

        fclose($temp_file);

        if ( ! function_exists('curl_file_create'))
        {
            $curl_file = '@'.$temp_filename;
        }
        else
        {
            $curl_file = curl_file_create($temp_filename);
        }

        $postdata = array_merge($this->default_params, array(
            'method' => 'post',
            'key' => $this->apikey,
            'file' => $curl_file,
        ));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_request,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $postdata
        ));
        $result = curl_exec($curl);

        if(curl_errno($curl))
        {
            Yii::log('Curl error: '.curl_errno($curl).' (url: '.$url.')', 'error', 'antigate');

            return FALSE;
        }

        curl_close($curl);
        unlink($temp_filename);

        if (strpos($result, 'ERROR') !== FALSE)
        {
            Yii::log('Request error: '.$result.' (url: '.$url.')', 'error', 'antigate');

            return FALSE;
        }

        $data = explode('|', $result);

        return $data[1];
    }

    /**
     * Проверяет и возвращает решение каптчи либо false
     * в случае, если произошла ошибка
     *
     * @param $captcha_id идентификатор каптчи
     */
    public function response($captcha_id)
    {
        $time = 0;

        while(TRUE)
        {
            $result = file_get_contents($this->api_response.'?'.http_build_query(array(
                    'key' => $this->apikey,
                    'action' => 'get',
                    'id' => $captcha_id
                )));

            if(strpos($result, 'ERROR') !== FALSE)
            {
                /**
                 * Произошла ошибка
                 */

                Yii::log('Response error: '.$result.' (captcha id: '.$captcha_id.')', 'error', 'antigate');

                return FALSE;
            }
            if($result == 'CAPCHA_NOT_READY')
            {
                /**
                 * Каптча еще не решена, пробуем снова
                 */

                $time += $this->request_timeout;

                if ($time > $this->timeout)
                {
                    Yii::log('Max timeout reached ('.$this->timeout.') for captcha '.$captcha_id, 'error', 'antigate');

                    return FALSE;
                }

                sleep($this->request_timeout);
            }
            else
            {
                /**
                 * Каптча решена, возвращаем решение
                 */

                $data = explode('|', $result);
                if (trim($data[0]) == 'OK') return trim($data[1]);
            }
        }

        return FALSE;
    }
}