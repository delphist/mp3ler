<?php

class m140422_031749_create_user_table extends CDbMigration
{
    public function up()
    {
        $this->execute("CREATE TABLE `user` (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `username` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `hashed_password` char(40) NOT NULL,
            `sitename` varchar(255) DEFAULT NULL,
            `group` varchar(20) NOT NULL,
            `remind_token` char(40) DEFAULT NULL,
            `webmoney_details` varchar(50) DEFAULT NULL,
            `paypal_details` varchar(50) DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE `username` (username),
	        UNIQUE `email` (email),
	        UNIQUE `remind_token` (remind_token)
        );");
    }

    public function down()
    {
        $this->execute("DROP TABLE `user`");
    }
}