<?php

use yii\db\Migration;

class m170907_234529_users extends Migration
{
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'accountId' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
            'avatar' => $this->text()->null(),
            'avatarBig' => $this->text()->null(),
            'gender' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'tileId' => $this->integer()->unsigned()->null(),
            'ideologyId' => $this->smallInteger(4)->unsigned()->null(),
            'fame' => $this->integer()->notNull()->defaultValue(0),
            'trust' => $this->integer()->notNull()->defaultValue(0),
            'success' => $this->integer()->notNull()->defaultValue(0),
            'fameBase' => $this->integer()->notNull()->defaultValue(0),
            'trustBase' => $this->integer()->notNull()->defaultValue(0),
            'successBase' => $this->integer()->notNull()->defaultValue(0),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
            'utr' => $this->integer()->unsigned()->unique()->null(),
        ]);
        $this->createIndex('accountIdUsers', 'users', ['accountId']);
        $this->createIndex('nameUsers', 'users', ['name']);
        $this->createIndex('genderUsers', 'users', ['gender']);
        $this->createIndex('tileIdUsers', 'users', ['tileId']);
        $this->createIndex('ideologyIdUsers', 'users', ['ideologyId']);
        $this->createIndex('fameUsers', 'users', ['fame']);
        $this->createIndex('trustUsers', 'users', ['trust']);
        $this->createIndex('successUsers', 'users', ['success']);
        $this->createIndex('dateCreatedUsers', 'users', ['dateCreated']);
        $this->createIndex('utrUsers', 'users', ['utr'], true);
        $this->addForeignKey('accountIdUsersRef', 'users', ['accountId'], 'accounts', ['id']);
        
        $this->addForeignKey('user2stateUserRef', 'citizenships', ['userId'], 'users', ['id']);
        $this->addForeignKey('postsUserRef', 'agenciesPosts', ['userId'], 'users', ['id']);
        $this->addForeignKey('partyPostsUserRef', 'partiesPosts', ['userId'], 'users', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('partyPostsUserRef', 'partiesPosts');
        $this->dropForeignKey('postsUserRef', 'agenciesPosts');
        $this->dropForeignKey('user2stateUserRef', 'citizenships');
        
        $this->dropForeignKey('accountIdUsersRef', 'users');
        $this->dropIndex('accountIdUsers', 'users');
        $this->dropIndex('nameUsers', 'users');
        $this->dropIndex('genderUsers', 'users');
        $this->dropIndex('tileIdUsers', 'users');
        $this->dropIndex('ideologyIdUsers', 'users');
        $this->dropIndex('fameUsers', 'users');
        $this->dropIndex('trustUsers', 'users');
        $this->dropIndex('successUsers', 'users');
        $this->dropIndex('dateCreatedUsers', 'users');
        $this->dropIndex('utrUsers', 'users');
        $this->dropTable('users');
    }
    
}
