<?php

use yii\db\Migration;

class m170903_181822_traits extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('traits', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'protoId' => $this->integer(5)->unsigned()->notNull(),
            'userId' => $this->integer()->unsigned()->notNull(),
            'dateReceived' => $this->integer()->unsigned()->notNull(),
            'dateExpired' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('user2trait', 'traits', ['userId', 'protoId'], true);
        $this->createIndex('userIdTraits', 'traits', ['userId']);
        $this->createIndex('dateReceivedTraits', 'traits', ['dateReceived']);
        $this->createIndex('dateExpiredTraits', 'traits', ['dateExpired']);
    }

    public function safeDown()
    {
        $this->dropTable('traits');
    }

}
