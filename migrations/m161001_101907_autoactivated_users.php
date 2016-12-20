<?php

use yii\db\Migration;

class m161001_101907_autoactivated_users extends Migration
{
    
    public function safeUp()
    {
        $this->truncateTable('accounts');
        $this->truncateTable('users');        
    }

    public function safeDown()
    {
        $this->truncateTable('accounts');
        $this->truncateTable('users');
    }
}
