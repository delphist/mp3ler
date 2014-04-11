<?php

/**
 * Класс работы с API вконтакте, реализует автомаический выбор
 * аккаунтов из базы данных, проверки каптчи и ее обработки
 */
class VkApi extends CComponent
{
    /**
     * URL API вконтакте
     */
    protected $api_url = 'http://api.vk.com/api.php';

    /**
     * Максимальное количество попыток запросов к API
     * через разные аккаунты
     */
    protected $max_attempts = 3;

    /**
     * Выполняет запрос к VK Api через аккаунты, пока
     * не получит нужный результат
     *
     * @param $method название метода
     * @param bool $params параметры
     * @return SimpleXMLElement|null ответ сервера вконтакте либо null
     *         в случае если ни один аккаунт не сработал
     */
    public function execute($method, $params = FALSE)
    {
        $attempt = 1;
        $account_ids = array();

        while($attempt <= $this->max_attempts)
        {
            $attempt++;

            $not_account_condition = '';
            if(count($account_ids))
            {
                $not_account_condition = ' AND id NOT IN ('.implode($account_ids, ', ').')';
            }

            /**
             * Сначала ищем аккаунты, у которых уже решена, но еще не введена
             * каптча
             */
            $account = NULL;
            //$account = VkAccount::model()->find(array(
            //    'condition' => 'is_alive=1 AND is_captcha_response=1' . $not_account_condition
            //));

            if($account === NULL)
            {
                /**
                 * Если таких аккаунтов нет, то вытаскиваем случайный живой аккаунт
                 * без каптчи
                 */

                $account = VkAccount::model()->find(array(
                    'condition' => 'is_alive=1 AND (is_captcha_request=0 OR is_captcha_response=1)' . $not_account_condition,
                    'order' => 'RAND()'
                ));
            }

            if($account === NULL)
            {
                return NULL;
            }

            $account_ids[] = $account->id;
            $result = $this->execute_account($account, $method, $params);

            if(isset($result->error_code))
            {
                $error_message = $result->error_code.': '.$result->error_msg.' ('.$account->id.')';

                if($result->error_code == 14)
                {
                    /**
                     * Поймали каптчу, сохраняем ее данные в аккаунт
                     */
                    $account->captcha_request = array(
                        'id' => (string) $result->captcha_sid,
                        'url' => (string) $result->captcha_img,
                    );
                    $account->captcha_response = NULL;
                    $account->save();

                    Yii::log('Got captcha ('.$account->id.')', 'info', 'vkapi');
                }
                elseif($result->error_code == 6)
                {
                    /**
                     * Слишком много запросов в секунду
                     */

                    Yii::log($error_message, 'warning', 'vkapi');

                    return NULL;
                }
                else
                {
                    /**
                     * Произошла ошибка, помечаем аккаунт как битый
                     */

                    Yii::log($error_message, 'error', 'vkapi');

                    $account->error_response = json_encode($result);
                    $account->is_alive = FALSE;
                    $account->save();

                    return NULL;
                }
            }
            else
            {
                /**
                 * Каптча не вылетала, выдаем результат и обнуляем
                 * поля каптчи аккаунта
                 */

                return $result;
            }
        }

        return NULL;
    }

    /**
     * Выполняет запрос к VK Api через определенный аккаунт
     *
     * @param VkAccount $account обьект аккаунта
     * @param string $method название метода
     * @param array $params параметры
     * @return SimpleXMLElement|null ответ сервера вконтакте либо null в случае ошибки
     */
    public function execute_account(VkAccount $account, $method, array $params = NULL)
    {
        if ($params === NULL)
        {
            $params = array();
        }

        $params = array_merge($params, array(
            'api_id' => $account->app_id,
            'v' => '3.0',
            'test_mode' => 1,
            'method' => $method,
        ));

        if($account->captcha_response)
        {
            $params['captcha_sid'] = $account->captcha_request['id'];
            $params['captcha_key'] = $account->captcha_response;

            $account->captcha_response = NULL;
            $account->captcha_request = NULL;
            $account->save();
        }

        ksort($params);

        $signature = $account->vk_id;
        foreach($params as $k => $v)
        {
            $signature .= $k.'='.$v;
        }

        $params['sig'] = md5($signature);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $account->app_url());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17');

        $data = curl_exec($ch);
        curl_close($ch);

        $result = simplexml_load_string($data);

        return $result;
    }

    /**
     * Решает каптчу для аккаунта
     *
     * @param VkAccount $account обьект аккаунта
     * @param bool статус решения
     */
    public function solve_captcha(VkAccount $account)
    {
        if($account->is_captcha_response)
        {
            /**
             * Каптча уже решена
             */

            return TRUE;
        }
        elseif(isset($account->captcha_request['url']))
        {
            /**
             * Каптча еще не решалась
             */
            $solve_id = Yii::app()->captchaSolver->request($account->captcha_request['url']);

            if($solve_id !== FALSE)
            {
                $account->captcha_request = array(
                    'id' => $account->captcha_request['id'],
                    'solve_id' => $solve_id
                );
                $account->save();
            }

            return FALSE;
        }
        elseif(isset($account->captcha_request['solve_id']))
        {
            /**
             * Каптча отправлена на решение
             */
            $response = Yii::app()->captchaSolver->response($account->captcha_request['solve_id']);

            if($response === TRUE)
            {
                return FALSE;
            }
            elseif($response === FALSE)
            {
                $account->captcha_response = NULL;
                $account->captcha_request = NULL;
                $account->save();
            }
            else
            {
                $account->captcha_response = $response;
                $account->save();
            }

            return TRUE;
        }
    }
}