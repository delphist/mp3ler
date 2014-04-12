<?php

/**
 * Модель результатов поискового запроса
 */
class Results implements ArrayAccess, Countable, Serializable, IteratorAggregate
{
    /**
     * Число всех результатов поиска
     *
     * @var int
     */
    public $count;

    /**
     * Число доступных результатов поиска
     *
     * @var int
     */
    public $real_count;

    /**
     * Хранилище результатов поиска
     *
     * @var array
     */
    protected $storage = array();

    /**
     * Добавление нового элемента, в котором идет проверка на дубликаты
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === NULL)
        {
            foreach($this->storage as $v)
            {
                if($this->compare($v, $value))
                {
                    return;
                }
            }

            $this->storage[] = $value;
        }
        else
        {
            $this->storage[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->storage[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->storage[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->storage[$offset];
    }

    public function getIterator()
    {
        return new ArrayIterator($this->storage);
    }

    public function serialize()
    {
        return serialize(array(
            'c' => $this->count,
            's' => $this->storage
        ));
    }

    public function unserialize($data)
    {
        $result = unserialize($data);

        if(isset($result['c']))
        {
            $this->count = $result['c'];
        }

        if(isset($result['c']))
        {
            $this->storage = $result['s'];
        }
    }

    public function count()
    {
        return count($this->storage);
    }

    /**
     * Сравнивает два элемента поиска на предмет дубликатов
     *
     * @param $a
     * @param $b
     * @return bool TRUE если элементы дублируют друг друга
     */
    public function compare($a, $b)
    {
        return $this->_compare_normalize($a) == $this->_compare_normalize($b);
    }

    /**
     * Нормализует результат поиска в строку, подходящую для сравнений
     *
     * @param $result
     * @return string
     */
    public function _compare_normalize($r)
    {
        $result = $r['artist_title'].' - '.$r['title'];
        $result = mb_strtolower($result);
        $result = preg_replace('/([^\w\d]+|[_]+)/ius', '', $result);

        return $result;
    }
}