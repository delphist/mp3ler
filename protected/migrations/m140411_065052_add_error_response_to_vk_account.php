<?php

class m140411_065052_add_error_response_to_vk_account extends CDbMigration
{
	public function up()
	{
        $this->execute("ALTER TABLE `vk_account` ADD COLUMN `error_response` text NOT NULL AFTER `captcha_response_data`;");
	}

	public function down()
	{
        $this->execute("ALTER TABLE `vk_account` DROP COLUMN `error_response`;");
	}
}