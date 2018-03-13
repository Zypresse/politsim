<?php

use yii\db\Migration;

class m170907_221900_accounts extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('accounts', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'email' => $this->string(255)->unique()->null(),
            'password' => $this->string(255)->null(),
            'accessToken' => $this->string(255)->null(),
            'role' => $this->smallInteger(2)->unsigned()->notNull()->defaultValue(1),
            'status' => $this->smallInteger(2)->unsigned()->notNull()->defaultValue(0),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
        ]);
        $this->createIndex('accountsEmail', 'accounts', ['email']);
        $this->createIndex('accountsStatus', 'accounts', ['status']);
        $this->createIndex('accountsDateCreated', 'accounts', ['dateCreated']);
        
        $this->createTable('accountsSources', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'accountId' => $this->integer()->unsigned()->notNull(),
            'sourceType' => $this->integer(1)->unsigned()->notNull(),
            'sourceId' => $this->string(255)->notNull(),
        ]);
        $this->createIndex('source', 'accountsSources', ['sourceType', 'sourceId'], true);
        $this->createIndex('userIdAccountsSources', 'accountsSources', ['accountId']);
        $this->addForeignKey('userIdAccountsSourcesRef', 'accountsSources', ['accountId'], 'accounts', ['id']);

    }

    public function safeDown()
    {
        $this->dropIndex('source', 'accountsSources');
        $this->dropIndex('userIdAccountsSources', 'accountsSources');
        $this->dropForeignKey('userIdAccountsSourcesRef', 'accountsSources');
        $this->dropTable('accountsSources');
        
        $this->dropIndex('accountsDateCreated', 'accounts');
        $this->dropIndex('accountsStatus', 'accounts');
        $this->dropIndex('accountsEmail', 'accounts');
        $this->dropTable('accounts');
    }

}
