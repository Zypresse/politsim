<?php

use yii\db\Migration;

class m170113_120145_bills_discussion extends Migration
{
    public function safeUp()
    {
        
        $this->createTable('messages', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'typeId' => 'UNSIGNED INTEGER(4) NOT NULL', // 1 - лс, 2 - обсуждение законопроекта, етч.
            'senderId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'recipientId' => 'UNSIGNED INTEGER NOT NULL',
            'text' => 'TEXT NOT NULL',
            'textHtml' => 'TEXT NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateUpdated' => 'UNSIGNED INTEGER DEFAULT NULL',
            'dateDeleted' => 'UNSIGNED INTEGER DEFAULT NULL',
        ]);
        
        $this->createIndex('messagesCategory', 'messages', ['typeId', 'recipientId']);
        
    }

    public function safeDown()
    {
        $this->dropTable('messages');
    }
}
