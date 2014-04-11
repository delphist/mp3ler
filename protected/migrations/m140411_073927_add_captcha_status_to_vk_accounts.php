<?php

class m140411_073927_add_captcha_status_to_vk_accounts extends CDbMigration
{
    public function up()
    {
        $this->execute("ALTER TABLE `vk_account` ADD COLUMN `captcha_count` int(11) NOT NULL DEFAULT 0;");
        $this->execute("ALTER TABLE `vk_account` ADD COLUMN `request_count` int(11) NOT NULL DEFAULT 0;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `vk_account` DROP COLUMN `captcha_count`;");
        $this->execute("ALTER TABLE `vk_account` DROP COLUMN `request_count`;");
    }
}