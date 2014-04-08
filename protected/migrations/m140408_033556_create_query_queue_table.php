<?php

class m140408_033556_create_query_queue_table extends CDbMigration
{
    public function up()
    {
        $this->execute("CREATE TABLE `query_queue` (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `query_id` int(11) UNSIGNED NOT NULL,
            PRIMARY KEY (`id`)
        );");
    }

    public function down()
    {
        $this->execute("DROP TABLE `query_queue`");
    }
}