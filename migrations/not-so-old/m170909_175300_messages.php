<?php

use yii\db\Migration;

class m170909_175300_messages extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('messages', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'typeId' => $this->integer(3)->unsigned()->notNull(),
            'senderId' => $this->integer()->unsigned()->notNull(),
            'recipientId' => $this->integer()->unsigned()->notNull(),
            'text' => $this->text()->notNull(),
            'textHtml' => $this->text()->notNull(),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
            'dateUpdated' => $this->integer()->unsigned()->null(),
            'dateDeleted' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('type2recipientMessages', 'messages', ['typeId', 'recipientId']);
        $this->createIndex('senderIdMessages', 'messages', ['senderId']);
        $this->createIndex('recipientIdMessages', 'messages', ['recipientId']);
        $this->createIndex('dateCreatedMessages', 'messages', ['dateCreated']);
        $this->createIndex('dateUpdatedMessages', 'messages', ['dateUpdated']);
        $this->createIndex('dateDeletedMessages', 'messages', ['dateDeleted']);
    }

    public function safeDown()
    {
        $this->dropIndex('type2recipientMessages', 'messages');
        $this->dropIndex('senderIdMessages', 'messages');
        $this->dropIndex('recipientIdMessages', 'messages');
        $this->dropIndex('dateCreatedMessages', 'messages');
        $this->dropIndex('dateUpdatedMessages', 'messages');
        $this->dropIndex('dateDeletedMessages', 'messages');
        $this->dropTable('messages');
    }

}
