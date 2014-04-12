<?php

class m140412_051231_change_vk_cache_response_length extends CDbMigration
{
    public function up()
    {
        $this->execute("ALTER TABLE `vk_cache` CHANGE COLUMN `response_data` `response_data` longtext DEFAULT NULL;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `vk_cache` CHANGE COLUMN `response_data` `response_data` text DEFAULT NULL;");
    }
}