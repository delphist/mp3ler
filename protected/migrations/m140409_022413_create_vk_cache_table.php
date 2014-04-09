<?php

class m140409_022413_create_vk_cache_table extends CDbMigration
{
	public function up()
	{
        $this->execute("CREATE TABLE `vk_cache` (
            `id` char(32) NOT NULL,
            `response_data` text,
            `modified_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
        );");
	}

    public function down()
    {
        $this->execute("DROP TABLE `vk_cache`");
    }
}