<?php

class m140404_040818_create_vk_account_table extends CDbMigration
{
    public function up()
    {
        $this->execute("CREATE TABLE `vk_account` (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `vk_id` int(11) NOT NULL,
            `app_id` int(11) NOT NULL,
            `is_alive` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
            `is_captcha_request` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
            `is_captcha_response` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
            `captcha_request` text,
	        `captcha_response` varchar(20) DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE  (vk_id, app_id)
        );");
    }

    public function down()
    {
        $this->execute("DROP TABLE `vk_account`");
    }
}