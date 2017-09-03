<?php

use yii\db\Migration;

class m170903_152414_agencies extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('agencies', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'stateId' => $this->integer()->unsigned()->notNull(),
            'leaderId' => $this->integer()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'nameShort' => $this->string(10)->notNull(),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
            'dateDeleted' => $this->integer()->unsigned()->null(),
            'utr' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('stateIdAgencies', 'agencies', ['stateId']);
        $this->createIndex('leaderIdAgencies', 'agencies', ['leaderId']);
        $this->createIndex('nameAgencies', 'agencies', ['name']);
        $this->createIndex('nameShortAgencies', 'agencies', ['nameShort']);
        $this->createIndex('dateCreatedAgencies', 'agencies', ['dateCreated']);
        $this->createIndex('dateDeletedAgencies', 'agencies', ['dateDeleted']);
        $this->createIndex('utrAgencies', 'agencies', ['utr'], true);
    }

    public function safeDown()
    {
        $this->dropTable('agencies');
    }
    
}
