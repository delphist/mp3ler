<?php

class VkApi extends CComponent
{
    /**
     * URL API вконтакте
     */
    const API_URL = 'http://api.vk.com/api.php';
    const MAX_ATTEMPTS = 10;

    /**
     * Выполняет запрос к VK Api через аккаунты, пока
     * не получит нужный результат
     *
     * @param $method название метода
     * @param bool $params параметры
     */
    public function execute($method, $params = FALSE)
    {
        $attempt = 0;

        while($attempt < self::MAX_ATTEMPTS)
        {
            $attempt++;

            $account = current(VkAccount::model()->findAll());
            $result = $account->execute_api($method, $params);

            return $result;
        }
    }
}