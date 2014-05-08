<?php

class m140508_113033_payout_fixes extends CDbMigration
{
    public function up()
    {
        $this->execute("ALTER TABLE `payout` ADD COLUMN `transitions` int(11) UNSIGNED NOT NULL AFTER `amount`, CHANGE COLUMN `start_date` `start_date` timestamp NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT NULL AFTER `transitions`, CHANGE COLUMN `end_date` `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `start_date`, CHANGE COLUMN `is_payed` `is_payed` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' AFTER `end_date`, CHANGE COLUMN `created_at` `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `is_payed`, CHANGE COLUMN `payed_at` `payed_at` timestamp NULL DEFAULT NULL AFTER `created_at`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `payout` DROP COLUMN `transitions`, CHANGE COLUMN `start_date` `start_date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP;");
    }
}