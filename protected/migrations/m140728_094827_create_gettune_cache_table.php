<?php

class m140728_094827_create_gettune_cache_table extends CDbMigration
{
    public function up()
    {
        $this->execute("CREATE TABLE `gettune_cache` (
            `id` char(32) NOT NULL,
            `response_data` longtext,
            `modified_at` datetime NOT NULL,
            `page` int(11) NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`)
        );");
    }

    public function down()
    {
        $this->execute("DROP TABLE `gettune_cache`");
    }
}