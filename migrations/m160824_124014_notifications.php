<?php

use yii\db\Migration;

class m160824_124014_notifications extends Migration
{
    public function up()
    {
        $this->dropTable('notifications');
        $this->createTable('notifications', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',            
            'protoId' => 'UNSIGNED INTEGER(5) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'text' => 'TEXT DEFAULT NULL',
            'textShort' => 'VARCHAR(255) DEFAULT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateReaded' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);
    }

    public function down()
    {
        $this->dropTable('notifications');
        $this->createTable('notifications', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',            
            'protoId' => 'UNSIGNED INTEGER(5) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'text' => 'TEXT NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateReaded' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);
    }
}
