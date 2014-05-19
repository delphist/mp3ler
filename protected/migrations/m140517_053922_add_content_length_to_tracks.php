<?php

class m140517_053922_add_content_length_to_tracks extends CDbMigration
{
    public function up()
    {
        $this->execute("ALTER TABLE `track` ADD COLUMN `content_length` int(11) UNSIGNED NULL DEFAULT NULL;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `track` DROP COLUMN `content_length`;");
    }
}