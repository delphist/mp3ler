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
}