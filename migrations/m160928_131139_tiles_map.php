<?php

use yii\db\Migration;

class m160928_131139_tiles_map extends Migration
{

    public function safeUp()
    {
        $this->truncateTable('tiles');
    }

    public function safeDown()
    {
        $this->truncateTable('tiles');
    }
}
