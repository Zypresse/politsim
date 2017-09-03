<?php

use yii\db\Migration;

class m170903_182246_parties extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('parties', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'stateId' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
            'nameShort' => $this->string(10)->notNull(),
            'flag' => $this->string(255)->null(),
            'anthem' => $this->string(255)->null(),
            'ideologyId' => $this->integer(3)->unsigned()->notNull(),
            'text' => $this->text()->null(),
            'textHtml' => $this->text()->null(),
            'fame' => $this->integer()->notNull()->defaultValue(0),
            'trust' => $this->integer()->notNull()->defaultValue(0),
            'success' => $this->integer()->notNull()->defaultValue(0),
            'membersCount' => $this->integer(5)->unsigned()->notNull()->defaultValue(1),
            'leaderPostId' => $this->integer()->unsigned()->notNull(),
            'joiningRules' => $this->integer(2)->unsigned()->notNull(),
            'listCreationRules' => $this->integer(2)->unsigned()->notNull(),
            'utr' => $this->integer()->unsigned()->null(),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
            'dateConfirmed' => $this->integer()->unsigned()->null(),
            'dateDeleted' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('stateIdParties', 'parties', ['stateId']);
        $this->createIndex('nameParties', 'parties', ['name']);
        $this->createIndex('nameShortParties', 'parties', ['nameShort']);
        $this->createIndex('flagParties', 'parties', ['flag']);
        $this->createIndex('anthemParties', 'parties', ['anthem']);
        $this->createIndex('ideologyIdParties', 'parties', ['ideologyId']);
        $this->createIndex('fameParties', 'parties', ['fame']);
        $this->createIndex('trustParties', 'parties', ['trust']);
        $this->createIndex('successParties', 'parties', ['success']);
        $this->createIndex('membersCountParties', 'parties', ['membersCount']);
        $this->createIndex('utrParties', 'parties', ['utr']);
        $this->createIndex('dateCreatedParties', 'parties', ['dateCreated']);
        $this->createIndex('dateConfirmedParties', 'parties', ['dateConfirmed']);
        $this->createIndex('dateDeletedParties', 'parties', ['dateDeleted']);
    }

    public function safeDown()
    {
        $this->dropTable('parties');
    }

}
