<?php

class m140404_064130_create_track_table extends CDbMigration
{
    public function up()
    {
        $this->execute("CREATE TABLE `track` (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `artist_title` varchar(255) NOT NULL,
            `title` varchar(255) NOT NULL,
            `file` varchar(255) DEFAULT NULL,
            `external_type` varchar(10) NOT NULL,
            `external_id` varchar(30) NOT NULL,
            `external_data` text,
            PRIMARY KEY (`id`)
        );");
    }

    public function down()
    {
        $this->execute("DROP TABLE `track`");
    }
}