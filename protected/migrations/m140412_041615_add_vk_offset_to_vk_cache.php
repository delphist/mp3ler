<?php

class m140412_041615_add_vk_offset_to_vk_cache extends CDbMigration
{
    public function up()
    {
        $this->execute("ALTER TABLE `vk_cache` ADD COLUMN `vk_offset` int(11) NOT NULL DEFAULT 0;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `vk_cache` DROP COLUMN `vk_offset`;");
    }
}