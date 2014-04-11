<?php

class m140411_085526_add_downloads_count_to_tracks extends CDbMigration
{
    public function up()
    {
        $this->execute("ALTER TABLE `track` ADD COLUMN `downloads_count` int(11) NOT NULL DEFAULT 1;");
        $this->execute("ALTER TABLE `track` ADD INDEX  (downloads_count);");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `track` DROP COLUMN `downloads_count`;");
    }
}