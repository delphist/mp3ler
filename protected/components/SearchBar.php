<?php

class SearchBar extends CWidget
{
    public $query;

    public function run()
    {
        $this->render('searchBar');
    }
}