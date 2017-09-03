<?php

use yii\db\Migration;

class m170903_174217_electionsVotesUsers extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('electionsVotesUsers', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'electionId' => $this->integer()->unsigned()->notNull(),
            'userId' => $this->integer()->unsigned()->notNull(),
            'variant' => $this->integer(3)->unsigned()->notNull(),
            'tileId' => $this->integer()->unsigned()->notNull(),
            'districtId' => $this->integer()->unsigned()->notNull(),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
        ]);
        $this->createIndex('electionIdVotesUsers', 'electionsVotesUsers', ['electionId']);
        $this->createIndex('userIdVotesUsers', 'electionsVotesUsers', ['userId']);
        $this->createIndex('election2user', 'electionsVotesUsers', ['electionId', 'userId'], true);
        $this->createIndex('tileIdVotesUsers', 'electionsVotesUsers', ['tileId']);
        $this->createIndex('districtIdVotesUsers', 'electionsVotesUsers', ['districtId']);
        $this->createIndex('dateCreatedVotesUsers', 'electionsVotesUsers', ['dateCreated']);
    }

    public function safeDown()
    {
        $this->dropTable('electionsVotesUsers');
    }

}
