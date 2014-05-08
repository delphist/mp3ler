<?php

class m140503_051519_create_payout_table extends CDbMigration
{
    public function up()
    {
        $this->execute("CREATE TABLE `payout` (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `user_id` int(11) UNSIGNED NOT NULL,
            `amount` decimal(11,2) UNSIGNED NOT NULL,
            `start_date` timestamp NOT NULL,
            `end_date` timestamp NOT NULL,
            `is_payed` tinyint UNSIGNED NOT NULL DEFAULT '0',
            `created_at` timestamp NOT NULL,
            `payed_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        );");
    }

    public function down()
    {
        $this->execute("DROP TABLE `payout`");
    }
}