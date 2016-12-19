<?php

use yii\db\Migration;

class m160921_102750_update_elections extends Migration
{
    public function up()
    {

        $this->dropTable('electionsVotesUsers');
        $this->createTable('electionsVotesUsers', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'districtId' => 'UNSIGNED INTEGER REFERENCES `electoralDistricts`(id) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('electionsVotesUsersPrimary', 'electionsVotesUsers', ['electionId', 'userId'], true);

        $this->dropTable('electionsVotesPops');
        $this->createTable('electionsVotesPops', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'districtId' => 'UNSIGNED INTEGER REFERENCES `electoralDistricts`(id) NOT NULL',
            
            'count' => 'UNSIGNED INTEGER NOT NULL',
            'popData' => 'TEXT NOT NULL',

            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
    }

    public function down()
    {
        $this->dropTable('electionsVotesUsers');
        $this->createTable('electionsVotesUsers', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('electionsVotesUsersPrimary', 'electionsVotesUsers', ['electionId', 'userId'], true);

        $this->dropTable('electionsVotesPops');
        $this->createTable('electionsVotesPops', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            
            'count' => 'UNSIGNED INTEGER NOT NULL',
            'popData' => 'TEXT NOT NULL',

            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
    }
}
