<?php

use yii\db\Migration;

class m170903_180728_messages extends Migration
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
        $this->dropTable('messages');
    }

}
