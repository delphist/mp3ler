<?php

/**
 * Модель кеша результатов аудио из get-tune.net
 *
 * @property string $id
 */
class GettuneCache extends SourceCache
{
    public function tableName()
    {
        return 'gettune_cache';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
