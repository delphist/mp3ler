<?php

class VkSearch extends CComponent
{
    private $api;
    protected $account;
    protected $attempts = 0;

    public function search($query, $page = 1)
    {

    }

    protected function api()
    {
        if($this->api === NULL)
        {
            $this->api = new vkapi();
        }

        return $this->api;
    }
}