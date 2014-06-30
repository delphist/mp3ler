<?php

class m140630_060608_create_billboard_table extends CDbMigration
{
    public function up()
    {
        $this->execute("CREATE TABLE `billboard` (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `position` int(11) UNSIGNED DEFAULT NULL,
            `created_at` timestamp NOT NULL,
            PRIMARY KEY (`id`),
            INDEX (`position`)
        );");
    }

    public function down()
    {
        $this->execute("DROP TABLE `billboard`");
    }
}