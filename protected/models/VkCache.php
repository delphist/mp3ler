<?php

/**
 * Модель кеша результатов аудио из вконтакте
 *
 * @property string $id
 */
class VkCache extends SourceCache
{
    public function tableName()
    {
        return 'vk_cache';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
