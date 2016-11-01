<?php

use yii\db\Migration;

class m160825_103122_user_location extends Migration
{
    public function safeUp()
    {
        $this->dropTable('users');
        $this->createTable('users', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'avatar' => 'TEXT NOT NULL',
            'avatarBig' => 'TEXT NOT NULL',
            'genderId' => 'UNSIGNED INTEGER(1) NOT NULL',

            'cityId' => 'UNSIGNED INTEGER REFERENCES cities(id) NOT NULL',

            'ideologyId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'religionId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',

            'fame' => 'INTEGER NOT NULL DEFAULT 0',
            'trust' => 'INTEGER NOT NULL DEFAULT 0',
            'success' => 'INTEGER NOT NULL DEFAULT 0',
            'fameBase' => 'INTEGER NOT NULL DEFAULT 0',
            'trustBase' => 'INTEGER NOT NULL DEFAULT 0',
            'successBase' => 'INTEGER NOT NULL DEFAULT 0',

            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateLastLogin' => 'UNSIGNED INTEGER NOT NULL',

            'isInvited' => 'BOOLEAN NOT NULL DEFAULT 0',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
//        $this->insert('users', [
//            'name' => 'Ilya Gorokhov',
//            'avatar' => 'http://placehold.it/50x50',
//            'avatarBig' => 'http://placehold.it/400x600',
//            'genderId' => 2,
//            'cityId' => 1,
//            'dateCreated' => time(),
//            'dateLastLogin' => time(),
//            'isInvited' => 1
//        ]);

    }

    public function safeDown()
    {
        $this->dropTable('users');
        $this->createTable('users', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'avatar' => 'TEXT NOT NULL',
            'avatarBig' => 'TEXT NOT NULL',
            'genderId' => 'UNSIGNED INTEGER(1) NOT NULL',

            'locationId' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',

            'ideologyId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'religionId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',

            'fame' => 'INTEGER NOT NULL DEFAULT 0',
            'trust' => 'INTEGER NOT NULL DEFAULT 0',
            'success' => 'INTEGER NOT NULL DEFAULT 0',
            'fameBase' => 'INTEGER NOT NULL DEFAULT 0',
            'trustBase' => 'INTEGER NOT NULL DEFAULT 0',
            'successBase' => 'INTEGER NOT NULL DEFAULT 0',

            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateLastLogin' => 'UNSIGNED INTEGER NOT NULL',

            'isInvited' => 'BOOLEAN NOT NULL DEFAULT 0',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
    }

}
