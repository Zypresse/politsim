<?php

use yii\db\Migration;

class m170903_151414_accounts extends Migration
{
    
    public function safeUp()
    {
        
        $this->createTable('accounts', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'userId' => $this->integer()->unsigned()->notNull(),
            'sourceType' => $this->integer(1)->unsigned()->notNull(),
            'sourceId' => $this->string(255)->notNull(),
        ]);
        $this->createIndex('source', 'accounts', ['sourceType', 'sourceId']);
        $this->createIndex('userIdAccounts', 'accounts', ['userId']);

    }

    public function safeDown()
    {
        $this->dropTable('accounts');
    }

}
