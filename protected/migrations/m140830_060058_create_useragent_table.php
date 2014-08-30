<?php

class m140830_060058_create_useragent_table extends CDbMigration
{
    public function up()
    {
        $this->execute("CREATE TABLE `useragent` (
            `id` char(32) NOT NULL,
            `title` varchar(255) NOT NULL,
            `headers` TEXT,
            PRIMARY KEY (`id`)
        );");
    }

    public function down()
    {
        $this->execute("DROP TABLE `useragent`");
    }
}