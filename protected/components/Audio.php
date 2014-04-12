<?php

/**
 * Класс поиска музыки
 */
abstract class Audio extends CComponent
{
    /**
     * @var string поисковой запрос
     */
    public $query;

    /**
     * @var int номер страницы
     */
    public $page = 1;

    /**
     * @var int количество результатов на страницу
     */
    public $perpage = 20;

    /**
     * Метод поиска музыки, должен быть реализован во всех наследниках
     * Должен возвращать экземпляр класса Results
     *
     * @return Results
     */
    abstract function results();

    /**
     * Обьект должен создаваться и инициализировать текстовый запрос
     *
     * @param string $query текстовый запрос
     * @param int $page номер страницы
     */
    public function __construct($query, $page = 1)
    {
        $this->query = $query;
        $this->page = $page;
    }

    /**
     * Преобразует строку полученную от сервера,
     * убирая лишние проблемы, декодируя html-коды
     *
     * @param $value
     * @return string
     */
    protected function decode_string($value)
    {
        $value = trim($value);
        $value = htmlspecialchars_decode($value);
        $value = preg_replace('/\s+/su', ' ', $value);

        return $value;
    }
}