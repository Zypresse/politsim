<?php

use yii\db\Migration;

class m160921_102750_update_elections extends Migration
{
    public function up()
    {

        $this->dropTable('elections-votes-users');
        $this->createTable('elections-votes-users', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'districtId' => 'UNSIGNED INTEGER REFERENCES `electoral-districts`(id) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('electionsVotesUsers', 'elections-votes-users', ['electionId', 'userId'], true);

        $this->dropTable('elections-votes-pops');
        $this->createTable('elections-votes-pops', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'districtId' => 'UNSIGNED INTEGER REFERENCES `electoral-districts`(id) NOT NULL',
            
            'count' => 'UNSIGNED INTEGER NOT NULL',
            'popData' => 'TEXT NOT NULL',

            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
    }

    public function down()
    {
        $this->dropTable('elections-votes-users');
        $this->createTable('elections-votes-users', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('electionsVotesUsers', 'elections-votes-users', ['electionId', 'userId'], true);

        $this->dropTable('elections-votes-pops');
        $this->createTable('elections-votes-pops', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            
            'count' => 'UNSIGNED INTEGER NOT NULL',
            'popData' => 'TEXT NOT NULL',

            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
    }
}
