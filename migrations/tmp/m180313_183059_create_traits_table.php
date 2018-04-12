<?php

use yii\db\Migration;

class m180313_183059_create_traits_table extends Migration
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
        $this->addForeignKey('userIdTraits', 'traits', ['userId'], 'users', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('userIdTraits', 'traits');
        $this->dropIndex('user2trait', 'traits');
        $this->dropIndex('userIdTraits', 'traits');
        $this->dropIndex('dateReceivedTraits', 'traits');
        $this->dropIndex('dateExpiredTraits', 'traits');
        $this->dropTable('traits');
    }

}
