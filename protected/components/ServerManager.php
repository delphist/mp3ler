<?php

/**
 * Компонент доступа к информации о серверах
 */
class ServerManager extends CApplicationComponent
{
    /**
     * @var int Список доступных серверов
     */
    public $servers;

    /**
     * @var string Входной домен сайта (вместе с портом)
     */
    public $mainHost;

    protected $_currentServerIp;
    protected $_currentServerId;

    /**
     * Id текущего сервера
     *
     * @return int
     */
    public function getCurrentServerId()
    {
        if($this->_currentServerId === NULL)
        {
            foreach($this->servers as $server)
            {
                if($server['ip'] == $this->currentServerIp)
                {
                    $this->_currentServerId = $server['id'];

                    break;
                }
            }
        }

        return $this->_currentServerId;
    }

    /**
     * Возвращает, указан ли другой сервер принудительно (через другой домен)
     *
     * @return bool
     */
    public function getIsCurrentServerDirectly()
    {
        $host = $_SERVER['HTTP_HOST'];

        foreach($this->servers as $server)
        {
            if(mb_strtolower($server['host']) == mb_strtolower($host) && $server['id'] == $this->currentServerId)
            {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Ip текущего сервера
     *
     * @return string
     */
    public function getCurrentServerIp()
    {
        if($this->_currentServerIp === NULL)
        {
            if(function_exists('gethostname'))
            {
                $this->_currentServerIp = gethostbyname(gethostname());
            }
            else
            {
                $this->_currentServerIp = gethostbyname(trim(`hostname`));
            }
        }

        return $this->_currentServerIp;
    }

    /**
     * Генерирует URL на определенный сервер
     *
     * @param string $url сформированный относительный url-адрес
     * @param int $server_id id сервера
     * @return string|false абсолютный url либо false в случае если сервер не найден
     */
    public function createUrl($url, $server_id = NULL)
    {
        $server = $this->getServer($server_id);

        if($server === FALSE)
        {
            return FALSE;
        }

        return 'http://'.$server['host'].$url;
    }

    /**
     * Возвращает сервер по его id
     *
     * @param $id ID сервера
     * @return bool|array данные о сервере, либо FALSE если сервер не найден
     */
    public function getServer($id)
    {
        foreach($this->servers as $server)
        {
            if($id == $server['id'])
            {
                return $server;
            }
        }

        return FALSE;
    }
}