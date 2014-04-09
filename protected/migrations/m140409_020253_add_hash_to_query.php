<?php

class m140409_020253_add_hash_to_query extends CDbMigration
{
	public function up()
	{
        $this->execute("ALTER TABLE `query` ADD COLUMN `hash` char(32) NOT NULL AFTER `results_count`, ADD INDEX USING HASH (`hash`);");
	}

	public function down()
	{
        $this->execute("ALTER TABLE `query` DROP COLUMN `hash`;");
	}
}