<?php

class m140326_022539_create_query_table extends CDbMigration
{
	public function up()
	{
        $this->execute("CREATE TABLE `query` (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `text` varchar(255) NOT NULL,
            `results_count` int(11) UNSIGNED NOT NULL,
            PRIMARY KEY (`id`)
        )");
	}

	public function down()
	{
        $this->execute("DROP TABLE `query`");
	}
}