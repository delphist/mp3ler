<?php

class m140404_024609_add_results_to_queries extends CDbMigration
{
	public function up()
	{
        $this->execute("ALTER TABLE `query` ADD COLUMN `results` text DEFAULT NULL AFTER `results_count`;");
	}

	public function down()
	{
        $this->execute("ALTER TABLE `query` DROP COLUMN `results`;");
	}
}